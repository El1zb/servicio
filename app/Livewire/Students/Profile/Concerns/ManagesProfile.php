<?php

namespace App\Livewire\Students\Profile\Concerns;

use App\Models\Campus;
use App\Models\Career;
use App\Models\Period;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

trait ManagesProfile
{
    // ─── Propiedades ────────────────────────────────────────────────────────────

    public $student;

    public string  $last_name_paterno   = '';
    public string  $last_name_materno   = '';
    public string  $name                = '';
    public string  $curp                = '';
    public ?string $rfc                 = null;
    public string  $control_number      = '';
    public string  $institutional_email = '';
    public string  $personal_email      = '';
    public string  $phone               = '';
    public ?int    $semester_id         = null;
    public string  $system              = '';
    public ?int    $campus_id           = null;
    public ?int    $career_id           = null;
    public ?int    $period_id           = null;
    public ?float  $reticular_progress  = null;
    public bool    $showForm            = true;

    public array $systems = ['Escolarizado', 'Sabatino'];

    // ─── Mount ──────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->student = Student::firstOrNew(['user_id' => Auth::id()]);

        if ($this->student->exists) {
            $this->fill($this->student->toArray());
        }

        // Asignar último periodo activo si no tiene uno asignado
        if (! $this->student->period_id) {
            $lastPeriod = Period::orderBy('start_date', 'desc')->first();
            if ($lastPeriod) {
                $this->period_id = $lastPeriod->id;
            }
        }

        $this->updateFormVisibility();
    }

    // ─── Visibilidad del formulario ──────────────────────────────────────────────

    private function updateFormVisibility(): void
    {
        $this->showForm = ! in_array($this->student->status, [
            'pendiente',
            'aprobado',
            'rechazado',
        ]);
    }

    // ─── Guardar perfil ──────────────────────────────────────────────────────────

    public function save(): void
    {
        $validated = $this->validate([
            'last_name_paterno' => 'required|string|max:255',
            'last_name_materno' => 'required|string|max:255',
            'name'              => 'required|string|max:255',
            'curp'              => 'required|string|max:18',
            'rfc'               => 'nullable|string|max:13',
            'phone'             => 'required|string|max:10',
            'personal_email'    => 'required|email',
        ]);

        if ($this->canEditAcademic()) {
            $academic = $this->validate([
                'control_number'      => 'required|string|max:10',
                'institutional_email' => [
                    'required',
                    'email',
                    'regex:/@(itsco\.edu\.mx|cosamaloapan\.tecnm\.mx|tecnm\.mx|tecnm\.org\.mx)$/i'
                ],
                'semester_id'         => 'required|exists:semesters,id',
                'system'              => 'required|string',
                'campus_id'           => 'required|exists:campuses,id',
                'career_id'           => 'required|exists:careers,id',
                'period_id'           => 'required|exists:periods,id',
                'reticular_progress'  => 'required|numeric|min:0|max:100',
            ]);

            $validated = array_merge($validated, $academic);
        }

        $validated['user_id']        = Auth::id();
        $validated['personal_email'] = strtolower($validated['personal_email']);

        // Nuevo o rechazado → vuelve a revisión
        if (! $this->student->exists || $this->student->status === 'rechazado') {
            $validated['status'] = 'pendiente';
            $this->showForm      = false;
        }

        $this->student->updateOrCreate(['user_id' => Auth::id()], $validated);

        // Refrescar estado
        $this->student = Student::where('user_id', Auth::id())->first();
        $this->updateFormVisibility();

        if ($this->student->status !== 'pendiente') {
            $this->dispatch('notify', type: 'success', message: 'Perfil actualizado correctamente.');
        }
    }

    // ─── Permisos de edición ─────────────────────────────────────────────────────

    public function canEditAcademic(): bool
    {
        return in_array($this->student->status, [null, 'rechazado']);
    }

    public function canEditPersonal(): bool
    {
        return true;
    }

    // ─── Query de datos para render ──────────────────────────────────────────────

    protected function getProfileViewData(): array
    {
        $lastPeriod = Period::where('is_active', true)->orderBy('start_date', 'desc')->first();

        if ($this->student->exists && $this->student->period_id) {
            $periods   = Period::where('id', $this->student->period_id)->where('is_active', true)->get();
            $semesters = Period::find($this->student->period_id)
                               ->semesters()
                               ->where('is_active', true)
                               ->get();
        } else {
            $periods   = $lastPeriod ? collect([$lastPeriod]) : collect([]);
            $semesters = $lastPeriod ? $lastPeriod->semesters()->where('is_active', true)->get() : collect([]);
        }

        return [
            'campuses'  => Campus::all(),
            'careers'   => Career::all(),
            'periods'   => $periods,
            'semesters' => $semesters,
        ];
    }
}