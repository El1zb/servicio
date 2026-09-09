{{-- Buscador: vive en el topbar en desktop (@push) y en el botón de la
     barra superior móvil (mismo stack, ver sidebar.blade.php). Mismo
     wire:model en ambos casos. --}}
@push('topbar-search')
    <div class="topbar-search-input-wrap">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16.6725 16.6412L21 21"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
        </svg>
        <input type="text" placeholder="Buscar carreras..." value="{{ $search }}" oninput="topbarSearchInput(this.value)" class="topbar-search-input"/>
    </div>
@endpush
