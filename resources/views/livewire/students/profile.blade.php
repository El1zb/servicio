<div class="flex flex-col gap-8 p-6 bg-white dark:bg-zinc-800 rounded-lg">
    <!-- Encabezado -->
    <x-auth-header 
        :title="__('Perfil del estudiante')" 
        :description="__('Completa tu información personal y académica')" 
    />

    {{-- Mensajes flash --}}
    @if (session()->has('info'))
        <div class="mb-4 p-3 rounded-lg bg-blue-100 text-blue-800 border border-blue-200">
            {{ session('info') }}
        </div>
    @endif

    @if (session()->has('message'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-800 border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    {{-- Formulario --}}
    @if ($showForm)
        <form method="POST" wire:submit.prevent="save" class="flex flex-col gap-8">
            <!-- DATOS PERSONALES -->
            <div class="p-4 bg-gray-50 dark:bg-zinc-900 rounded-lg border border-gray-200 dark:border-zinc-700">
                <h2 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-zinc-700 pb-2">Datos Personales</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="last_name_paterno" label="Apellido Paterno *" type="text" required />
                    <flux:input wire:model="last_name_materno" label="Apellido Materno *" type="text" required />
                    <flux:input wire:model="name" label="Nombre(s) *" type="text" required />
                    <flux:input wire:model="curp" label="CURP *" type="text" required />
                    <flux:input wire:model="rfc" label="RFC" type="text" />
                    <flux:input wire:model="phone" label="Teléfono *" type="text" required/>
                    <flux:input wire:model="personal_email" style="text-transform: lowercase;" label="Correo Personal *" type="email" required/>
                </div>
            </div>

            <!-- DATOS ACADÉMICOS -->
            @if ($this->canEditAcademic() && $student->status !== 'aprobado')
            <div class="p-4 bg-gray-50 dark:bg-zinc-900 rounded-lg border border-gray-200 dark:border-zinc-700">
                <h2 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-zinc-700 pb-2">Datos Académicos</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="control_number" label="Número de Control *" type="text" required />
                    <flux:input wire:model="institutional_email" label="Correo Institucional *" type="email" placeholder="Ej. l225q0000@itsco.edu.mx" required />
                    <flux:select wire:model="system" label="Sistema *" required>
                        <option value="">Seleccione</option>
                        @foreach($systems as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model="semester_id" label="Semestre *" required>
                        <option value="">Seleccione</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:input wire:model="reticular_progress" label="Avance Reticular (%) *" type="number" min="0" max="100" step="0.01" placeholder="Ej. 70.00" required />
                    <flux:select wire:model="campus_id" label="Campus *" required>
                        <option value="">Seleccione</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model="career_id" label="Carrera *" required>
                        <option value="">Seleccione</option>
                        @foreach($careers as $career)
                            <option value="{{ $career->id }}">{{ $career->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model="period_id" label="Periodo *" required>
                        <option value="">Seleccione</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}">{{ $period->name }}</option>
                        @endforeach
                    </flux:select>
                </div>
            </div>
            @endif

            <!-- BOTÓN -->
            <div class="flex justify-end">
                <flux:button type="submit" variant="primary" class="px-6 py-3 rounded-lg">
                    Guardar Perfil
                </flux:button>
            </div>
        </form>
    @else
        {{-- Mensajes según estado del estudiante --}}
        <div class="text-center p-6">
            @if ($student->status === 'pendiente')
                <div class="bg-blue-50 p-5 rounded-lg border border-blue-200">
                    <p class="text-blue-700 font-medium text-lg">
                        🕐 Tu información ha sido enviada y está en revisión.
                    </p>
                </div>
            @elseif ($student->status === 'aprobado')
                <div class="bg-green-50 p-5 rounded-lg border border-green-200">
                    <p class="text-green-700 font-semibold text-lg">
                        Tu perfil ha sido aprobado por el administrador.
                    </p>
                    <div class="mt-4 flex justify-center">
                        <flux:button 
                            wire:click="$set('showForm', true)" 
                            class="w-full max-w-xs bg-green-100 text-green-700 font-medium py-2 px-4 rounded-lg border border-green-300 hover:bg-green-200 transition-colors duration-150">
                            Actualizar datos personales
                        </flux:button>
                    </div>
                </div>
            @elseif ($student->status === 'rechazado')
                <div class="bg-red-50 p-5 rounded-lg border border-red-200">
                    <p class="text-red-700 font-semibold text-lg">
                        Tu perfil fue rechazado.
                    </p>
                    <p class="text-gray-600 text-sm mt-1">
                        Revisa los datos y vuelve a enviarlos para validación.
                    </p>
                    <div class="mt-4 flex justify-center">
                        <flux:button 
                            wire:click="$set('showForm', true)" 
                            class="w-full max-w-xs bg-red-100 text-red-700 font-medium py-2 px-4 rounded-lg border border-red-300 hover:bg-red-200 transition-colors duration-150">
                            Editar datos
                        </flux:button>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
