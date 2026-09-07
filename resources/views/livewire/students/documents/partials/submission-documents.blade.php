{{-- Buscador: en desktop vive en el topbar, en mobile se queda acá. --}}
@push('topbar-search')
    <div class="topbar-search-input-wrap">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.6725 16.6412L21 21"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
        </svg>
        <input type="text" placeholder="Buscar documento..." value="{{ $searchDocuments }}" oninput="topbarSearchInput(this.value, 'searchDocuments')" class="topbar-search-input"/>
    </div>
@endpush

{{-- Filtro de estado: junto al título de la página. Va empujado al layout
     (fuera del wire:id del componente), así que $wire no está disponible
     ahí — por eso, a diferencia del resto de mis selects, este habla con
     el componente vía window.Livewire.first() igual que los demás filtros
     de header-filters ya establecidos (revision-tab.blade.php, filters.blade.php). --}}
@php
    $statusFilterLabels = [
        ''            => 'Todos los estados',
        'pendiente'   => 'Por entregar',
        'en_revision' => 'En revisión',
        'aprobado'    => 'Aprobados',
        'rechazado'   => 'Rechazados',
        'vencido'     => 'Vencidos',
    ];
@endphp

@push('header-filters')
    <div class="header-filter-dropdown"
         x-data="{ open: false, value: '{{ $statusFilter }}', label: '{{ $statusFilterLabels[$statusFilter] ?? 'Todos los estados' }}' }"
         @click.outside="open = false">
        <button type="button" class="header-filter-select" @click="open = !open">
            <span x-text="label"></span>
            <svg class="header-filter-select-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div class="header-filter-dropdown-panel" x-show="open" x-cloak x-transition>
            @foreach($statusFilterLabels as $value => $label)
                <button type="button" class="header-filter-dropdown-option" :class="{ 'is-selected': value === '{{ $value }}' }"
                        @click="value = '{{ $value }}'; label = '{{ $label }}'; open = false; window.Livewire.first().set('statusFilter', '{{ $value }}')">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>
@endpush

<div class="space-y-3">

    {{-- Respaldo mobile: buscador y filtro. --}}
    <div class="lg:hidden flex flex-col gap-3">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-secondary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.6725 16.6412L21 21"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.400ms="searchDocuments"
                   placeholder="Buscar documento..."
                   class="app-input w-full" style="padding-left: 40px;">
        </div>

        <x-select wire-model="statusFilter" :value="$statusFilter"
            :options="['' => 'Todos los estados', 'pendiente' => 'Por entregar', 'en_revision' => 'En revisión', 'aprobado' => 'Aprobados', 'rechazado' => 'Rechazados', 'vencido' => 'Vencidos']"
            placeholder="Todos los estados" />
    </div>

    <div class="px-1">
        <p class="font-bold text-[var(--color-primary-2)] truncate" style="font-size: 20px;">Por entregar</p>
    </div>

    @if($submissionDocuments->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($submissionDocuments as $item)
                @php
                    $document  = $item['document'];
                    $isExpired = $item['isExpired'];
                    $hasFile   = $item['hasFile'];
                    $limitDate = $item['limitDate'];

                    $canUpload  = $this->canUploadFile($document);
                    $adminFiles = $this->getFileToDisplay($document);

                    $statusConfig = [
                        'revisado'    => ['label' => 'Aprobado',    'class' => 'status-badge--approved'],
                        'rechazado'   => ['label' => 'Rechazado',   'class' => 'status-badge--rejected'],
                        'en_revision' => ['label' => 'En revisión', 'class' => 'status-badge--review'],
                    ];

                    if ($hasFile) {
                        $badge = $statusConfig[$document->status] ?? ['label' => 'Sin entregar', 'class' => 'status-badge--pending'];
                    } elseif ($isExpired) {
                        $badge = ['label' => 'Vencido', 'class' => 'status-badge--expired'];
                    } else {
                        $badge = ['label' => 'Sin entregar', 'class' => 'status-badge--pending'];
                    }
                @endphp

                <div wire:key="submission-doc-{{ $document->id }}" class="period-card group">
                    <div>
                        <div class="flex items-center justify-end mb-2">
                            <span class="status-badge {{ $badge['class'] }}">
                                <span class="status-badge-dot"></span>
                                {{ $badge['label'] }}
                            </span>
                        </div>

                        <p class="document-card-title">{{ $document->name }}</p>

                        <p class="stat-card-description mt-1" style="margin:0;">
                            @if($limitDate)
                                Límite: {{ $limitDate->locale('es')->isoFormat('DD MMM YYYY') }}
                            @else
                                Sin fecha límite
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-1 mt-auto">

                        {{-- Ver — un solo botón que abre el visor con SOLO los
                             archivos de este documento (los del admin si subió
                             algo, el mío si ya lo subí). --}}
                        @if(count($adminFiles) > 0 || ($canUpload && $document->student_file_path))
                            <button wire:click="openDocumentViewer({{ $document->id }})"
                                    title="Ver documento"
                                    class="w-7 h-7 flex items-center justify-center rounded-full flex-shrink-0
                                           text-[var(--color-icon)] bg-transparent cursor-pointer
                                           opacity-0 group-hover:opacity-100
                                           transition-all duration-150
                                           hover:text-[var(--color-icon-hover)] hover:bg-[var(--sidebar-color-hover)]">
                                <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
                                    <path d="M7.8557,3.65731A7.00442,7.00442,0,0,0,1,8.00054a7.58806,7.58806,0,0,0,7.14647,4.34215A7.00224,7.00224,0,0,0,15,8.00054,7.586,7.586,0,0,0,7.8557,3.65731M6.65709,10.94592a5.10784,5.10784,0,0,1-4.214-2.94538s.66446-2.58462,4.32923-3.03692A2.786,2.786,0,0,0,5.35939,6.187L8.14647,7.40715H4.97709a3.46976,3.46976,0,0,0-.05277.57616,3.34816,3.34816,0,0,0,1.73384,2.96154m2.84954.01938a3.3991,3.3991,0,0,0,.10768-5.90692,5.00551,5.00551,0,0,1,3.94155,2.94323s-.60307,2.44138-4.04923,2.96369"/>
                                </svg>
                            </button>
                        @endif

                        {{-- Subir — solo cuando todavía NO hay archivo. Nunca "reemplazar":
                             aprobado o en revisión no se tocan (ya está bien, o está
                             esperando veredicto); si fue rechazado, primero hay que
                             cancelar la entrega (lo que limpia el archivo y el estado)
                             y ahí sí reaparece este botón, siempre como "Subir". --}}
                        @if($canUpload && !$isExpired && !$hasFile)
                            <button type="button" wire:click="openUploadModal({{ $document->id }})"
                                    title="Subir archivo"
                                    class="w-7 h-7 flex items-center justify-center rounded-full flex-shrink-0
                                           text-[var(--color-icon)] bg-transparent cursor-pointer
                                           opacity-0 group-hover:opacity-100
                                           transition-all duration-150
                                           hover:text-[var(--color-icon-hover)] hover:bg-[var(--sidebar-color-hover)]">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 21H9C6.17157 21 4.75736 21 3.87868 20.1213C3 19.2426 3 17.8284 3 15M21 15C21 17.8284 21 19.2426 20.1213 20.1213C19.8215 20.4211 19.4594 20.6186 19 20.7487"/>
                                    <path d="M12 16V3M12 3L16 7.375M12 3L8 7.375"/>
                                </svg>
                            </button>
                        @endif

                        {{-- Cancelar entrega — solo si fue rechazado (para poder
                             corregir y reenviar). En revisión o aprobado no se toca. --}}
                        @if($canUpload && $hasFile && $document->status === 'rechazado' && !$isExpired)
                            <button type="button"
                                    wire:click="openCancelModal({{ $document->id }})"
                                    title="Cancelar entrega"
                                    class="w-7 h-7 flex items-center justify-center rounded-full flex-shrink-0
                                           text-red-400 bg-transparent cursor-pointer
                                           opacity-0 group-hover:opacity-100
                                           transition-all duration-150
                                           hover:bg-red-50 hover:text-red-600">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M936,120a12,12,0,1,1,12-12A12,12,0,0,1,936,120Zm0-22a10,10,0,1,0,10,10A10,10,0,0,0,936,98Zm4.706,14.706a0.951,0.951,0,0,1-1.345,0l-3.376-3.376-3.376,3.376a0.949,0.949,0,1,1-1.341-1.342l3.376-3.376-3.376-3.376a0.949,0.949,0,1,1,1.341-1.342l3.376,3.376,3.376-3.376a0.949,0.949,0,1,1,1.342,1.342l-3.376,3.376,3.376,3.376A0.95,0.95,0,0,1,940.706,112.706Z" transform="translate(-924 -96)"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @elseif($searchDocuments !== '' || $statusFilter !== '')
        <div class="text-center py-20">
            <svg class="w-14 h-14 mx-auto mb-4" style="color: var(--color-secondary);" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-bold mb-2" style="color: var(--color-primary-2);">
                No se encontraron documentos
            </h3>
            <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--color-secondary);">
                Ajusta el buscador o los filtros para ver otros resultados.
            </p>
        </div>
    @else
        <div class="text-center py-20">
            <svg class="w-14 h-14 mx-auto mb-4" style="color: var(--color-secondary);" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-bold mb-2" style="color: var(--color-primary-2);">
                Aún no tienes documentos asignados
            </h3>
            <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--color-secondary);">
                Cuando el administrador configure documentos para tu periodo, aparecerán aquí.
            </p>
        </div>
    @endif
</div>
