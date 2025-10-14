<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Perfil del estudiante')" :description="__('Completa tu información personal y académica')" />

    <form method="POST" wire:submit.prevent="save" class="flex flex-col gap-6">
        <!-- Datos Personales -->
        <div class="border-b pb-4">
            <h2 class="text-lg font-semibold mb-2">Datos Personales</h2>

            <flux:input wire:model="last_name_paterno" label="Apellido Paterno *" type="text" required />
            <flux:input wire:model="last_name_materno" label="Apellido Materno *" type="text" required />
            <flux:input wire:model="name" label="Nombre(s) *" type="text" required />
            <flux:input wire:model="curp" label="CURP *" type="text" required />
            <flux:input wire:model="rfc" label="RFC" type="text" />
            <flux:input wire:model="phone" label="Teléfono *" type="text" required/>
            <flux:input wire:model="personal_email" style="text-transform: lowercase;" label="Correo Personal *" type="email" required/>
        </div>

        <!-- Datos Académicos -->
        <div>
            <h2 class="text-lg font-semibold mb-2">Datos Académicos</h2>

            <flux:input wire:model="control_number" label="Número de Control *" type="text" required />
            <flux:input wire:model="institutional_email" style="text-transform: lowercase;" label="Correo Institucional *" type="email" placeholder="Ej. l225q0000@itsco.edu.mx"  required />

            <!-- Sistema -->
            <flux:select wire:model="system" label="Sistema *" required>
                <option value="">Seleccione</option>
                @foreach($systems as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                @endforeach
            </flux:select>

            <!-- Semestre -->
            <flux:select wire:model="semester_id" label="Semestre *" required>
                <option value="">Seleccione</option>
                @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                @endforeach
            </flux:select>

            <!-- Avance Reticular -->
            <flux:input 
                wire:model="reticular_progress" 
                label="Avance Reticular (%) *" 
                type="number" 
                min="0" 
                max="100" 
                step="0.01"
                placeholder="Ej. 70.00" 
                required 
            />

            <!-- Campus -->
            <flux:select wire:model="campus_id" label="Campus *" required>
                <option value="">Seleccione</option>
                @foreach($campuses as $campus)
                    <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                @endforeach
            </flux:select>

            <!-- Carrera -->
            <flux:select wire:model="career_id" label="Carrera *" required>
                <option value="">Seleccione</option>
                @foreach($careers as $career)
                    <option value="{{ $career->id }}">{{ $career->name }}</option>
                @endforeach
            </flux:select>

            <!-- Periodo -->
            <flux:select wire:model="period_id" label="Periodo *" required>
                <option value="">Seleccione</option>
                @foreach($periods as $period)
                    <option value="{{ $period->id }}">{{ $period->name }}</option>
                @endforeach
            </flux:select>
        </div>

        <!-- Botón -->
        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                Guardar Perfil
            </flux:button>
        </div>
    </form>
</div>