{{-- Navigation Tabs --}}
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