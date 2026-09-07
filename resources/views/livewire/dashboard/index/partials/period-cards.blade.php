{{-- Period Cards Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    @foreach($periods as $period)
        <a href="{{ route('periods.detail', $period->id) }}" class="period-card group">

            <div class="period-card-header">
                <div class="min-w-0">
                    <h3 class="period-card-name">{{ $period->name }}</h3>
                    <p class="period-card-dates">{{ $period->startFormatted }} — {{ $period->endFormatted }}</p>
                </div>
                <span class="period-card-status {{ $period->is_active ? 'is-active' : '' }}">
                    <span class="period-card-status-dot"></span>
                    {{ $period->is_active ? 'Activo' : 'Inactivo' }}
                </span>
            </div>

            <div class="flex items-center justify-between gap-3">
                @if($period->hasStudents)
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col gap-0.5">
                            <span class="flex items-center gap-1.5">
                                <span class="period-card-stat-value">{{ $period->pending_review_documents_count }}</span>
                                <svg width="14" height="14" viewBox="0 0 52 52" fill="var(--color-icon)" class="flex-shrink-0">
                                    <path d="M44.4,19H33.2c-2.6,0-4.2-1.6-4.2-4.2V3.6C29,2.7,28.3,2,27.4,2H10.8C8.2,2,6,4.2,6,6.8v38.4 c0,2.6,2.2,4.8,4.8,4.8h30.4c2.6,0,4.8-2.2,4.8-4.8V20.6C46,19.7,45.3,19,44.4,19z"/>
                                    <path d="M45.7,12.9L35.1,2.3C34.9,2.1,34.5,2,34.2,2l0,0C33.6,2,33,2.5,33,3.1v8.5c0,1.8,1.6,3.4,3.4,3.4h8.5 c0.6,0,1.1-0.6,1.1-1.2l0,0C46,13.5,45.9,13.1,45.7,12.9z"/>
                                </svg>
                            </span>
                            <span class="period-card-stat-label">Por revisar</span>
                        </div>

                        <div class="flex flex-col gap-0.5">
                            <span class="flex items-center gap-1.5">
                                <span class="period-card-stat-value">{{ $period->pending_students_count }}</span>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--color-icon)" class="flex-shrink-0">
                                    <circle cx="12" cy="6" r="4"/>
                                    <ellipse cx="12" cy="17" rx="7" ry="4"/>
                                </svg>
                            </span>
                            <span class="period-card-stat-label">Por aceptar</span>
                        </div>
                    </div>
                @else
                    <p class="period-card-empty">Sin estudiantes registrados</p>
                @endif

                <button wire:click.prevent="editPeriod({{ $period->id }})"
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

        </a>
    @endforeach
</div>

{{-- Paginación --}}
<div class="mt-6">
    {{ $periods->links() }}
</div>
