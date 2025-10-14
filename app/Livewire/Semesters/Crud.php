<?php

namespace App\Livewire\Semesters;

use Livewire\Component;
use App\Models\Semester;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Crud extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $semesterId, $is_active = true;
    public $isOpen = false;

    /**
     * Reglas de validación dinámicas
     */
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:1',
                Rule::unique('semesters', 'name')->ignore($this->semesterId),
            ],
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $semesters = Semester::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.semesters.crud', [
            'semesters' => $semesters
        ]);
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit(Semester $semester)
    {
        $this->semesterId = $semester->id;
        $this->name = $semester->name;
        $this->is_active = $semester->is_active;
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate();

        Semester::updateOrCreate(
            ['id' => $this->semesterId],
            [
                'name' => $this->name,
                'is_active' => $this->is_active,
            ]
        );

        session()->flash('message', 
            $this->semesterId ? 'Semestre actualizado correctamente.' : 'Semestre creado correctamente.'
        );

        $this->closeModal();
        $this->resetInput();
    }

    public function delete(Semester $semester)
    {
        $semester->delete();
        session()->flash('message', 'Semestre eliminado correctamente.');
    }

    private function resetInput()
    {
        $this->semesterId = null;
        $this->name = '';
        $this->is_active = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function toggleActive($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->is_active = !$semester->is_active;
        $semester->save();

        session()->flash('message', 'Estado actualizado correctamente.');
    }

}
