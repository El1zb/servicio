<?php

namespace App\Livewire\Students;

use Livewire\Component;
use App\Models\Student;
use App\Models\Document;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class Details extends Component
{
    public $student;
    public $documents = [];
    public $editingComments = [];
    public $editingDates = [];

    // Modal PDF
    public $isModalOpen = false;
    public $selectedDocument = null;
    public $previewUrl = null;
    public $previewName = null;
    public $previewExtension = null;

    // Modal Rechazo
    public $isRejectModalOpen = false;
    public $rejectingDocument = null;

    public function mount($studentId)
    {
        $this->student = Student::with(['career', 'period', 'documents.file'])
            ->findOrFail($studentId);

        $this->documents = $this->student->documents->filter(function($doc) {
            return $doc->file && $doc->file->period_id == $this->student->period_id;
        });

        foreach ($this->documents as $doc) {
            $this->editingComments[$doc->id] = $doc->comments;

            $generalDate = $doc->file?->limit_date ? Carbon::parse($doc->file->limit_date) : null;
            $customDate = $doc->custom_limit_date ? Carbon::parse($doc->custom_limit_date) : null;

            if ($generalDate && $customDate && $customDate->greaterThan($generalDate)) {
                $this->editingDates[$doc->id] = $customDate->format('Y-m-d');
            } else {
                $this->editingDates[$doc->id] = $generalDate ? $generalDate->format('Y-m-d') : null;
            }
        }
    }

    public function updateDate($documentId)
    {
        $doc = Document::with('file')->findOrFail($documentId);

        $generalDate = $doc->file?->limit_date ? Carbon::parse($doc->file->limit_date) : null;
        $customDate = $this->editingDates[$documentId] 
            ? Carbon::parse($this->editingDates[$documentId]) 
            : null;

        if ($generalDate && $customDate && $customDate->lessThanOrEqualTo($generalDate)) {
            $customDate = null;
        }

        $doc->update([
            'custom_limit_date' => $customDate,
        ]);

        $fecha = $customDate ? $customDate->format('d/m/Y') : 'sin fecha personalizada';
        session()->flash('message', "Fecha de entrega del documento '{$doc->file->name}' actualizada: {$fecha}");
    }

    public function openModal($documentId)
    {
        $this->selectedDocument = Document::findOrFail($documentId);

        if ($this->selectedDocument->student_file_path) {
            $this->previewUrl = \Storage::url($this->selectedDocument->student_file_path);
            $this->previewName = $this->selectedDocument->student_file_name;
            $this->previewExtension = strtolower(pathinfo($this->selectedDocument->student_file_path, PATHINFO_EXTENSION));
        } else {
            $this->previewUrl = null;
            $this->previewName = null;
            $this->previewExtension = null;
        }

        $this->isModalOpen = true;
    }

    public function saveComments()
    {
        if ($this->selectedDocument) {
            $this->selectedDocument->update([
                'comments' => $this->editingComments[$this->selectedDocument->id] ?? $this->selectedDocument->comments,
            ]);

            session()->flash('message', "Comentarios del documento '{$this->selectedDocument->file->name}' guardados correctamente");
            $this->isModalOpen = false;
        }
    }

    public function openRejectModal($documentId)
    {
        $this->rejectingDocument = Document::findOrFail($documentId);
        $this->editingComments[$documentId] = $this->rejectingDocument->comments ?? '';
        $this->isRejectModalOpen = true;
    }

    public function saveRejection()
    {
        if (!$this->rejectingDocument) return;

        $doc = $this->rejectingDocument;

        $doc->update([
            'status' => 'rechazado',
            'comments' => $this->editingComments[$doc->id] ?? $doc->comments,
        ]);

        $this->isRejectModalOpen = false;
        $this->rejectingDocument = null;

        $this->documents = $this->student->documents->filter(function($doc) {
            return $doc->file && $doc->file->period_id == $this->student->period_id;
        });

        session()->flash('message', "Documento '{$doc->file->name}' marcado como rechazado con comentarios.");
    }

    public function updateStatus($documentId, $status)
    {
        $doc = Document::findOrFail($documentId);

        $doc->update([
            'status' => $status,
            'comments' => $this->editingComments[$documentId] ?? $doc->comments,
        ]);

        $this->documents = $this->student->documents->filter(function($doc) {
            return $doc->file && $doc->file->period_id == $this->student->period_id;
        });

        session()->flash('message', "Estatus del documento '{$doc->file->name}' actualizado a '{$status}'");
    }

    public function render()
    {
        return view('livewire.students.details');
    }

    public function exportPDF()
    {
        $student = $this->student;
        $documents = $this->documents;

        $pdf = Pdf::loadView('pdf.student-documents', [
            'student' => $student,
            'documents' => $documents,
        ])->setPaper('a4');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "Seguimiento_{$student->control_number}.pdf"
        );
    }
}
