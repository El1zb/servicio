{{-- resources/views/components/layouts/period-detail.blade.php --}}
<?php
$period = \App\Models\Period::with(['files', 'semesters'])->findOrFail(request()->route('id'));

$tabs = [
    'periods.students'  => ['label' => 'Gestión de Estudiantes', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
    'periods.documents' => ['label' => 'Documentos Base',        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
    'periods.revision'  => ['label' => 'Revisión de Documentos', 'icon' => 'M12 8v4m0 4h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h7l7 7v9a2 2 0 01-2 2z'],
];
?>

<x-layouts.app>  {{-- ← esto agrega el HTML base, head, body, header de flux, etc. --}}

    <div class="space-y-8 min-h-screen p-6">

        {{-- Header --}}
        <div class="w-full mb-8 rounded-xl shadow-sm p-6"
            style="background-color: var(--period-detail-bg);">

            <a wire:navigate href="{{ route('dashboard') }}"
                class="flex items-center gap-2 mb-6 transition-all duration-200 group"
                style="color: var(--period-detail-text-secondary);">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="font-medium" style="color: var(--period-detail-text-primary);">Volver a periodos</span>
            </a>

            <div class="flex items-start justify-between w-full">
                <div class="flex-1">
                    <x-auth-header
                        title="{{ $period->name }}"
                        description="{{ \Carbon\Carbon::parse($period->start_date)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($period->end_date)->translatedFormat('d M Y') }}"
                        :center="false"
                    />
                </div>

                @if($period->is_active)
                    <div class="backdrop-blur-sm px-5 py-2.5 rounded-full border"
                        style="border-color: var(--period-detail-status-approved-border);
                               background-color: var(--period-detail-status-approved-icon-bg);">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                    style="background-color: var(--period-detail-status-approved-icon-color);"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2"
                                    style="background-color: var(--period-detail-status-approved-icon-color);"></span>
                            </span>
                            <span class="font-semibold text-sm"
                                style="color: var(--period-detail-status-approved-icon-color);">Activo</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        @include('livewire.dashboard.period.partials.stats-bar', ['period' => $period])

        {{-- Tabs --}}
        <div class="w-full mb-6">
            <div class="backdrop-blur-xl rounded-2xl p-2 flex flex-wrap gap-2"
                 style="background-color: var(--period-detail-bg);">
                @foreach($tabs as $route => $data)
                    <a wire:navigate
                       href="{{ route($route, $period->id) }}"
                       class="flex items-center justify-center gap-2 px-4 sm:px-6 py-3 font-medium rounded-xl transition-all duration-200 flex-1 sm:flex-none"
                       style="{{ request()->routeIs($route)
                           ? 'background-color: var(--period-detail-btn-primary-bg); color: var(--period-detail-btn-primary-text); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3);'
                           : 'color: var(--period-detail-text-secondary);' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $data['icon'] }}"/>
                        </svg>
                        <span class="text-sm sm:text-base truncate">{{ $data['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Componente activo --}}
        <div class="w-full">
            {{ $slot }}
        </div>

    </div>

</x-layouts.app>