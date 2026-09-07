{{-- Buscador: en desktop vive en el topbar (@push), en mobile se queda acá
     (el topbar no se muestra ahí). Mismo wire:model en ambos casos. --}}
@push('topbar-search')
    <div class="topbar-search-input-wrap">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16.6725 16.6412L21 21"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
        </svg>
        <input type="text" placeholder="Buscar campus..." value="{{ $search }}" oninput="topbarSearchInput(this.value)" class="topbar-search-input"/>
    </div>
@endpush

<div class="lg:hidden rounded-xl p-5">
    <div class="relative max-w-2xl">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-[var(--color-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16.6725 16.6412L21 21"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
            </svg>
        </div>
        <input
            type="text"
            placeholder="Buscar campus..."
            wire:model.live="search"
            class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]
                   bg-[var(--color-card-bg)] text-[var(--color-primary-2)] border border-[var(--color-border-hover)]
                   placeholder:text-[var(--color-secondary)]"
        />
    </div>
</div>