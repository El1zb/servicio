<?php

namespace App\Livewire\Students\Documents;

use App\Livewire\Students\Documents\Concerns\ManagesDocuments;
use App\Livewire\Students\Documents\Concerns\ManagesDocumentViewer;
use App\Livewire\Students\Documents\Concerns\ManagesProfileModal;
use App\Livewire\Students\Documents\Concerns\ManagesUploads;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;
    use ManagesDocuments;
    use ManagesDocumentViewer;
    use ManagesProfileModal;
    use ManagesUploads;

    public $student;

    public function mount(): void
    {
        $this->student = Auth::user()->student;

        if (! $this->student) {
            return;
        }

        if ($this->student->status === 'aprobado' && $this->student->period_id) {
            $this->assignPendingDocuments();
        }
    }

    public function render()
    {
        return view('livewire.students.documents.index', array_merge(
            $this->getDocumentsData(),
            $this->getProfileModalViewData(),
        ));
    }
}