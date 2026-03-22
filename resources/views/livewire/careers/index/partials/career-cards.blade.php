{{-- Grid de Carreras --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 p-4">
    @foreach($careers as $career)
        <div class="flex flex-col h-full rounded-2xl overflow-hidden transition-all duration-200
                    border border-[var(--color-border-hover)]
                    hover:shadow-[0_4px_24px_rgba(0,0,0,0.25)] hover:border-[var(--color-indicator)]">

            {{-- Cuerpo --}}
            <div class="flex-1 flex flex-col gap-3 p-4">
                <div>
                    <p class="text-[9px] font-semibold tracking-widest uppercase text-[var(--color-secondary)] mb-1">
                        Carrera
                    </p>
                    <h3 class="text-sm font-medium text-[var(--color-primary-2)] leading-snug">
                        {{ $career->name }}
                    </h3>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-1 px-2.5 py-2 border-t border-[var(--color-border-hover)] flex-shrink-0">

                {{-- Editar --}}
                <button wire:click="edit({{ $career->id }})"
                        class="w-7 h-7 flex items-center justify-center rounded-md border border-transparent
                               text-[var(--color-icon)] bg-transparent cursor-pointer
                               transition-all duration-150
                               hover:text-[var(--color-icon-hover)] hover:border-[var(--color-border-hover)] hover:bg-[var(--color-icon-bg-hover)]">
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>

                {{-- Separador --}}
                <span class="w-px h-3.5 bg-[var(--color-border-hover)] mx-0.5 flex-shrink-0"></span>

                {{-- Eliminar --}}
                <button wire:click="confirmDelete({{ $career->id }})"
                        class="w-7 h-7 flex items-center justify-center rounded-md border border-transparent
                               text-[var(--color-icon)] bg-transparent cursor-pointer
                               transition-all duration-150
                               hover:text-red-400 hover:border-red-400/30 hover:bg-red-500/10">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>

            </div>
        </div>
    @endforeach
</div>

{{-- Paginación --}}
<div class="mt-6 px-4">
    {{ $careers->links() }}
</div>