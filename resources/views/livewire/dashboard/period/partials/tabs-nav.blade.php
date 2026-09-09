{{-- Switcher entre Revisión / Documentos Base / Gestión de Estudiantes:
     en el topbar (con respaldo en mobile, donde el topbar no se muestra). --}}
@php
    $tabOptions = collect($tabs)->map(fn($data, $route) => [
        'label'  => $data['label'],
        'href'   => route($route, $period->id),
        'active' => request()->routeIs($route),
    ])->values()->all();

    // Mobile: etiquetas cortas (menos ancho, ver overflow-x-auto de abajo).
    $tabOptionsMobile = collect($tabs)->map(fn($data, $route) => [
        'label'  => $data['shortLabel'],
        'href'   => route($route, $period->id),
        'active' => request()->routeIs($route),
    ])->values()->all();

    // "Nuevo Documento": solo ícono, a la izquierda del switcher — nada más
    // en la pestaña Documentos, donde vive openCreateDocumentModal(). Solo
    // mobile: en escritorio el botón sigue siendo el de topbar-actions de
    // siempre (ver documents-tab.blade.php).
    $showNewDocumentButton = request()->routeIs('periods.documents');
@endphp

@push('topbar-switcher')
    <x-switcher :options="$tabOptions" />
@endpush

{{-- Con botón + (Documentos): justify-between lo pega a la izquierda y deja
     el switcher a la derecha. Sin botón (Revisión, Gestión): justify-end, el
     switcher solo, pegado a la derecha igual que siempre. --}}
<div class="lg:hidden flex items-center gap-2 {{ $showNewDocumentButton ? 'justify-between' : 'justify-end' }}">
    @if($showNewDocumentButton)
        {{-- tabs-nav.blade.php se incluye en el layout, fuera del wire:id del
             componente de la página → onclick + topbarAction(), no wire:click. --}}
        <button type="button" onclick="topbarAction('openCreateDocumentModal')" class="btn-primary !w-10 !h-10 !p-0 flex-shrink-0" aria-label="Nuevo Documento">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        </button>
    @endif

    {{-- overflow-x-auto: aunque las etiquetas mobile son cortas, el selector
         igual scrollea dentro de su propio ancho en vez de desbordar la
         página si no caben. --}}
    <div class="overflow-x-auto scrollbar-none flex justify-end min-w-0">
        <x-switcher :options="$tabOptionsMobile" class="catalog-switcher catalog-switcher--mobile w-max" />
    </div>
</div>
