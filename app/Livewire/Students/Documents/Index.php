<?php

namespace App\Livewire\Students\Documents;

use App\Livewire\Students\Documents\Concerns\ManagesCalendar;
use App\Livewire\Students\Documents\Concerns\ManagesDocuments;
use App\Livewire\Students\Documents\Concerns\ManagesModals;
use App\Livewire\Students\Documents\Concerns\ManagesUploads;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;
    use ManagesCalendar;
    use ManagesDocuments;
    use ManagesModals;
    use ManagesUploads;

    public $student;

    public function mount(): void
    {
        $this->student = Auth::user()->student;

        if (! $this->student) {
            $this->dispatch('notify', type: 'error', message: 'No tienes perfil de estudiante.');
            return;
        }

        $this->loadCalendarEvents();

        if ($this->student->status === 'aprobado' && $this->student->period_id) {
            $this->assignPendingDocuments();
        }
    }

    public function render()
    {
        return view('livewire.students.documents.index', $this->getDocumentsData());
    }
}