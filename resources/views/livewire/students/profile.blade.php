<div class="flex flex-col gap-6
            p-6 xl:px-12 2xl:px-16
            bg-[var(--student-profile-bg)]
            rounded-2xl
            shadow-[0_12px_40px_rgba(0,0,0,0.12)]
            max-w-7xl mx-auto">


    <!-- Encabezado -->
    <div class="space-y-2">
        <x-auth-header 
            :title="__('Perfil del estudiante')" 
            :description="__('Completa tu información personal y académica')" 
        />
    </div>

    {{-- Formulario --}}
    @if ($showForm)
        <form method="POST" wire:submit.prevent="save" class="flex flex-col gap-6">
            
            <!-- DATOS PERSONALES -->
            <div class="bg-[var(--student-document-bg-card)] rounded-xl border border-[var(--student-document-bg-card)] 
                        shadow-[0_2px_8px_rgba(0,0,0,0.08)] overflow-hidden transition-all duration-300 hover:shadow-[0_4px_12px_rgba(0,0,0,0.12)]">
                
                <div class="p-6 pb-4" 
                    style="background: linear-gradient(135deg, var(--student-document-bg-card-header-approved) 0%, var(--student-document-bg-card-header-approved-2) 100%);">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[var(--settings-btn-primary)]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[var(--settings-btn-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-[var(--student-profile-text-primary)]">
                            Datos Personales
                        </h2>
                    </div>
                </div>


                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <flux:input wire:model="last_name_paterno" label="Apellido Paterno *" type="text" inputmode="text" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras" oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')"/>
                        <flux:input wire:model="last_name_materno" label="Apellido Materno *" type="text" inputmode="text" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras" oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')"/>
                        <flux:input wire:model="name" label="Nombre(s) *" type="text" inputmode="text" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras" oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')"/>
                        <flux:input wire:model="curp" label="CURP *" type="text" required maxlength="18" minlength="18" inputmode="text" style="text-transform: uppercase;" pattern="[A-Z0-9]{18}" title="La CURP debe tener exactamente 18 caracteres." oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"/>
                        <flux:input wire:model="rfc" label="RFC" type="text" maxlength="13" minlength="12" inputmode="text" style="text-transform: uppercase;" pattern="[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}" title="RFC válido (12 o 13 caracteres, en mayúsculas)" oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9Ñ&]/g, '')"/>
                        <flux:input wire:model="phone" label="Teléfono *" type="text" placeholder="Ej. 0000000000" required maxlength="10" minlength="10" inputmode="numeric" pattern="[0-9]{10}" title="El teléfono debe contener 10 dígitos numéricos" oninput="this.value = this.value.replace(/[^0-9]/g, '')"/>
                        <flux:input wire:model="personal_email" label="Correo Personal *" type="email" placeholder="Ej. personal@ejemplo.com" required inputmode="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" title="Ingresa un correo válido" oninput="this.value = this.value.toLowerCase()"/>
                    </div>
                </div>
            </div>

            <!-- DATOS ACADÉMICOS -->
            @if ($this->canEditAcademic() && $student->status !== 'aprobado')
            <div class="bg-[var(--student-document-bg-card)] rounded-xl border border-[var(--student-document-bg-card)] 
                        shadow-[0_2px_8px_rgba(0,0,0,0.08)] overflow-hidden transition-all duration-300 hover:shadow-[0_4px_12px_rgba(0,0,0,0.12)]">
                
                <div class="p-6 pb-4" 
                        style="background: linear-gradient(135deg, var(--student-document-bg-card-header-approved) 0%, var(--student-document-bg-card-header-approved-2) 100%);">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[var(--settings-btn-primary)]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[var(--settings-btn-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-[var(--student-profile-text-primary)]">
                            Datos Académicos
                        </h2>
                    </div>
                </div>


                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <flux:input wire:model="control_number" label="Número de Control *" type="text" required inputmode="text" pattern="[A-Za-z0-9]+" title="Solo letras y números" oninput="this.value=this.value.replace(/[^A-Za-z0-9]/g,'').toUpperCase();"/>
                        <flux:input wire:model="institutional_email" label="Correo Institucional *" type="email" placeholder="Ej. l225q0000@itsco.edu.mx" required inputmode="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" title="Ingresa un correo válido" oninput="this.value=this.value.toLowerCase();"/>
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
            </div>
            @endif

            <!-- BOTÓN CON MEJOR DISEÑO -->
            <div class="flex justify-end pt-2">
                <flux:button type="submit" variant="primary" 
                class="
                    group relative overflow-hidden
                    rounded-xl
                    bg-[var(--settings-btn-primary)]!
                    text-[var(--settings-btn-primary-text)]!
                    shadow-lg shadow-[var(--settings-btn-primary-shadow)]
                    hover:bg-[var(--settings-btn-primary-hover)]!
                    hover:shadow-2xl hover:shadow-[var(--settings-btn-primary-shadow)]
                    hover:-translate-y-1
                    active:translate-y-0
                    transition-all duration-300
                    disabled:opacity-60 disabled:cursor-not-allowed
                    disabled:hover:translate-y-0
                    px-8 py-3.5
                    font-medium text-base
                    before:absolute before:inset-0 before:bg-white/10 before:translate-y-full 
                    before:transition-transform before:duration-300
                    hover:before:translate-y-0
                ">
                    <span class="relative z-10 flex items-center gap-2">
                        Guardar Perfil
                    </span>
                </flux:button>
            </div>
        </form>
    @else
        {{-- Mensajes según estado del estudiante --}}
        <div class="p-6 space-y-4">
            @if ($student->status === 'pendiente')
                <div class="bg-[var(--student-profile-card-bg)] p-6 rounded-xl shadow-lg">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-10 h-10 bg-[var(--status-icon-bg-pending)] rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-[var(--status-icon-color-pending)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-[var(--student-profile-text-primary)] font-semibold text-lg mb-2">
                                Perfil en revisión
                            </h3>
                            <p class="text-[var(--student-profile-text-secondary)] text-sm leading-relaxed">
                                Tu perfil fue enviado correctamente y está siendo revisado por un administrador. Te notificaremos cuando el proceso haya finalizado.
                            </p>
                        </div>
                    </div>
                </div>

            @elseif ($student->status === 'aprobado')
                <div class="bg-[var(--student-profile-card-bg)] p-6 rounded-xl shadow-lg">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-10 h-10 bg-[var(--status-icon-bg-approved)] rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-[var(--status-icon-color-approved)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-[var(--student-profile-text-primary)] font-semibold text-lg mb-2">
                                ¡Perfil Aprobado!
                            </h3>
                            <p class="text-[var(--student-profile-text-secondary)] text-sm leading-relaxed mb-4">
                                Tu perfil ha sido aprobado por el administrador. Ya puedes acceder a Mis Documentos.
                            </p>
                            <flux:button 
                                wire:click="$set('showForm', true)" 
                                class="
                                    group relative
                                    rounded-[var(--radius-md)]
                                    bg-[var(--settings-btn-primary)]!
                                    text-[var(--settings-btn-primary-text)]!
                                    shadow-lg shadow-[var(--settings-btn-primary-shadow)]
                                    hover:bg-[var(--settings-btn-primary-hover)]!
                                    hover:shadow-xl hover:-translate-y-0.5
                                    transition-all duration-300
                                    disabled:opacity-60 disabled:cursor-not-allowed
                                    px-6 py-3
                                "
                            >
                                Actualizar Datos
                            </flux:button>
                        </div>
                    </div>
                </div>

            @elseif ($student->status === 'rechazado')
                <div class="bg-[var(--student-profile-card-bg)] p-6 rounded-xl shadow-lg">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-10 h-10 bg-[var(--status-icon-bg-rejected)] rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-[var(--status-icon-color-rejected)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-[var(--student-profile-text-primary)] font-semibold text-lg mb-2">
                                Perfil Rechazado
                            </h3>
                            <p class="text-[var(--student-profile-text-secondary)] text-sm leading-relaxed mb-3">
                                Tu perfil no ha sido aprobado. Por favor, revisa la información y corrígela para volver a enviarla.
                            </p>

                            @if($student->rejection_reason)
                                <div class="bg-[var(--student-document-bg-content)] p-4 rounded-lg mb-4">
                                    <p class="text-xs font-semibold text-[var(--status-icon-color-rejected)] uppercase tracking-wide mb-2">
                                        Motivo del Rechazo
                                    </p>
                                    <p class="text-sm text-[var(--student-profile-text-secondary)] leading-relaxed">
                                        {{ $student->rejection_reason }}
                                    </p>
                                </div>
                            @endif

                            <flux:button 
                                wire:click="$set('showForm', true)" 
                                 class="
                                    group relative
                                    rounded-[var(--radius-md)]
                                    bg-[var(--settings-btn-primary)]!
                                    text-[var(--settings-btn-primary-text)]!
                                    shadow-lg shadow-[var(--settings-btn-primary-shadow)]
                                    hover:bg-[var(--settings-btn-primary-hover)]!
                                    hover:shadow-xl hover:-translate-y-0.5
                                    transition-all duration-300
                                    disabled:opacity-60 disabled:cursor-not-allowed
                                    px-6 py-3
                                "
                            >
                                Editar Datos
                            </flux:button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
