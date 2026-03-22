{{-- Period Cards Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
    @foreach($periods as $period)
        <a href="{{ route('periods.detail', $period->id) }}" class="block h-full">
            <div style="background-color: var(--color-icon-bg); border: 1px solid var(--color-border-hover); border-radius: 14px; overflow: hidden; transition: border-color 0.18s ease, box-shadow 0.18s ease; display: flex; flex-direction: column; height: 100%;"
                 onmouseover="this.style.borderColor='var(--color-indicator)'; this.style.boxShadow='0 4px 24px rgba(0,0,0,0.25)'"
                 onmouseout="this.style.borderColor='var(--color-border-hover)'; this.style.boxShadow='none'">

                {{-- Card Body --}}
                <div style="padding: 18px 18px 14px; flex: 1; display: flex; flex-direction: column;">

                    {{-- Status Badge --}}
                    <div style="margin-bottom: 10px;">
                        <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 9.5px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: {{ $period->is_active ? 'rgb(52,211,153)' : 'var(--color-secondary)' }};">
                            <span style="width: 6px; height: 6px; border-radius: 50%; display: inline-block; flex-shrink: 0;
                                background-color: {{ $period->is_active ? 'rgb(52,211,153)' : 'var(--color-secondary)' }};
                                {{ $period->is_active ? 'box-shadow: 0 0 6px rgb(52,211,153);' : 'opacity:0.4;' }}">
                            </span>
                            {{ $period->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    {{-- Nombre y fechas --}}
                    <h2 style="font-size: 20px; font-weight: 500; color: var(--color-primary-2); letter-spacing: -0.03em; line-height: 1.15; margin-bottom: 5px;">
                        {{ $period->name }}
                    </h2>
                    <p style="font-size: 11px; font-weight: 300; color: var(--color-secondary); margin-bottom: 16px;">
                        {{ $period->startFormatted }} — {{ $period->endFormatted }}
                    </p>

                    {{-- Student Stats --}}
                    <div style="margin-bottom: 14px; display: flex; align-items: center;">
                        @if($period->hasStudents)
                            @php
                                $statCols = [
                                    ['value' => $period->approved_students_count, 'label' => 'Aprobados',  'color' => 'rgb(52,211,153)',  'size' => '20px', 'flex' => '1',   'transform' => ''],
                                    ['value' => $period->pending_students_count,  'label' => 'Pendientes', 'color' => 'rgb(96,165,250)',   'size' => '30px', 'flex' => '1.4', 'transform' => 'translateY(-6px)'],
                                    ['value' => $period->rejected_students_count, 'label' => 'Rechazados', 'color' => 'rgb(248,113,113)',  'size' => '20px', 'flex' => '1',   'transform' => ''],
                                ];
                            @endphp
                            <div style="display: flex; gap: 5px; width: 100%; align-items: center;">
                                @foreach($statCols as $col)
                                    <div style="flex: {{ $col['flex'] }}; min-width: 0; padding: 8px 6px; display: flex; flex-direction: column; justify-content: center; align-items: center; {{ $col['transform'] ? 'transform: ' . $col['transform'] . ';' : '' }}">
                                        <div style="font-size: {{ $col['size'] }}; font-weight: 700; color: var(--color-primary-2); letter-spacing: -0.04em; line-height: 1; margin-bottom: 4px;">
                                            {{ $col['value'] }}
                                        </div>
                                        <div style="font-size: 7.5px; font-weight: 600; color: {{ $col['color'] }}; text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap; text-align: center;">
                                            {{ $col['label'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="width: 100%; display: flex; align-items: center; height: 68px;">
                                <p style="font-size: 11.5px; font-weight: 300; color: var(--color-secondary); opacity: 0.45;">
                                    Sin estudiantes registrados
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Semesters --}}
                    <div style="margin-top: auto;">
                        @if($period->semesters->count())
                            <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                @foreach($period->semesters as $semester)
                                    <span style="font-size: 9.5px; font-weight: 400; padding: 2px 8px; border-radius: 4px; color: var(--color-secondary); border: 1px solid var(--color-border-hover); letter-spacing: 0.02em;">
                                        {{ $semester->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

                {{-- Card Footer --}}
                <div style="padding: 10px 18px; border-top: 1px solid var(--color-border-hover); background-color: var(--color-card-bg); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;">

                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="font-size: 11px; color: var(--color-secondary); font-weight: 300;">
                            <span style="color: var(--color-primary-2); font-weight: 500;">{{ $period->students_count }}</span> estudiantes
                        </span>
                        <span style="width: 3px; height: 3px; border-radius: 50%; background: var(--color-border-hover); display: inline-block; flex-shrink: 0;"></span>
                        <span style="font-size: 11px; color: var(--color-secondary); font-weight: 300;">
                            <span style="color: var(--color-primary-2); font-weight: 500;">{{ $period->files_count }}</span> docs
                        </span>
                    </div>

                    <div style="display: flex; gap: 3px;">
                        {{-- Editar --}}
                        <button wire:click.prevent="editPeriod({{ $period->id }})" title="Editar periodo"
                                style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid transparent; background: transparent; color: var(--color-icon); cursor: pointer; transition: color 0.15s, border-color 0.15s;"
                                onmouseover="this.style.color='var(--color-icon-hover)'; this.style.backgroundColor='var(--color-icon-bg-hover)'"
                                onmouseout="this.style.color='var(--color-icon)'; this.style.backgroundColor='transparent'">
                            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>

                        {{-- Eliminar (solo inactivos) --}}
                        @if(!$period->is_active)
                            <button wire:click.prevent="confirmDelete({{ $period->id }})" title="Eliminar periodo"
                                    style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid transparent; background: transparent; color: var(--color-icon); cursor: pointer; transition: color 0.15s, border-color 0.15s;"
                                    onmouseover="this.style.color='rgb(248,113,113)'; this.style.backgroundColor='rgba(239,68,68,0.1)'"
                                    onmouseout="this.style.color='var(--color-icon)'; this.style.backgroundColor='transparent'">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        </a>
    @endforeach
</div>

{{-- Paginación --}}
<div class="mt-6">
    {{ $periods->links() }}
</div>