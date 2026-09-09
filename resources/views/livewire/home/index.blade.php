<div class="space-y-8 p-6">
    @include('livewire.dashboard.index.partials.stats')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">

        <div class="lg:col-span-2 flex flex-col gap-5">

            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <p class="dashboard-panel-title">Estudiantes por Campus</p>
                    <span class="dashboard-panel-meta">{{ $stats['total_students'] }} en total</span>
                </div>

                @if($studentsByCampus->isEmpty())
                    <p class="dashboard-panel-empty">Aún no hay estudiantes registrados.</p>
                @else
                    @php $maxValue = $studentsByCampus->max('value') ?: 1; @endphp
                    <div class="bar-chart">
                        @foreach($studentsByCampus as $bar)
                            <div class="bar-chart-col">
                                <div class="bar-chart-track">
                                    <div class="bar-chart-bar"
                                         style="height: {{ $bar['value'] > 0 ? max(8, round($bar['value'] / $maxValue * 100)) : 4 }}%"
                                         title="{{ $bar['label'] }}: {{ $bar['value'] }} estudiantes">
                                        <span class="bar-chart-value">{{ $bar['value'] }}</span>
                                    </div>
                                </div>
                                <span class="bar-chart-label">{{ $bar['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <p class="dashboard-panel-title">Estudiantes por Estado</p>
                </div>

                <div class="donut-chart-row flex-col md:flex-row w-full">
                    <div class="donut-chart-wrap w-full max-w-[220px] aspect-square mx-auto md:w-[140px] md:h-[140px] md:max-w-none md:mx-0">
                        <svg class="donut-chart" viewBox="0 0 36 36">
                            <circle class="donut-chart-track" cx="18" cy="18" r="15.915" fill="none"/>
                            @php $offset = 25; @endphp
                            @foreach($studentsByStatus as $segment)
                                <circle cx="18" cy="18" r="15.915" fill="none"
                                        stroke="{{ $segment['color'] }}"
                                        stroke-width="3.8"
                                        stroke-dasharray="{{ $segment['percent'] }} {{ 100 - $segment['percent'] }}"
                                        stroke-dashoffset="{{ $offset }}"
                                        title="{{ $segment['label'] }}: {{ $segment['value'] }} ({{ $segment['percent'] }}%)"/>
                                @php $offset -= $segment['percent']; @endphp
                            @endforeach
                        </svg>
                        <div class="donut-chart-center">
                            <span class="donut-chart-total">{{ $stats['total_students'] }}</span>
                            <span class="donut-chart-total-label">estudiantes</span>
                        </div>
                    </div>

                    <div class="donut-chart-legend grid grid-cols-2 gap-x-4 gap-y-2 w-full self-stretch md:flex md:flex-col md:gap-2.5 md:w-auto md:self-auto">
                        @foreach($studentsByStatus as $segment)
                            <div class="donut-chart-legend-item">
                                <span class="donut-chart-legend-dot" style="background-color: {{ $segment['color'] }};"></span>
                                <span class="donut-chart-legend-label">{{ $segment['label'] }}</span>
                                <span class="donut-chart-legend-value">{{ $segment['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        <div class="flex flex-col gap-5">

            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <p class="dashboard-panel-title">Próximos Documentos a Revisar</p>
                </div>

                @if($upcomingFiles->isEmpty())
                    <p class="dashboard-panel-empty">No hay documentos próximos a vencer.</p>
                @else
                    <div class="task-list w-full">
                        @foreach($upcomingFiles as $item)
                            <div class="task-item">
                                <span class="task-checkbox"></span>
                                <div class="task-content">
                                    <span class="task-date-badge">{{ $item['day'] }} {{ $item['month'] }}</span>
                                    <p class="task-title">{{ $item['name'] }}</p>
                                    <p class="task-meta">{{ $item['period'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="dashboard-panel">
                <div class="dashboard-panel-header">
                    <p class="dashboard-panel-title">Estudiantes por Carrera</p>
                </div>

                @if($studentsByCareer->isEmpty())
                    <p class="dashboard-panel-empty">Aún no hay estudiantes registrados.</p>
                @else
                    @php $maxCareerValue = $studentsByCareer->max('value') ?: 1; @endphp
                    <div class="hbar-list">
                        @foreach($studentsByCareer as $row)
                            <div class="hbar-row">
                                <span class="hbar-label">{{ $row['label'] }}</span>
                                <div class="hbar-track">
                                    <div class="hbar-fill" style="width: {{ max(6, round($row['value'] / $maxCareerValue * 100)) }}%"></div>
                                </div>
                                <span class="hbar-value">{{ $row['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
