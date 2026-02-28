{{-- Status Badge --}}
<div style="margin-bottom: 10px;">
    <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 9.5px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: {{ $period->is_active ? 'var(--index-status-approved-icon)' : 'var(--index-text-secondary)' }};">
        <span style="width: 6px; height: 6px; border-radius: 50%; display: inline-block; flex-shrink: 0;
                     background-color: {{ $period->is_active ? 'var(--index-status-approved-icon)' : 'var(--index-text-secondary)' }};
                     {{ $period->is_active ? 'box-shadow: 0 0 6px var(--index-status-approved-icon);' : 'opacity:0.4;' }}">
        </span>
        {{ $period->is_active ? 'Activo' : 'Inactivo' }}
    </span>
</div>

{{-- Name --}}
<h2 style="font-size: 20px; font-weight: 500; color: var(--index-text-primary); letter-spacing: -0.03em; line-height: 1.15; margin-bottom: 5px;">
    {{ $period->name }}
</h2>

{{-- Date range --}}
<p style="font-size: 11px; font-weight: 300; color: var(--index-text-secondary); margin-bottom: 16px;">
    {{ $period->startFormatted }} — {{ $period->endFormatted }}
</p>

{{-- Student Stats --}}
<div style="margin-bottom: 14px; display: flex; align-items: center;">
    @if($period->hasStudents)
        @php
            $statCols = [
                ['value' => $period->approved_students_count, 'label' => 'Aprobados',  'color' => 'var(--index-status-approved-icon)', 'size' => '20px', 'flex' => '1',   'transform' => ''],
                ['value' => $period->pending_students_count,  'label' => 'Pendientes', 'color' => 'var(--index-status-pending)',       'size' => '30px', 'flex' => '1.4', 'transform' => 'translateY(-6px)'],
                ['value' => $period->rejected_students_count, 'label' => 'Rechazados', 'color' => 'var(--index-status-rejected-icon)', 'size' => '20px', 'flex' => '1',   'transform' => ''],
            ];
        @endphp
        <div style="display: flex; gap: 5px; width: 100%; align-items: center;">
            @foreach($statCols as $col)
                <div style="flex: {{ $col['flex'] }}; min-width: 0; padding: 8px 6px; display: flex; flex-direction: column; justify-content: center; align-items: center; {{ $col['transform'] ? 'transform: ' . $col['transform'] . ';' : '' }}">
                    <div style="font-size: {{ $col['size'] }}; font-weight: 700; color: var(--index-text-primary); letter-spacing: -0.04em; line-height: 1; margin-bottom: 4px;">
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
            <p style="font-size: 11.5px; font-weight: 300; color: var(--index-text-secondary); opacity: 0.45;">
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
                <span style="font-size: 9.5px; font-weight: 400; padding: 2px 8px; border-radius: 4px; color: var(--index-text-secondary); border: 1px solid var(--index-border); letter-spacing: 0.02em;">
                    {{ $semester->name }}
                </span>
            @endforeach
        </div>
    @endif
</div>