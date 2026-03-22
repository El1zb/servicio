{{-- Header --}}
<div class="w-full mb-8 rounded-xl shadow-sm p-6"
    style="background-color: var(--color-card-bg);">

    <a wire:navigate href="{{ route('dashboard') }}"
        class="flex items-center gap-2 mb-6 transition-all duration-200 group"
        style="color: var(--color-secondary);">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="font-medium" style="color: var(--color-primary-2);">Volver a periodos</span>
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
            <div class="px-5 py-2.5 rounded-full border"
                style="border-color: rgba(16, 185, 129, 0.3);
                       background-color: rgba(16, 185, 129, 0.1);">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full"
                            style="background-color: rgb(52, 211, 153); opacity: 0.75;"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2"
                            style="background-color: rgb(52, 211, 153);"></span>
                    </span>
                    <span class="font-semibold text-sm"
                        style="color: rgb(52, 211, 153);">Activo</span>
                </div>
            </div>
        @endif
    </div>
</div>