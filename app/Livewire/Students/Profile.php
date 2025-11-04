<?php

namespace App\Livewire\Students;

use Livewire\Component;
use App\Models\Student;
use App\Models\Campus;
use App\Models\Career;
use App\Models\Period;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    public $student;
    public $last_name_paterno, $last_name_materno, $name, $curp, $rfc, $control_number;
    public $institutional_email, $personal_email, $phone, $semester_id, $system;
    public $campus_id, $career_id, $period_id;
    public $reticular_progress;
    public $systems = ['Escolarizado', 'Sabatino'];
    public $showForm = true;

    public function mount()
    {
        $this->student = Student::firstOrNew(['user_id' => Auth::id()]);

        // Llenar datos si ya existe
        if ($this->student->exists) {
            $this->fill($this->student->toArray());
        }

        // Asignar último periodo si no tiene
        if (!$this->student->period_id) {
            $lastPeriod = Period::orderBy('id', 'desc')->first();
            if ($lastPeriod) $this->period_id = $lastPeriod->id;
        }

        // Mostrar/ocultar formulario según el estado actual
        $this->updateFormVisibility();
    }

    /**
     * Muestra u oculta el formulario según el estado del estudiante.
     */
    private function updateFormVisibility()
    {
        switch ($this->student->status) {
            case 'pendiente':
                $this->showForm = false;
                break;

            case 'aprobado':
                $this->showForm = false;
                break;

            case 'rechazado':
                $this->showForm = false;
                break;

            default:
                $this->showForm = true;
                break;
        }
    }

    /**
     * Guarda o actualiza la información del perfil del estudiante.
     */
    public function save()
    {
        $validated = $this->validate([
            'last_name_paterno' => 'required|string|max:255',
            'last_name_materno' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'curp' => 'required|string|max:18',
            'rfc' => 'nullable|string|max:13',
            'phone' => 'required|string|max:10',
            'personal_email' => 'required|email',
        ]);

        // Si puede editar datos académicos (nuevo o rechazado)
        if ($this->canEditAcademic()) {
            $academic = $this->validate([
                'control_number' => 'required|string|max:10',
                'institutional_email' => ['required', 'email', 'regex:/@itsco\.edu\.mx$/i'],
                'semester_id' => 'required|exists:semesters,id',
                'system' => 'required|string',
                'campus_id' => 'required|exists:campuses,id',
                'career_id' => 'required|exists:careers,id',
                'period_id' => 'required|exists:periods,id',
                'reticular_progress' => 'required|numeric|min:0|max:100',
            ]);

            $validated = array_merge($validated, $academic);
        }

        $validated['user_id'] = Auth::id();
        $validated['personal_email'] = strtolower($validated['personal_email']);

        // Si es nuevo o estaba rechazado → se vuelve a enviar para revisión
        if (!$this->student->exists || $this->student->status === 'rechazado') {
            $validated['status'] = 'pendiente';
            //session()->flash('info', 'Tu información fue enviada para validación.');
            $this->showForm = false;
        }

        // Guardar o actualizar registro
        $this->student->updateOrCreate(['user_id' => Auth::id()], $validated);

        // Refrescar datos y estado
        $this->student = Student::where('user_id', Auth::id())->first();
        $this->updateFormVisibility();

        if ($this->student->status !== 'pendiente') {
            session()->flash('message', 'Perfil actualizado correctamente.');
        }
    }

    public function render()
    {
        $lastPeriod = Period::orderBy('id', 'desc')->first();

        if ($this->student->exists && $this->student->period_id) {
            $periods = Period::where('id', $this->student->period_id)->get();
        } else {
            $periods = $lastPeriod ? collect([$lastPeriod]) : collect([]);
        }

        return view('livewire.students.profile', [
            'campuses' => Campus::all(),
            'careers' => Career::all(),
            'periods' => $periods,
            'semesters' => Semester::where('is_active', true)->get(),
        ]);
    }

    public function canEditAcademic(): bool
    {
        return in_array($this->student->status, [null, 'rechazado']);
    }

    public function canEditPersonal(): bool
    {
        return true;
    }
}