<flux:modal wire:model="isOpen" :dismissible="false"
            class="w-[95vw] sm:w-[85vw] md:w-[650px] lg:w-[700px] max-w-[95vw]">
    <div class="flex flex-col max-h-[85vh]">

        {{-- Header --}}
        <div class="relative px-6 py-5 flex items-center gap-4 overflow-hidden flex-shrink-0 rounded-t-xl border-b"
             style="border-bottom: 1px solid var(--index-border);">
            <div class="flex-1 min-w-0">
                <h3 class="text-lg font-bold" style="color: var(--index-text-primary);">
                    {{ $periodId ? 'Editar Periodo Académico' : 'Nuevo Periodo Académico' }}
                </h3>
                <p class="text-sm truncate" style="color: var(--index-text-secondary);">
                    {{ $periodId ? 'Modifique los datos del periodo seleccionado' : 'Complete la información requerida' }}
                </p>
            </div>
        </div>

        {{-- Body --}}
        <div class="flex-1 overflow-y-auto p-6 space-y-5">

            {{-- Nombre --}}
            <div class="p-4 rounded-xl border" style="background-color: var(--index-content-bg); border-color: var(--index-border);">
                <label class="block text-sm font-semibold mb-2" style="color: var(--index-text-primary);">
                    Nombre del Periodo <span style="color: var(--index-status-rejected-icon);">*</span>
                </label>
                <flux:input wire:model.defer="name" type="text" placeholder="Ejemplo: Enero-Junio 2025" class="w-full"
                            style="background-color: var(--index-card-bg); border-color: var(--index-border); color: var(--index-modal-text-primary);"/>
                <flux:error name="name"/>
            </div>

            {{-- Fechas --}}
            <div class="p-4 rounded-xl border" style="background-color: var(--index-content-bg); border-color: var(--index-border);">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 rounded-lg" style="background-color: var(--index-card-bg);">
                        <svg class="w-5 h-5" style="color: var(--index-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="text-base font-semibold" style="color: var(--index-text-primary);">Periodo de Vigencia</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach([['field' => 'start_date', 'label' => 'Fecha de Inicio'], ['field' => 'end_date', 'label' => 'Fecha de Término']] as $dateField)
                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color: var(--index-text-secondary);">
                                {{ $dateField['label'] }} <span style="color: var(--index-status-rejected-icon);">*</span>
                            </label>
                            <flux:input wire:model.defer="{{ $dateField['field'] }}" type="date" class="w-full"
                                        style="background-color: var(--index-card-bg); border-color: var(--index-border); color: var(--index-modal-text-primary);"/>
                            <flux:error name="{{ $dateField['field'] }}"/>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Semestres --}}
            <div class="p-4 rounded-xl border" style="background-color: var(--index-content-bg); border-color: var(--index-border);">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 rounded-lg" style="background-color: var(--index-card-bg);">
                        <svg class="w-5 h-5" style="color: var(--index-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h4 class="text-base font-semibold" style="color: var(--index-text-primary);">
                        Semestres Incluidos <span style="color: var(--index-status-rejected-icon);">*</span>
                    </h4>
                </div>

                <div class="space-y-2 max-h-52 overflow-y-auto p-4 rounded-lg"
                     style="border: 1px solid var(--index-border); background-color: var(--index-card-bg);">
                    @forelse($semesters as $semester)
                        <label class="flex items-center gap-3 p-2 rounded-lg cursor-pointer transition-all"
                               onmouseover="this.style.backgroundColor='var(--index-content-bg)'"
                               onmouseout="this.style.backgroundColor='transparent'">
                            <flux:checkbox wire:model.defer="selectedSemesters" value="{{ $semester->id }}"/>
                            <span class="text-sm font-medium" style="color: var(--index-modal-text-primary);">{{ $semester->name }}</span>
                        </label>
                    @empty
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4"
                                 style="background-color: var(--index-content-bg);">
                                <svg class="w-8 h-8" style="color: var(--index-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium mb-1" style="color: var(--index-modal-text-primary);">No hay semestres disponibles</p>
                            <p class="text-xs" style="color: var(--index-text-secondary);">Debe crear semestres antes de asignarlos a un periodo</p>
                        </div>
                    @endforelse
                </div>
                <flux:error name="selectedSemesters"/>
            </div>

            {{-- Estado activo --}}
            <div class="p-4 rounded-xl border" style="background-color: var(--index-content-bg); border-color: var(--index-border);">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg" style="background-color: var(--index-card-bg);">
                            <svg class="w-5 h-5" style="color: var(--index-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold" style="color: var(--index-text-primary);">Periodo activo</p>
                            <p class="text-xs" style="color: var(--index-text-secondary);">Este periodo estará disponible para los estudiantes</p>
                        </div>
                    </div>
                    <flux:switch wire:model="is_active"/>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-2 border-t flex-shrink-0"
             style="border-color: var(--index-border); background-color: var(--index-card-bg);">

            <button wire:click="$set('isOpen', false)"
                    class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm transition-all duration-300 hover:-translate-y-0.5"
                    style="color: var(--index-text-secondary);"
                    onmouseover="this.style.color='var(--index-text-primary)'; this.style.backgroundColor='var(--index-border)'"
                    onmouseout="this.style.color='var(--index-text-secondary)'; this.style.backgroundColor='transparent'">
                Cancelar
            </button>

            <button wire:click="save"
                    class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm font-medium shadow-lg
                           transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl
                           disabled:opacity-60 disabled:cursor-not-allowed"
                    style="background-color: var(--index-btn-primary-bg); color: var(--index-btn-primary-text);"
                    onmouseover="this.style.opacity='0.85'"
                    onmouseout="this.style.opacity='1'">
                {{ $periodId ? 'Guardar' : 'Crear' }}
            </button>

        </div>

    </div>
</flux:modal>