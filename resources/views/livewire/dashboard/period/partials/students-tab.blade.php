{{-- GESTIÓN DE ESTUDIANTES --}}
<div class="backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden"
     style="background-color: var(--color-card-bg); border: 1px solid var(--color-border-hover);">

    {{-- Header --}}
    <div class="p-4 sm:p-6" style="border-bottom: 1px solid var(--color-border-hover);">
        <h2 class="text-xl sm:text-2xl font-bold mb-2" style="color: var(--color-primary-2);">Gestión de Estudiantes</h2>
        <p style="color: var(--color-secondary);">Revisa el perfil de los estudiantes</p>

        <div class="mt-4 flex flex-col lg:flex-row gap-3 items-stretch lg:items-center">
            {{-- Buscador --}}
            <div class="flex-1 min-w-0">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="color: var(--color-secondary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Buscar por nombre o número de control..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all focus:ring-2 focus:outline-none"
                        style="background-color: var(--color-icon-bg);
                               color: var(--color-primary-2);
                               border: 1px solid var(--color-border-hover);"
                    />
                </div>
            </div>

            {{-- Filtro de estado --}}
            <flux:select
                wire:model.live="statusFilterStudents"
                class="w-full lg:w-48 px-3 py-2.5 rounded-lg text-sm font-medium transition-all"
                style="background-color: var(--color-icon-bg);
                       color: var(--color-primary-2);
                       border: 1px solid var(--color-border-hover);"
            >
                <option value="">Todos</option>
                <option value="pending">Pendientes</option>
                <option value="approved">Aprobados</option>
                <option value="rejected">Rechazados</option>
            </flux:select>
        </div>
    </div>

    {{-- Tabla: escritorio --}}
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full">
            <thead style="background-color: var(--color-icon-bg);">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase"
                        style="color: var(--color-secondary);">Nombre</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase"
                        style="color: var(--color-secondary);">Avance Reticular</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase"
                        style="color: var(--color-secondary);">Estatus</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase"
                        style="color: var(--color-secondary);">Acciones</th>
                </tr>
            </thead>

            <tbody style="border-top: 1px solid var(--color-border-hover);">
                @forelse($students as $student)
                    <tr class="transition"
                        onmouseover="this.style.backgroundColor='var(--color-hover)'"
                        onmouseout="this.style.backgroundColor='transparent'"
                        style="border-bottom: 1px solid var(--color-border-hover);">

                        {{-- Nombre --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div>
                                    <p class="font-medium" style="color: var(--color-primary-2);">
                                        {{ $student->name }} {{ $student->last_name_paterno }} {{ $student->last_name_materno }}
                                    </p>
                                    <p class="text-xs" style="color: var(--color-secondary);">{{ $student->career->name }}</p>
                                    <p class="text-xs" style="color: var(--color-secondary);">{{ $student->control_number }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Avance --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="text-sm min-w-[3rem] text-right"
                                      style="color: var(--color-primary-2);">
                                    {{ $student->reticular_progress }}%
                                </span>
                            </div>
                        </td>

                        {{-- Estatus --}}
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full"
                                  style="{{ $student->status_style }}">
                                {{ $student->status_label }}
                            </span>
                        </td>

                        {{-- Acciones --}}
                        <td class="px-6 py-4">
                            {{-- Acciones desktop --}}
                                <button
                                    wire:click="viewDetails({{ $student->id }})"
                                    class="px-3 py-2 text-xs rounded-lg transition flex items-center gap-2"
                                    style="background-color: var(--color-icon-bg); color: var(--color-primary);"
                                    onmouseover="this.style.backgroundColor='var(--color-hover)'; this.style.transform='translateY(-2px)';"
                                    onmouseout="this.style.backgroundColor='var(--color-icon-bg)'; this.style.transform='translateY(0)';"
                                >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver perfil
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center"
                            style="color: var(--color-secondary);">
                            No hay estudiantes registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Cards: móvil --}}
    <div class="sm:hidden divide-y" style="border-color: var(--color-border-hover);">
        @forelse($students as $student)
            <div class="p-4 transition"
                 onmouseover="this.style.backgroundColor='var(--color-hover)'"
                 onmouseout="this.style.backgroundColor='transparent'">

                {{-- Nombre + carrera --}}
                <div class="mb-3">
                    <p class="font-medium text-sm" style="color: var(--color-primary-2);">
                        {{ $student->name }} {{ $student->last_name_paterno }} {{ $student->last_name_materno }}
                    </p>
                    <p class="text-xs mt-0.5" style="color: var(--color-secondary);">{{ $student->career->name }}</p>
                    <p class="text-xs" style="color: var(--color-secondary);">{{ $student->control_number }}</p>
                </div>

                {{-- Avance + Estatus --}}
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-xs mb-0.5" style="color: var(--color-secondary);">Avance Reticular</p>
                        <p class="text-sm font-medium" style="color: var(--color-primary-2);">
                            {{ $student->reticular_progress }}%
                        </p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full"
                          style="{{ $student->status_style }}">
                        {{ $student->status_label }}
                    </span>
                </div>

                {{-- Acción --}}
                {{-- Acción móvil --}}
                    <button
                        wire:click="viewDetails({{ $student->id }})"
                        class="w-full px-3 py-2 text-xs rounded-lg transition flex items-center justify-center gap-2"
                        style="background-color: var(--color-icon-bg); color: var(--color-primary);"
                        onmouseover="this.style.backgroundColor='var(--color-hover)'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.backgroundColor='var(--color-icon-bg)'; this.style.transform='translateY(0)';"
                    >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Ver perfil
                </button>
            </div>
        @empty
            <div class="px-6 py-12 text-center" style="color: var(--color-secondary);">
                No hay estudiantes registrados.
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="p-4" style="border-top: 1px solid var(--color-border-hover);">
        {{ $students->links() }}
    </div>

    @include('livewire.dashboard.period.modals.student-modal')
    @include('livewire.dashboard.period.modals.reject-modal')
    @include('livewire.dashboard.period.modals.preview-modal')
</div>