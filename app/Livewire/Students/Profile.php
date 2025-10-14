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

    public function mount()
    {
        $this->student = Student::firstOrNew(['user_id' => Auth::id()]);
        if ($this->student->exists) {
            $this->fill($this->student->toArray());
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'last_name_paterno' => 'required|string|max:255',
            'last_name_materno' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'curp' => 'required|string|max:18',
            'rfc' => 'nullable|string|max:13',
            'control_number' => 'required|string|max:10',
            'institutional_email' => ['required','email','regex:/@itsco\.edu\.mx$/i'],
            'personal_email' => 'required|email',
            'phone' => 'required|string|max:10',
            'semester_id' => 'required|exists:semesters,id',
            'system' => 'required|string',
            'campus_id' => 'required|exists:campuses,id',
            'career_id' => 'required|exists:careers,id',
            'period_id' => 'required|exists:periods,id',
            'reticular_progress' => 'required|numeric|min:0|max:100',
        ]);

        $validated['user_id'] = Auth::id();

        // Convertir correos a minúsculas antes de guardar
        $validated['institutional_email'] = strtolower($validated['institutional_email']);
        $validated['personal_email'] = strtolower($validated['personal_email']);

        $this->student->updateOrCreate(['user_id' => Auth::id()], $validated);

        session()->flash('message', 'Perfil actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.students.profile', [
            'campuses' => Campus::all(),
            'careers' => Career::all(),
            'periods' => Period::all(),
            'semesters' => Semester::where('is_active', true)->get(),
        ]);
    }
}
