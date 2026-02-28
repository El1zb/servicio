<div class="rounded-xl p-5">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">

        {{-- Search --}}
        <div class="md:col-span-6 lg:col-span-7 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     style="color: var(--index-text-primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" placeholder="Buscar por nombre..." wire:model.live="search"
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg text-sm transition-all"
                   style="background-color: var(--index-card-bg); color: var(--index-text-primary); border: 1px solid var(--index-border);"/>
        </div>

        {{-- Status filter --}}
        <div class="md:col-span-3 lg:col-span-2">
            <flux:select wire:model.live="statusFilter"
                         class="w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-all"
                         style="background-color: var(--index-card-bg); color: var(--index-text-primary); border: 1px solid var(--index-border);">
                <option value="all">Todos los estados</option>
                <option value="active">Solo activos</option>
                <option value="inactive">Solo inactivos</option>
            </flux:select>
        </div>

        {{-- Sort --}}
        <div class="md:col-span-3 lg:col-span-3">
            <flux:select wire:model.live="sortBy"
                         class="w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-all"
                         style="background-color: var(--index-card-bg); color: var(--index-text-primary); border: 1px solid var(--index-border);">
                <option value="recent">Más recientes</option>
                <option value="oldest">Más antiguos</option>
                <option value="name">Nombre (A–Z)</option>
            </flux:select>
        </div>

    </div>
</div>