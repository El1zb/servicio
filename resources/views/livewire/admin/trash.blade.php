<section class="w-full">
    <x-settings.layout subheading="Periodos, semestres, carreras, campus y documentos eliminados. Se conservan 30 días antes de borrarse por completo.">
        <div class="space-y-6"
             x-data="{
                selected: [],
                confirmingPurge: false,
                toggle(key) {
                    this.selected = this.selected.includes(key)
                        ? this.selected.filter(k => k !== key)
                        : [...this.selected, key];
                },
                cancelSelection() {
                    this.selected = [];
                    this.confirmingPurge = false;
                },
             }">

            {{-- Barra de acciones masivas --}}
            <div x-show="selected.length > 0" x-cloak
                 class="flex flex-wrap items-center justify-between gap-4 p-4 rounded-xl"
                 style="background-color: var(--color-card-bg);">

                <template x-if="!confirmingPurge">
                    <p class="text-sm font-medium" style="color: var(--color-primary-2);">
                        <span x-text="selected.length"></span> seleccionado(s)
                    </p>
                </template>
                <template x-if="confirmingPurge">
                    <p class="text-sm font-medium" style="color: #DC2626;">
                        ¿Eliminar permanentemente? Esta acción no se puede deshacer.
                    </p>
                </template>

                <div class="flex items-center gap-3" x-show="!confirmingPurge">
                    <button type="button" @click="cancelSelection()"
                            class="text-sm" style="color: var(--color-secondary);">
                        Cancelar
                    </button>
                    <button type="button"
                            @click="$wire.call('restoreSelected', selected).then(() => selected = [])"
                            class="btn-primary" style="height: 36px; padding: 0 16px; font-size: 13px;">
                        Restaurar
                    </button>
                    <button type="button"
                            @click="confirmingPurge = true"
                            class="btn-danger" style="height: 36px; padding: 0 16px; font-size: 13px;">
                        Eliminar
                    </button>
                </div>

                <div class="flex items-center gap-3" x-show="confirmingPurge" x-cloak>
                    <button type="button" @click="confirmingPurge = false"
                            class="text-sm" style="color: var(--color-secondary);">
                        No
                    </button>
                    <button type="button"
                            @click="$wire.call('purgeSelected', selected).then(() => { selected = []; confirmingPurge = false; })"
                            class="btn-danger" style="height: 36px; padding: 0 16px; font-size: 13px;">
                        Sí, eliminar
                    </button>
                </div>
            </div>

            @forelse($items as $item)
                @php($key = $item['type'].':'.$item['id'])
                @if($loop->first)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @endif

                <div class="period-card group">
                    <button type="button"
                            @click="toggle('{{ $key }}')"
                            class="trash-select-checkbox opacity-0 group-hover:opacity-100 transition-opacity duration-150"
                            :class="{ 'is-checked': selected.includes('{{ $key }}'), '!opacity-100': selected.length > 0 }">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                    </button>

                    <div class="stat-card-top">
                        <div class="min-w-0 flex-1 flex flex-col gap-1">
                            <span class="period-card-status" style="align-self:flex-start;">
                                <span class="period-card-status-dot"></span>
                                {{ $item['label'] }}
                            </span>
                            <p class="stat-card-label truncate">{{ $item['name'] }}</p>
                            @if(!empty($item['periodName']))
                                <p class="text-xs truncate" style="color: var(--color-secondary);">
                                    Periodo: {{ $item['periodName'] }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <p class="stat-card-description" style="margin:0;">
                        <span class="stat-card-dot"></span>
                        Eliminado el {{ $item['deletedAt']->format('d/m/Y') }}
                        @if($item['daysLeft'] > 0)
                            · se elimina en {{ $item['daysLeft'] }} {{ $item['daysLeft'] === 1 ? 'día' : 'días' }}
                        @else
                            · se eliminará pronto
                        @endif
                    </p>

                    @if(! $item['canPurge'])
                        <p class="text-xs" style="color: var(--color-secondary); margin:0;">
                            {{ $item['blockedReason'] }}
                        </p>
                    @endif

                    <div class="flex items-center justify-end gap-1" x-show="selected.length === 0">
                        <button wire:click="restore('{{ $item['type'] }}', {{ $item['id'] }})"
                                title="Restaurar"
                                class="w-7 h-7 flex items-center justify-center rounded-full flex-shrink-0
                                       text-[var(--color-icon)] bg-transparent cursor-pointer
                                       opacity-0 group-hover:opacity-100
                                       transition-all duration-150
                                       hover:text-[var(--color-icon-hover)] hover:bg-[var(--sidebar-color-hover)]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M19.7285 10.9288C20.4413 13.5978 19.7507 16.5635 17.6569 18.6573C15.1798 21.1344 11.4826 21.6475 8.5 20.1966M18.364 8.05071L17.6569 7.3436C14.5327 4.21941 9.46736 4.21941 6.34316 7.3436C3.42964 10.2571 3.23318 14.8588 5.75376 18M18.364 8.05071H14.1213M18.364 8.05071V3.80807" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <button
                            @if($item['canPurge'])
                                wire:click="confirmPurge('{{ $item['type'] }}', {{ $item['id'] }})"
                                title="Eliminar permanente"
                            @else
                                disabled
                                title="{{ $item['blockedReason'] }}"
                            @endif
                            class="w-7 h-7 flex items-center justify-center rounded-full flex-shrink-0
                                   text-[var(--color-icon)] bg-transparent transition-all duration-150
                                   {{ $item['canPurge']
                                        ? 'opacity-0 group-hover:opacity-100 cursor-pointer hover:text-[var(--color-icon-hover)] hover:bg-[var(--sidebar-color-hover)]'
                                        : 'opacity-0 group-hover:opacity-40 cursor-not-allowed' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6 7V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>

                @if($loop->last)
                    </div>
                @endif
            @empty
                {{-- Estado vacío --}}
                <div class="text-center py-20">
                    <svg class="w-14 h-14 mx-auto mb-4" style="color: var(--color-secondary);" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 7H20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 7V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-primary-2);">
                        La papelera está vacía
                    </h3>
                    <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--color-secondary);">
                        Los periodos, semestres, carreras, campus y documentos que elimines aparecerán aquí.
                    </p>
                </div>
            @endforelse

            {{-- =================== MODAL ELIMINAR PERMANENTEMENTE =================== --}}
            @if($isPurgeModalOpen && $purgeToDelete)
                <flux:modal
                    wire:model="isPurgeModalOpen"
                    :dismissible="false"
                    class="w-[95vw] sm:w-[420px] max-w-[95vw]"
                    style="border-color: var(--color-border);">

                    <div class="flex flex-col">

                        {{-- ── Body ── --}}
                        <div class="px-6 py-6" style="background-color: var(--color-card-bg);">
                            <p class="text-sm font-semibold mb-1" style="color: var(--color-primary-2);">
                                ¿Eliminar permanentemente?
                            </p>
                            <p class="text-sm" style="color: var(--color-secondary);">
                                {{ $purgeToDelete['label'] }}: {{ $purgeToDelete['name'] }}
                                — esta acción no se puede deshacer.
                            </p>
                        </div>

                        {{-- ── Footer ── --}}
                        <div class="flex items-center justify-end gap-2 px-6 py-4 flex-shrink-0"
                            style="border-top: 1px solid var(--color-border);">

                            <button wire:click="$set('isPurgeModalOpen', false)"
                                    class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                                Cancelar
                            </button>

                            <button wire:click="purge" class="btn-danger">
                                Eliminar
                            </button>
                        </div>

                    </div>
                </flux:modal>
            @endif

        </div>
    </x-settings.layout>
</section>
