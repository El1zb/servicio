{{-- Datos Académicos --}}
<div class="bg-[var(--color-card-bg)] rounded-xl
             overflow-hidden transition-all duration-300 ">

    {{-- Header --}}
    <div class="px-6 py-4 border-b border-[var(--color-border-hover)]"
         style="background-color: var(--color-card-bg);">
        <h2 class="text-lg font-semibold text-[var(--color-primary-2)]!">
            Datos Académicos
        </h2>
    </div>

    {{-- Body --}}
    <div class="p-6 space-y-6">

        {{-- Identificación académica --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <flux:input
                wire:model="control_number"
                label="Número de Control *"
                type="text" inputmode="text" required
                pattern="[A-Za-z0-9]+" title="Solo letras y números"
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover); text-transform: uppercase;"
                oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase()"
            />
            <flux:input
                wire:model="institutional_email"
                label="Correo Institucional *"
                type="email" inputmode="email"
                placeholder="Ej. l225q0000@itsco.edu.mx" required
                title="Ingresa un correo válido"
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                oninput="this.value = this.value.toLowerCase()"
            />
        </div>

        {{-- Información institucional --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <flux:select
                wire:model="campus_id"
                label="Campus *" required
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);">
                <option value="">Seleccione</option>
                @foreach($campuses as $campus)
                    <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                @endforeach
            </flux:select>
            <flux:select
                wire:model="career_id"
                label="Carrera *" required
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);">
                <option value="">Seleccione</option>
                @foreach($careers as $career)
                    <option value="{{ $career->id }}">{{ $career->name }}</option>
                @endforeach
            </flux:select>
            <flux:select
                wire:model="system"
                label="Sistema *" required
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);">
                <option value="">Seleccione</option>
                @foreach($systems as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                @endforeach
            </flux:select>
        </div>

        {{-- Progreso académico --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <flux:select
                wire:model="period_id"
                label="Periodo *" required
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);">
                <option value="">Seleccione</option>
                @foreach($periods as $period)
                    <option value="{{ $period->id }}">{{ $period->name }}</option>
                @endforeach
            </flux:select>
            <flux:select
                wire:model="semester_id"
                label="Semestre *" required
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);">
                <option value="">Seleccione</option>
                @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                @endforeach
            </flux:select>
            <flux:input
                wire:model="reticular_progress"
                label="Avance Reticular (%) *"
                type="number" inputmode="decimal"
                min="0" max="100" step="0.01"
                placeholder="Ej. 70.00" required
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
            />
        </div>

    </div>
</div>