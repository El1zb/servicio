<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Period;
use App\Models\Student;
use App\Models\Campus;
use App\Models\Career;
use App\Models\File;
use App\Models\Document;

class PeriodDetail extends Component
{
    use WithPagination, WithFileUploads;

    /** ========================= Tabs / Periodo ========================= */
    public $periodId;
    public $period;
    public $activeTab = 'estudiantes';
    public $tabs = [];

    /** ========================= Estudiantes ========================= */
    public $search = '';
    public $selectedStudent;
    public $showModal = false;
    public $editMode = false;
    public $studentData = [];

    /** ========================= Rechazo ========================= */
    public $rejectionReason = '';
    public $showRejectModal = false;

    /** ========================= Documentos Base ========================= */
    public $documentId = null;
    public $documentName;
    public $documentDeadline;
    public $documentFile;
    public $documentExample;
    public $maxSize = 10240;
    public $editingDocumentId = null;
    public $previewPath = null;
    public $previewName = null;

    /** ========================= Revisión de Documentos - NUEVAS PROPIEDADES ========================= */
    public $searchRevision = '';
    public $careerFilter = null;
    public $statusFilter = null; // NUEVO: Filtro por estado
    public $expandedStudent = null; // NUEVO: Para expandir/contraer filas
    
    // Modal de revisión rápida (reemplaza los 3 modales anteriores)
    public $quickReviewDoc = null;
    public $quickReviewComments = '';
    public $quickReviewPreviewUrl = null;
    public $nextPendingDoc = null;
    public $previousPendingDoc = null;
    
    // Propiedades para edición inline
    public $editingComments = [];
    public $editingDates = [];


    public $searchDocuments = '';


    public $statusFilterStudents = null;


    public function updatedSearchDocuments()
    {
        $this->resetPage('paginatedFiles'); // resetea la paginación de archivos cuando busque
    }


    public $showQuickReviewModal = false;


    protected $updatesQueryString = ['search', 'searchRevision'];

    public function mount($id)
    {
        $this->periodId = $id;

        $this->tabs = [
            'estudiantes' => [
                'label' => 'Gestión de Estudiantes',
                'icon'  => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
            ],
            'documentos' => [
                'label' => 'Documentos Base',
                'icon'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            ],
            'revision' => [
                'label' => 'Revisión de Documentos',
                'icon'  => 'M12 8v4m0 4h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h7l7 7v9a2 2 0 01-2 2z',
            ],
        ];

        $this->loadPeriod();
    }

    public function loadPeriod()
    {
        $this->period = Period::with(['semesters', 'files'])->findOrFail($this->periodId);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function goBack()
    {
        return redirect()->route('dashboard');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchRevision()
    {
        $this->resetPage('revisionPage');
    }












    /** ========================= NUEVOS MÉTODOS - Estadísticas ========================= */
    
    public function getStatsProperty()
    {
        $baseQuery = Document::whereHas('student', function($q) {
            $q->where('period_id', $this->periodId);
        })->whereNotNull('student_file_path');

        return [
            'pending' => (clone $baseQuery)->where(function($q) {
                $q->where('status', 'en_revision')
                  ->orWhereNull('status');
            })->count(),
            'approved' => (clone $baseQuery)->where('status', 'revisado')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rechazado')->count(),
            'total' => (clone $baseQuery)->count(),
        ];
    }

    /** ========================= NUEVOS MÉTODOS - Expandir/Contraer ========================= */
    
    public function toggleStudentExpand($studentId)
    {
        $this->expandedStudent = $this->expandedStudent === $studentId ? null : $studentId;
    }

    /** ========================= NUEVOS MÉTODOS - Revisión Rápida ========================= */
    
    public function quickReviewDocument($docId)
    {
         // 🔥 limpiar errores y validaciones previas
        $this->resetValidation();
        $this->resetErrorBag();

        $this->quickReviewDoc = Document::with(['student.career', 'file'])->find($docId);
        
        if (!$this->quickReviewDoc || !$this->quickReviewDoc->student_file_path) {
            session()->flash('error', 'El documento no está disponible para revisión');
            return;
        }

        $this->quickReviewComments = $this->quickReviewDoc->comments ?? '';
        $this->quickReviewPreviewUrl = Storage::url($this->quickReviewDoc->student_file_path);

        // ✅ Inicializar fecha para el input
        $this->editingDates[$docId] = $this->quickReviewDoc->custom_limit_date
            ? \Carbon\Carbon::parse($this->quickReviewDoc->custom_limit_date)->format('Y-m-d')
            : ($this->quickReviewDoc->file?->limit_date
                ? \Carbon\Carbon::parse($this->quickReviewDoc->file->limit_date)->format('Y-m-d')
                : null);
        
        // Encontrar documentos siguiente y anterior pendientes
        $this->findNavigationDocs();

        $this->showQuickReviewModal = true;
    }

    protected function findNavigationDocs()
    {
        if (!$this->quickReviewDoc) return;

        $currentStudentId = $this->quickReviewDoc->student_id;
        $currentDocId     = $this->quickReviewDoc->id;

        // Todos los documentos entregados del estudiante
        $studentDocs = Document::where('student_id', $currentStudentId)
            ->whereNotNull('student_file_path')
            ->whereHas('file', function($q) {
                $q->where('period_id', $this->periodId);
            })
            ->orderBy('id')
            ->get();

        $currentIndex = $studentDocs->search(fn($doc) => $doc->id === $currentDocId);

        // Siguiente documento
        $this->nextPendingDoc = $studentDocs[$currentIndex + 1] ?? null;

        // Documento anterior
        $this->previousPendingDoc = $studentDocs[$currentIndex - 1] ?? null;
    }


    public function navigateToNextDoc()
    {
        if ($this->nextPendingDoc) {
            $this->quickReviewDocument($this->nextPendingDoc->id);
            return true;
        }
        return false;
    }

    public function navigateToPreviousDoc()
    {
        if ($this->previousPendingDoc) {
            $this->quickReviewDocument($this->previousPendingDoc->id);
            return true;
        }
        return false;
    }

    public function quickApproveDocument()
    {
        if (!$this->quickReviewDoc) return;

        $this->quickReviewDoc->update([
            'status' => 'revisado',
            'comments' => $this->quickReviewComments,
            'reviewed_at' => now(),
        ]);

        session()->flash('message', 'Documento aprobado correctamente');
        
        // Intentar navegar al siguiente, si no hay, cerrar modal
        if (!$this->navigateToNextDoc()) {
            $this->closeQuickReview();
        }
    }

    public function quickRejectDocument()
    {
        if (!$this->quickReviewDoc) return;

        $this->validate([
            'quickReviewComments' => 'required|min:3',
        ], [
            'quickReviewComments.required' => 'Debes agregar un comentario para rechazar el documento.',
            'quickReviewComments.min' => 'El comentario es muy corto.',
        ]);

        $this->quickReviewDoc->update([
            'status' => 'rechazado',
            'comments' => $this->quickReviewComments,
            'reviewed_at' => now(),
        ]);

        session()->flash('message', 'Documento rechazado correctamente');

        if (!$this->navigateToNextDoc()) {
            $this->closeQuickReview();
        }
    }


    public function closeQuickReview()
    {
         // 🔥 limpiar errores y validaciones previas
        $this->resetValidation();
        $this->resetErrorBag();

        $this->quickReviewDoc = null;
        $this->quickReviewComments = '';
        $this->quickReviewPreviewUrl = null;
        $this->nextPendingDoc = null;
        $this->previousPendingDoc = null;

        $this->showQuickReviewModal = false; 
    }

    /** ========================= MÉTODOS ANTERIORES - Mantener para compatibilidad ========================= */
    
    public function updateDocumentDate($documentId)
    {
        $doc = Document::with('file')->findOrFail($documentId);
        
        $generalDate = $doc->file?->limit_date ? \Carbon\Carbon::parse($doc->file->limit_date) : null;
        $customDate = $this->editingDates[$documentId] 
            ? \Carbon\Carbon::parse($this->editingDates[$documentId]) 
            : null;

        if ($generalDate && $customDate && $customDate->lessThanOrEqualTo($generalDate)) {
            $customDate = null;
        }

        $doc->update(['custom_limit_date' => $customDate]);
        session()->flash('message', "Fecha actualizada correctamente");
    }

    public function exportStudentPDF($studentId)
    {
        $student = Student::with(['career', 'period', 'documents.file'])->findOrFail($studentId);
        
        $documents = $student->documents->filter(function($doc) use ($student) {
            return $doc->file && $doc->file->period_id == $student->period_id;
        });

        $pdf = Pdf::loadView('pdf.student-documents', [
            'student' => $student,
            'documents' => $documents,
        ])->setPaper('a4');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "Seguimiento_{$student->control_number}.pdf"
        );
    }

    public function exportExcel()
    {
        $date = now()->format('Y-m-d');
        $fileName = "estudiantes_periodo_{$this->period->name}_{$date}.xlsx";
        return Excel::download(new StudentsExport($this->careerFilter, $this->periodId, $this->searchRevision), $fileName);
    }

    /** ========================= Estudiantes (código existente) ========================= */
    
    public function viewDetails($studentId)
    {
        $this->selectedStudent = Student::with(['campus', 'career', 'semester'])
            ->where('period_id', $this->periodId)
            ->findOrFail($studentId);

        $this->editMode  = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'editMode', 'selectedStudent', 'studentData']);
    }

    public function editStudent($studentId)
    {
        $student = Student::where('period_id', $this->periodId)->findOrFail($studentId);
        $this->selectedStudent = $student;
        $this->studentData     = $student->toArray();
        $this->editMode        = true;
        $this->showModal       = true;
    }

    public function cancelEdit()
    {
        $this->editMode    = false;
        $this->studentData = $this->selectedStudent->toArray();
    }

    public function updateStudent()
    {
        if (!$this->selectedStudent) return;

        $this->validate([
            'studentData.name'                => 'required|string|max:255',
            'studentData.last_name_paterno'   => 'required|string|max:255',
            'studentData.last_name_materno'   => 'required|string|max:255',
            'studentData.curp'                => 'required|string|max:18',
            'studentData.phone'               => 'required|string|max:10',
            'studentData.personal_email'      => 'required|email',
            'studentData.institutional_email' => ['required', 'email', 'regex:/@itsco\.edu\.mx$/i'],
            'studentData.control_number'      => 'required|string|max:10',
            'studentData.system'              => 'required|string',
            'studentData.semester_id'         => 'required|exists:semesters,id',
            'studentData.campus_id'           => 'required|exists:campuses,id',
            'studentData.career_id'           => 'required|exists:careers,id',
            'studentData.reticular_progress'  => 'required|numeric|min:0|max:100',
        ], [
            'studentData.name.required'                => 'El nombre es obligatorio',
            'studentData.name.max'                     => 'El nombre no puede exceder 255 caracteres',
            'studentData.last_name_paterno.required'  => 'El apellido paterno es obligatorio',
            'studentData.last_name_paterno.max'       => 'El apellido paterno no puede exceder 255 caracteres',
            'studentData.last_name_materno.required'  => 'El apellido materno es obligatorio',
            'studentData.last_name_materno.max'       => 'El apellido materno no puede exceder 255 caracteres',
            'studentData.curp.required'               => 'La CURP es obligatoria',
            'studentData.curp.max'                    => 'La CURP no puede exceder 18 caracteres',
            'studentData.phone.required'              => 'El teléfono es obligatorio',
            'studentData.phone.max'                   => 'El teléfono no puede exceder 10 caracteres',
            'studentData.personal_email.required'     => 'El correo personal es obligatorio',
            'studentData.personal_email.email'        => 'El correo personal debe ser válido',
            'studentData.institutional_email.required'=> 'El correo institucional es obligatorio',
            'studentData.institutional_email.email'   => 'El correo institucional debe ser válido',
            'studentData.institutional_email.regex'   => 'El correo institucional debe terminar con @itsco.edu.mx',
            'studentData.control_number.required'     => 'El número de control es obligatorio',
            'studentData.control_number.max'          => 'El número de control no puede exceder 10 caracteres',
            'studentData.system.required'             => 'El sistema es obligatorio',
            'studentData.semester_id.required'        => 'El semestre es obligatorio',
            'studentData.semester_id.exists'          => 'El semestre seleccionado no es válido',
            'studentData.campus_id.required'          => 'El campus es obligatorio',
            'studentData.campus_id.exists'            => 'El campus seleccionado no es válido',
            'studentData.career_id.required'          => 'La carrera es obligatoria',
            'studentData.career_id.exists'            => 'La carrera seleccionada no es válida',
            'studentData.reticular_progress.required' => 'El avance reticular es obligatorio',
            'studentData.reticular_progress.numeric'  => 'El avance reticular debe ser un número',
            'studentData.reticular_progress.min'      => 'El avance reticular no puede ser menor a 0',
            'studentData.reticular_progress.max'      => 'El avance reticular no puede ser mayor a 100',
        ]);


        $this->selectedStudent->update($this->studentData);
        session()->flash('message', "Información actualizada correctamente");
        $this->closeModal();
    }

    public function approve($studentId)
    {
        $student = Student::where('period_id', $this->periodId)->find($studentId);
        if ($student) {
            $student->update(['status' => 'aprobado']);
            session()->flash('message', "Perfil aprobado");
        }

        $this->closeModal();
    }

    public function reject($studentId)
    {
        $this->selectedStudent = Student::where('period_id', $this->periodId)->findOrFail($studentId);
        $this->rejectionReason = $this->selectedStudent->rejection_reason ?? '';
        $this->showRejectModal = true;
    }

    public function confirmReject()
    {
        $this->validate([
        'rejectionReason' => 'required|min:3', // obligatorio y al menos 3 caracteres
        ], [
            'rejectionReason.required' => 'Debes agregar un motivo para rechazar el documento.',
            'rejectionReason.min' => 'El motivo debe tener al menos 3 caracteres.',
        ]);

        if (!$this->selectedStudent) return;

        $this->selectedStudent->update([
            'status'           => 'rechazado',
            'rejection_reason' => $this->rejectionReason,
        ]);

        session()->flash('info', "Perfil rechazado");
        $this->reset(['showRejectModal', 'selectedStudent', 'rejectionReason']);
    }

    /** ========================= Documentos Base (código existente) ========================= */
    
    public function createDocument()
    {
        $this->validate(
            [
                'documentName'     => ['required', 'string', Rule::unique('files', 'name')->where(fn ($q) => $q->where('period_id', $this->periodId))],
                'documentDeadline' => 'required|date',
                'documentFile'     => 'required|file|mimes:doc,docx',
                'documentExample'  => 'nullable|file|mimes:pdf',
                'maxSize'          => 'required|integer|min:1',
            ],
            [
                'documentName.required'     => 'Debes escribir el nombre del documento',
                'documentName.unique'       => 'Ya existe un documento con este nombre en el periodo',

                'documentDeadline.required' => 'Debes seleccionar una fecha límite',

                'documentFile.required'     => 'Debes subir el archivo del documento base',
                'documentFile.mimes'        => 'El archivo debe ser Word',

                'maxSize.required'          => 'Debes indicar el tamaño máximo permitido para los estudiantes',
                'maxSize.min' => 'El tamaño máximo debe ser mayor a 0',
            ]
        );

        $filePath = $this->documentFile->store('files', 'public');
        $examplePath = $this->documentExample ? $this->documentExample->store('files/examples', 'public') : null;

        File::create([
            'period_id'         => $this->periodId,
            'name'              => $this->documentName,
            'limit_date'        => $this->documentDeadline,
            'file_path'         => $filePath,
            'name_file'         => $this->documentFile->getClientOriginalName(),
            'example_path'      => $examplePath,
            'example_name_file' => $this->documentExample?->getClientOriginalName(),
            'max_size'          => $this->maxSize,
        ]);

        $this->reset(['documentName', 'documentDeadline', 'documentFile', 'documentExample', 'maxSize']);
        $this->loadPeriod();
    }

    public function editDocument($id)
    {
        $file = File::findOrFail($id);
        $this->documentId       = $file->id;
        $this->documentName     = $file->name;
        $this->documentDeadline = $file->limit_date;
        $this->maxSize          = $file->max_size;
        $this->documentFile    = null;
        $this->documentExample = null;
        $this->editingDocumentId = $id;
    }

    public function cancelEditDocument()
    {
        $this->reset(['documentId', 'documentName', 'documentDeadline', 'documentFile', 'documentExample', 'maxSize', 'editingDocumentId']);
    }

    public function saveDocument()
    {
        if ($this->documentId) {
            $this->validate([
                'documentName' => ['required', Rule::unique('files', 'name')->where(fn ($q) => $q->where('period_id', $this->periodId))->ignore($this->documentId)],
                'documentDeadline' => 'nullable|date',
                'documentFile'     => 'nullable|file|max:' . $this->maxSize . '|mimes:pdf,doc,docx',
                'documentExample'  => 'nullable|file|max:' . $this->maxSize . '|mimes:pdf',
            ]);

            $file = File::findOrFail($this->documentId);

            if ($this->documentFile) {
                if ($file->file_path) Storage::disk('public')->delete($file->file_path);
                $file->file_path = $this->documentFile->store('files', 'public');
                $file->name_file = $this->documentFile->getClientOriginalName();
            }

            if ($this->documentExample) {
                if ($file->example_path) Storage::disk('public')->delete($file->example_path);
                $file->example_path = $this->documentExample->store('files/examples', 'public');
                $file->example_name_file = $this->documentExample->getClientOriginalName();
            }

            $file->name       = $this->documentName;
            $file->limit_date = $this->documentDeadline;
            $file->max_size   = $this->maxSize;
            $file->save();

            session()->flash('message', "Documento actualizado correctamente");
        } else {
            $this->createDocument();
            return;
        }

        $this->cancelEditDocument();
        $this->loadPeriod();
    }

    public function deleteDocument($id)
    {
        $file = File::findOrFail($id);
        if ($file->file_path) Storage::disk('public')->delete($file->file_path);
        if ($file->example_path) Storage::disk('public')->delete($file->example_path);
        $file->delete();
        $this->loadPeriod();
    }

    public function previewFile($path, $name)
    {
        $this->previewPath = $path;
        $this->previewName = $name;
    }






    /** ========================= Render ========================= */
    
    public function render()
    {
        $students = Student::with(['campus', 'career', 'semester'])
        ->where('period_id', $this->periodId)
        ->when($this->statusFilterStudents, function($q) {
            switch($this->statusFilterStudents) {
                case 'pending':
                    $q->where('status', 'pendiente'); // Pendientes
                    break;
                case 'approved':
                    $q->where('status', 'aprobado');
                    break;
                case 'rejected':
                    $q->where('status', 'rechazado');
                    break;
            }
        })
        ->where(function ($q) {
            $q->where('name', 'like', "%{$this->search}%")
            ->orWhere('last_name_paterno', 'like', "%{$this->search}%")
            ->orWhere('last_name_materno', 'like', "%{$this->search}%")
            ->orWhere('control_number', 'like', "%{$this->search}%");
        })
        ->orderBy('name')
        ->paginate(1); // ajusta la paginación



        // Aquí agregamos el filtro por búsqueda de documentos
        $paginatedFiles = $this->period->files()
            ->when($this->searchDocuments, fn($q) => 
                $q->where('name', 'like', '%' . $this->searchDocuments . '%')
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'filesPage'); // usamos nombre de página custom para evitar conflicto con otros paginadores




        

        $students->getCollection()->transform(function ($student) {
            match ($student->status) {
                'aprobado' => (
                    $student->status_label = 'Aprobado'
                ) && (
                    $student->status_style = 'background-color: var(--status-icon-bg-approved); color: var(--status-icon-color-approved); padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-weight: 500;'
                ),
                'rechazado' => (
                    $student->status_label = 'Rechazado'
                ) && (
                    $student->status_style = 'background-color: var(--status-icon-bg-rejected); color: var(--status-icon-color-rejected); padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-weight: 500;'
                ),
                default => (
                    $student->status_label = 'Pendiente'
                ) && (
                    $student->status_style = 'background-color: var(--status-icon-bg-pending); color: var(--status-icon-color-pending); padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-weight: 500;'
                ),
            };
            return $student;
        });


        // MEJORADO: Para revisión de documentos con estadísticas
        $studentsRevision = Student::with(['career', 'documents' => function($q) {
        $q->whereHas('file', function($query) {
            $query->where('period_id', $this->periodId);
        });
    }])
    ->where('period_id', $this->periodId)
    ->where('status', 'aprobado') // <- FILTRO POR ESTUDIANTES APROBADOS
    ->when($this->careerFilter, fn($q) => $q->where('career_id', $this->careerFilter))
    ->when($this->statusFilter, function($q) {
        switch($this->statusFilter) {
            case 'pending':
                $q->whereHas('documents', function($query) {
                    $query->whereNotNull('student_file_path')
                          ->where(function($q) {
                              $q->where('status', 'en_revision')
                                ->orWhereNull('status');
                          });
                });
                break;
            case 'approved':
                $q->whereHas('documents', function($query) {
                    $query->where('status', 'revisado');
                });
                break;
            case 'rejected':
                $q->whereHas('documents', function($query) {
                    $query->where('status', 'rechazado');
                });
                break;
        }
    })
    ->when($this->searchRevision, function($q) {
        $q->where(function($query) {
            $query->where('name', 'like', "%{$this->searchRevision}%")
                  ->orWhere('last_name_paterno', 'like', "%{$this->searchRevision}%")
                  ->orWhere('last_name_materno', 'like', "%{$this->searchRevision}%")
                  ->orWhere('control_number', 'like', "%{$this->searchRevision}%");
        });
    })
    ->orderBy('name')
    ->paginate(20, ['*'], 'revisionPage');


        // MEJORADO: Agregar contadores por estudiante
        $studentsRevision->getCollection()->transform(function($student){
            $periodDocs = $student->documents->filter(function($doc) {
                return $doc->file && $doc->file->period_id == $this->periodId;
            });
            
            $student->delivered = $periodDocs->whereNotNull('student_file_path')->count();
            $student->total = File::where('period_id', $this->periodId)->count();
            $student->approved_count = $periodDocs->where('status', 'revisado')->count();
            $student->pending_count = $periodDocs->filter(function($doc) {
                return $doc->student_file_path && ($doc->status === 'en_revision' || is_null($doc->status));
            })->count();
            $student->rejected_count = $periodDocs->where('status', 'rechazado')->count();
            
            return $student;
        });

        return view('livewire.dashboard.period-detail', [
            'students'  => $students,
            'paginatedFiles' => $paginatedFiles,
            'campuses'  => Campus::all(),
            'careers'   => Career::all(),
            'semesters' => $this->period->semesters,
            'studentsRevision' => $studentsRevision,
        ]);
    }
}