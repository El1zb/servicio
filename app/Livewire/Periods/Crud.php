<?php

namespace App\Livewire\Periods;

use Livewire\Component;
use App\Models\Period;
use Livewire\WithPagination;

use Illuminate\Validation\Rule;

class Crud extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $start_date, $end_date, $periodId;
    public $isOpen = false;

    protected $rules = [
        'name' => 'required|string|min:3|unique:periods,name',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $periods = Period::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.periods.crud', [
            'periods' => $periods
        ]);
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit(Period $period)
    {
        $this->periodId = $period->id;
        $this->name = $period->name;
        $this->start_date = $period->start_date;
        $this->end_date = $period->end_date;
        $this->isOpen = true;

        $this->rules['name'] = 'required|string|min:3|unique:periods,name,' . $this->periodId;
    }

    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('periods', 'name')->ignore($this->periodId),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Period::updateOrCreate(
            ['id' => $this->periodId],
            [
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]
        );

        session()->flash('message', 
            $this->periodId ? 'Periodo actualizado correctamente.' : 'Periodo creado correctamente.'
        );

        $this->closeModal();
        $this->resetInput();
    }

    public function delete(Period $period)
    {
        $period->delete();
        session()->flash('message', 'Periodo eliminado correctamente.');
    }

    private function resetInput()
    {
        $this->periodId = null;
        $this->name = '';
        $this->start_date = '';
        $this->end_date = '';
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }
}
