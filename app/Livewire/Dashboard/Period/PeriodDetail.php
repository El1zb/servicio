<?php

namespace App\Livewire\Dashboard\Period;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

use App\Livewire\Dashboard\Period\Concerns\ManagesStudents;
use App\Livewire\Dashboard\Period\Concerns\ManagesDocuments;
use App\Livewire\Dashboard\Period\Concerns\ManagesRevision;

use App\Models\Period;
use App\Models\Campus;
use App\Models\Career;

class PeriodDetail extends Component
{
    use WithPagination, WithFileUploads;
    use ManagesStudents;
    use ManagesDocuments;
    use ManagesRevision;

    // ========================= Tabs / Periodo =========================

    public $periodId;
    public $period;
    public $activeTab = 'estudiantes';
    public $tabs = [];

    public $start_date;
    public $end_date;

    protected $queryString = [
        'activeTab' => ['except' => 'estudiantes'],
    ];

    // ========================= Boot / Mount =========================

    public function mount($id): void
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

    public function loadPeriod(): void
    {
        $this->period     = Period::with(['semesters', 'files'])->findOrFail($this->periodId);
        $this->start_date = $this->period->start_date;
        $this->end_date   = $this->period->end_date;
    }

    // ========================= Navegación =========================

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function goBack()
    {
        return redirect()->route('dashboard');
    }

    // ========================= Render =========================

    public function render()
    {
        return view('livewire.dashboard.period.period-detail', [
            'students'         => $this->getStudentsPaginated(),
            'paginatedFiles'   => $this->getFilesPaginated(),
            'studentsRevision' => $this->getStudentsRevisionPaginated(),
            'campuses'         => Campus::all(),
            'careers'          => Career::all(),
            'semesters'        => $this->period->semesters,
        ]);
    }
}