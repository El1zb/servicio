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

    protected $updatesQueryString = ['search', 'searchRevision'];

    public function mount($id)
    {
        $this->periodId = $id;

        $this->tabs = [
            'estudiantes' => [
                'label' => 'Gestión de Estudiantes',
                'icon'  => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z',
            ],
            'documentos' => [
                'label' => 'Documentos Base',
                'icon'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5',
            ],
            'revision' => [
                'label' => 'Revisión de Documentos',
                'icon'  => 'M9 5H7a2 2 0 00-2 2v12',
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
        $this->quickReviewDoc = Document::with(['student.career', 'file'])->find($docId);
        
        if (!$this->quickReviewDoc || !$this->quickReviewDoc->student_file_path) {
            session()->flash('error', 'El documento no está disponible para revisión');
            return;
        }

        $this->quickReviewComments = $this->quickReviewDoc->comments ?? '';
        $this->quickReviewPreviewUrl = Storage::url($this->quickReviewDoc->student_file_path);
        
        // Encontrar documentos siguiente y anterior pendientes
        $this->findNavigationDocs();
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

        if (empty(trim($this->quickReviewComments))) {
            session()->flash('error', 'Debes agregar un comentario al rechazar el documento');
            return;
        }

        $this->quickReviewDoc->update([
            'status' => 'rechazado',
            'comments' => $this->quickReviewComments,
            'reviewed_at' => now(),
        ]);

        session()->flash('message', 'Documento rechazado correctamente');
        
        // Intentar navegar al siguiente, si no hay, cerrar modal
        if (!$this->navigateToNextDoc()) {
            $this->closeQuickReview();
        }
    }

    public function closeQuickReview()
    {
        $this->quickReviewDoc = null;
        $this->quickReviewComments = '';
        $this->quickReviewPreviewUrl = null;
        $this->nextPendingDoc = null;
        $this->previousPendingDoc = null;
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
    }

    public function reject($studentId)
    {
        $this->selectedStudent = Student::where('period_id', $this->periodId)->findOrFail($studentId);
        $this->rejectionReason = $this->selectedStudent->rejection_reason ?? '';
        $this->showRejectModal = true;
    }

    public function confirmReject()
    {
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
        $this->validate([
            'documentName' => ['required', 'string', Rule::unique('files', 'name')->where(fn ($q) => $q->where('period_id', $this->periodId))],
            'documentDeadline' => 'nullable|date',
            'documentFile'     => 'required|file|max:' . $this->maxSize . '|mimes:pdf,doc,docx',
            'documentExample'  => 'nullable|file|max:' . $this->maxSize . '|mimes:pdf,doc,docx',
            'maxSize'          => 'required|integer|min:1|max:10240',
        ]);

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
            ->where(fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('last_name_paterno', 'like', "%{$this->search}%")
                  ->orWhere('last_name_materno', 'like', "%{$this->search}%")
            )
            ->orderBy('name')
            ->paginate(10);

        $students->getCollection()->transform(function ($student) {
            match ($student->status) {
                'aprobado' => ($student->status_label = 'Aprobado') && ($student->status_class = 'bg-emerald-500/20 text-emerald-300'),
                'rechazado' => ($student->status_label = 'Rechazado') && ($student->status_class = 'bg-red-500/20 text-red-300'),
                default => ($student->status_label = 'Pendiente') && ($student->status_class = 'bg-amber-500/20 text-amber-300'),
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
    ->paginate(10, ['*'], 'revisionPage');


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
            'campuses'  => Campus::all(),
            'careers'   => Career::all(),
            'semesters' => $this->period->semesters,
            'studentsRevision' => $studentsRevision,
        ]);
    }
}