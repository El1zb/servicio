<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-5">
    @foreach($campuses as $campus)
        {{-- Toda la card abre "editar" de un clic (el botón detiene la
             propagación para no disparar los dos), igual que en submission/
             informative-documents.blade.php. --}}
        <div wire:click="edit({{ $campus->id }})" class="period-card period-card--clickable group">
            {{-- Mobile: badge arriba, pegado a la derecha; el nombre va debajo.
                 Desktop (lg:): mismo header de siempre, nombre + badge en fila. --}}
            <div class="flex justify-end mb-2 lg:hidden">
                <x-status-badge :active="$campus->is_active" />
            </div>

            <div class="period-card-header">
                <h3 class="period-card-name" style="margin:0;">{{ $campus->name }}</h3>
                <div class="hidden lg:flex">
                    <x-status-badge :active="$campus->is_active" />
                </div>
            </div>

            <div class="period-card-footer">
                <p class="stat-card-description" style="margin:0;">
                    <span class="stat-card-dot"></span>
                    {{ $campus->careers_count }} {{ $campus->careers_count === 1 ? 'carrera relacionada' : 'carreras relacionadas' }}
                </p>

                <button wire:click.stop="edit({{ $campus->id }})"
                        class="w-7 h-7 flex items-center justify-center rounded-full flex-shrink-0
                               text-[var(--color-icon)] bg-transparent cursor-pointer
                               opacity-0 group-hover:opacity-100
                               transition-all duration-150
                               hover:text-[var(--color-icon-hover)] hover:bg-[var(--sidebar-color-hover)]">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M20.1497 7.93997L8.27971 19.81C7.21971 20.88 4.04971 21.3699 3.27971 20.6599C2.50971 19.9499 3.06969 16.78 4.12969 15.71L15.9997 3.84C16.5478 3.31801 17.2783 3.03097 18.0351 3.04019C18.7919 3.04942 19.5151 3.35418 20.0503 3.88938C20.5855 4.42457 20.8903 5.14781 20.8995 5.90463C20.9088 6.66146 20.6217 7.39189 20.0997 7.93997H20.1497Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 21H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $campuses->links() }}
</div>
