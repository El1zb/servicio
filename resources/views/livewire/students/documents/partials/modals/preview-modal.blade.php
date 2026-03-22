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
            <div class="flex items-start gap-3 pb-4 border-b border-[var(--color-border-hover)] shrink-0">
                <div class="w-10 h-10 rounded-lg bg-[var(--color-icon-bg)] flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-[var(--color-secondary)] text-sm"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <flux:heading size="lg" class="text-[var(--color-primary-2)] truncate">{{ $cleanName }}</flux:heading>
                    <p class="text-xs sm:text-sm text-[var(--color-secondary)] mt-1">Vista previa del documento</p>
                </div>
            </div>

            {{-- Contenido --}}
            <div class="flex-1 overflow-hidden py-3 sm:py-4 min-h-0">
                @if($ext === 'pdf')
                    <iframe src="{{ asset('storage/'.$previewPath) }}"
                            class="w-full h-full rounded-xl border border-[var(--color-border-hover)]"></iframe>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-center px-4">
                        <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-[var(--color-icon-bg)] rounded-2xl border border-[var(--color-border-hover)] mb-4">
                            <i class="fas fa-file-download text-3xl sm:text-4xl text-[var(--color-secondary)]"></i>
                        </div>
                        <p class="text-[var(--color-primary-2)] font-medium text-base sm:text-lg mb-1">Este archivo no se puede previsualizar</p>
                        <p class="text-sm text-[var(--color-secondary)]">Puedes descargarlo para verlo en tu dispositivo</p>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 pt-4 border-t border-[var(--color-border-hover)] shrink-0">

                <a href="{{ asset('storage/'.$previewPath) }}" download
                   class="group relative inline-flex items-center justify-center px-4 py-2 rounded-[var(--radius-md)]
                          bg-[var(--color-icon-bg)]! text-[var(--color-secondary)]!
                          border border-[var(--color-border-hover)]
                          hover:bg-[var(--color-card-bg-hover)]! hover:text-[var(--color-primary-2)]!
                          hover:shadow-md hover:-translate-y-0.5
                          transition-all duration-300 gap-2">
                    <i class="fas fa-download text-xs"></i>
                    Descargar
                </a>

            </div>

        </div>
    </flux:modal>
@endif