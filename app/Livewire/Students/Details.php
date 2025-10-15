<?php

namespace App\Livewire\Students;

use Livewire\Component;
use App\Models\Student;
use App\Models\Document;
use Carbon\Carbon;

class Details extends Component
{
    public $student;
    public $documents = [];
    public $editingComments = [];
    public $editingDates = [];

    // Modal
    public $isModalOpen = false;
    public $selectedDocument = null;
    public $previewUrl = null;
    public $previewName = null;
    public $previewExtension = null;

    public function mount($studentId)
    {
        $this->student = Student::with(['career', 'period', 'documents.file'])
            ->findOrFail($studentId);

        // Filtrar documentos por periodo del estudiante si el archivo tiene period_id
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

    public function updateStatus($documentId, $status)
    {
        $doc = Document::findOrFail($documentId);

        $doc->update([
            'status' => $status,
            'comments' => $this->editingComments[$documentId] ?? $doc->comments,
        ]);

        // Reaplicar filtro por periodo
        $this->documents = $this->student->documents->filter(function($doc) {
            return $doc->file && $doc->file->period_id == $this->student->period_id;
        });

        session()->flash('message', "Estatus del documento '{$doc->file->name}' actualizado a '{$status}'");
    }

    public function render()
    {
        return view('livewire.students.details');
    }
}
