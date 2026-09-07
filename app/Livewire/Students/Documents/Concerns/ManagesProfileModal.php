<?php

namespace App\Livewire\Students\Documents\Concerns;

use App\Models\Campus;
use App\Models\Period;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

trait ManagesProfileModal
{
    // ─── Propiedades ────────────────────────────────────────────────────────────

    public bool $isProfileModalOpen  = false;
    public int  $profileStep         = 1;
    public int  $profileFormInstance = 0;

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

    public array $systems = ['Escolarizado', 'Sabatino'];

    // ─── Abrir / cerrar modal ────────────────────────────────────────────────────

    public function openProfileModal(): void
    {
        // Un perfil ya aprobado no se vuelve a editar aquí — cualquier
        // corrección pasa por servicio social directamente.
        if (optional($this->student)->status === 'aprobado') return;

        $student = $this->student ?? Student::firstOrNew(['user_id' => Auth::id()]);

        $this->fill([
            'last_name_paterno'   => $student->last_name_paterno ?? '',
            'last_name_materno'   => $student->last_name_materno ?? '',
            'name'                => $student->name ?? '',
            'curp'                => $student->curp ?? '',
            'rfc'                 => $student->rfc,
            'control_number'      => $student->control_number ?? '',
            'institutional_email' => $student->institutional_email ?? '',
            'personal_email'      => $student->personal_email ?? '',
            'phone'               => $student->phone ?? '',
            'semester_id'         => $student->semester_id,
            'system'              => $student->system ?? '',
            'campus_id'           => $student->campus_id,
            'career_id'           => $student->career_id,
            'period_id'           => $student->period_id,
            'reticular_progress'  => $student->reticular_progress,
        ]);

        if (! $this->period_id) {
            $lastPeriod = Period::orderBy('start_date', 'desc')->first();
            if ($lastPeriod) {
                $this->period_id = $lastPeriod->id;
            }
        }

        $this->resetValidation();
        $this->profileStep        = 1;
        $this->profileFormInstance++;
        $this->isProfileModalOpen = true;
    }

    public function closeProfileModal(): void
    {
        $this->isProfileModalOpen = false;
        $this->profileStep        = 1;
    }

    public function updated($propertyName): void
    {
        $this->resetValidation($propertyName);
    }

    // ─── Pasos ──────────────────────────────────────────────────────────────────

    public function profileTotalSteps(): int
    {
        return $this->canEditAcademic() ? 2 : 1;
    }

    public function nextProfileStep(): void
    {
        $this->validate([
            'last_name_paterno' => 'required|string|max:255',
            'last_name_materno' => 'required|string|max:255',
            'name'              => 'required|string|max:255',
            'curp'              => 'required|string|max:18',
            'rfc'               => 'nullable|string|max:13',
            'phone'             => 'required|string|max:10',
            'personal_email'    => 'required|email',
        ]);

        if ($this->profileTotalSteps() > 1) {
            $this->profileStep = 2;
            return;
        }

        $this->saveProfile();
    }

    public function prevProfileStep(): void
    {
        $this->profileStep = 1;
    }

    // ─── Reactividad Campus → Carrera ────────────────────────────────────────────
    // No todos los planteles ofrecen todas las carreras; al cambiar el campus,
    // la carrera elegida deja de ser válida si no pertenece al nuevo plantel.

    public function updatedCampusId(): void
    {
        if (! $this->campus_id) {
            $this->career_id = null;
            return;
        }

        $validCareerIds = Campus::find($this->campus_id)?->careers()->where('is_active', true)->pluck('careers.id')->toArray() ?? [];

        if (! in_array($this->career_id, $validCareerIds)) {
            $this->career_id = null;
        }
    }

    // ─── Permisos de edición ─────────────────────────────────────────────────────

    public function canEditAcademic(): bool
    {
        return in_array(optional($this->student)->status, [null, 'rechazado']);
    }

    // ─── Guardar perfil ──────────────────────────────────────────────────────────

    public function saveProfile(): void
    {
        if (optional($this->student)->status === 'aprobado') return;

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
                    'regex:/@(itsco\.edu\.mx|cosamaloapan\.tecnm\.mx|tecnm\.mx|tecnm\.org\.mx)$/i',
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

        $wasNewOrRejected = ! $this->student || $this->student->status === 'rechazado';

        if ($wasNewOrRejected) {
            $validated['status'] = 'pendiente';
        }

        Student::updateOrCreate(['user_id' => Auth::id()], $validated);

        $this->student = Student::where('user_id', Auth::id())->first();
        $this->closeProfileModal();

        $message = $wasNewOrRejected
            ? 'Perfil enviado. Tu información está en revisión.'
            : 'Perfil actualizado correctamente.';

        $this->dispatch('notify', type: 'success', message: $message);
    }

    // ─── Query de datos para render ──────────────────────────────────────────────

    protected function getProfileModalViewData(): array
    {
        $lastPeriod = Period::where('is_active', true)->orderBy('start_date', 'desc')->first();

        if ($this->student && $this->student->exists && $this->student->period_id) {
            $periods   = Period::where('id', $this->student->period_id)->where('is_active', true)->get();
            $semesters = Period::find($this->student->period_id)
                               ?->semesters()
                               ->where('is_active', true)
                               ->get() ?? collect();
        } else {
            $periods   = $lastPeriod ? collect([$lastPeriod]) : collect([]);
            $semesters = $lastPeriod ? $lastPeriod->semesters()->where('is_active', true)->get() : collect([]);
        }

        $careers = $this->campus_id
            ? Campus::find($this->campus_id)?->careers()->where('is_active', true)->get() ?? collect()
            : collect();

        return [
            'profileCampuses'         => Campus::where('is_active', true)->get(),
            'profileCareers'          => $careers,
            'profilePeriods'          => $periods,
            'profileSemesters'        => $semesters,
            'profileTotalSteps'       => $this->profileTotalSteps(),
            'profileIsNewSubmission'  => ! $this->student || $this->student->status === 'rechazado',
        ];
    }
}
