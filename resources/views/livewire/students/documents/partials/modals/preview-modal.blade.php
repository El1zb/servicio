{{-- Modal: Previsualización de archivo --}}
@if($previewPath)
    @php
        $cleanName = $previewName;
        $pos = strpos($cleanName, '_');
        if ($pos !== false) $cleanName = substr($cleanName, 0, $pos);
        $ext = strtolower(pathinfo($previewPath, PATHINFO_EXTENSION));
    @endphp
    <flux:modal :dismissible="false" wire:model="previewPath"
                class="w-[95vw] sm:w-[90vw] md:w-[85vw] lg:w-[1100px] h-[90vh] sm:h-[85vh] max-w-[1400px]">

        <div class="flex flex-col h-full">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 sm:pb-4 border-b dark:border-gray-700 shrink-0">
                <div class="min-w-0 flex-1">
                    <flux:heading size="lg" class="text-[var(--modal-text-primary)] truncate">{{ $cleanName }}</flux:heading>
                    <p class="text-xs sm:text-sm text-[var(--modal-text-secondary)] mt-1">Vista previa del documento</p>
                </div>
            </div>

            {{-- Contenido --}}
            <div class="flex-1 overflow-hidden py-3 sm:py-4 min-h-0">
                @if($ext === 'pdf')
                    <iframe src="{{ asset('storage/'.$previewPath) }}"
                            class="w-full h-full rounded-lg sm:rounded-xl border dark:border-gray-700"></iframe>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-center px-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-[var(--index-icon-bg)] rounded-xl sm:rounded-2xl mb-3 sm:mb-4">
                            <i class="fas fa-file-download text-3xl sm:text-4xl text-[var(--index-icon-text)]"></i>
                        </div>
                        <p class="text-[var(--modal-text-primary)] text-base sm:text-lg">Este archivo no se puede previsualizar</p>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 pt-3 sm:pt-4 border-t dark:border-gray-700 shrink-0">
                <a href="{{ asset('storage/'.$previewPath) }}" download
                   class="group relative inline-flex items-center justify-center px-4 py-2 rounded-[var(--radius-md)]
                          bg-[var(--modal-btn-document-descargar)]! text-[var(--modal-btn-document-descargar-text)]!
                          shadow-lg shadow-[var(--modal-btn-document-descargar-shadow)]
                          hover:bg-[var(--modal-btn-document-descargar-hover)]! hover:shadow-xl hover:-translate-y-0.5
                          transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
                    <i class="fas fa-download"></i>
                    Descargar
                </a>
                <flux:button variant="primary" wire:click="$set('previewPath', null)"
                    class="group relative inline-flex items-center justify-center px-4 py-2 rounded-[var(--radius-md)]
                           bg-[var(--modal-btn-document-cerrar)]! text-[var(--modal-btn-document-cerrar-text)]!
                           shadow-lg shadow-[var(--modal-btn-document-cerrar-shadow)]
                           hover:bg-[var(--modal-btn-document-cerrar-hover)]! hover:shadow-xl hover:-translate-y-0.5
                           transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
                    Cerrar
                </flux:button>
            </div>

        </div>
    </flux:modal>
@endif