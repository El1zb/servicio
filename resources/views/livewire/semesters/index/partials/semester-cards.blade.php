<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    @foreach($semesters as $semester)
        <div class="period-card group">
            <div class="stat-card-top">
                <div class="min-w-0 flex-1 flex flex-col gap-2">
                    <p class="stat-card-label">{{ $semester->name }}</p>
                    <span class="period-card-status {{ $semester->is_active ? 'is-active' : '' }}" style="align-self:flex-start;">
                        <span class="period-card-status-dot"></span>
                        {{ $semester->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>

                <div class="stat-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 9h16M4 15h16M10 3L8 21M16 3l-2 18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <div class="period-card-footer" style="justify-content:flex-end;">
                <button wire:click="edit({{ $semester->id }})"
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
    {{ $semesters->links() }}
</div>
