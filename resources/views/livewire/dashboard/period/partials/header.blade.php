{{-- Header --}}
<div class="w-full mb-8 rounded-xl shadow-sm p-6"
    style="background-color: var(--period-detail-bg);">

    <button
        wire:click="goBack"
        class="flex items-center gap-2 text-gray-400 hover:text-white mb-6 transition-all duration-200 group"
        style="color: var(--period-detail-text-secondary);"
    >
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 19l-7-7 7-7" />
        </svg>
        <span class="font-medium" style="color: var(--period-detail-text-primary);">Volver a periodos</span>
    </button>

    <div class="flex items-start justify-between w-full">
        <div class="flex-1">
            <x-auth-header
                title="{{ $period->name }}"
                description="{{ \Carbon\Carbon::parse($period->start_date)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($period->end_date)->translatedFormat('d M Y') }}"
                :center="false"
            />
        </div>

        @if($period->is_active)
            <div class="bg-emerald-500/10 backdrop-blur-sm px-5 py-2.5 rounded-full border"
                style="border-color: var(--period-detail-status-approved-border);
                       background-color: var(--period-detail-status-approved-icon-bg);">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full"
                            style="background-color: var(--period-detail-status-approved-icon-bg); opacity: 0.75;"></span>
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