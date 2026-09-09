<!DOCTYPE html>
@php
    $appearance = request()->cookie('appearance', 'system');
    $htmlClass = $appearance === 'dark' ? 'dark' : '';
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $htmlClass }}">
<head>
    <!-- PRIMER tag, antes de cualquier CSS -->
    <script>
        (function() {
            var a = localStorage.getItem('flux.appearance') || 'system';
            var dark = a === 'dark' || (a === 'system' && matchMedia('(prefers-color-scheme:dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
            document.documentElement.style.colorScheme = dark ? 'dark' : 'light';

            // Intercepta replaceHtmlAttributes de Livewire antes de que borre 'dark'
            // o 'sidebar-collapsed' de <html> en cada navegación (wire:navigate
            // sincroniza los atributos de <html> por diff, llamando
            // setAttribute — a diferencia de <body>, que Livewire reemplaza
            // entero como nodo nuevo; por eso el estado del sidebar en sí
            // -colapsado/expandido, listeners del tooltip- se protege con
            // la directiva "persist" (ver el Blade), no aquí).
            var _setAttribute = Element.prototype.setAttribute;
            Element.prototype.setAttribute = function(name, value) {
                if (name === 'class' && this === document.documentElement) {
                    var a = localStorage.getItem('flux.appearance') || 'system';
                    var dark = a === 'dark' || (a === 'system' && matchMedia('(prefers-color-scheme:dark)').matches);
                    if (dark && !value.includes('dark')) {
                        value = (value + ' dark').trim();
                    }
                    var collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                    if (collapsed && !value.includes('sidebar-collapsed')) {
                        value = (value + ' sidebar-collapsed').trim();
                    }
                }
                _setAttribute.call(this, name, value);
            };
        })();
    </script>

    @include('partials.head')

    <script>
        if (localStorage.getItem('sidebarCollapsed') === 'true')
            document.documentElement.classList.add('sidebar-collapsed');
    </script>
</head>
<body class="min-h-screen">

<div class="app-shell">

{{-- La directiva "persist" saca este nodo del DOM ANTES de que wire:navigate
     reemplace <body> por completo, y lo vuelve a insertar tal cual después
     (mismo nodo, mismas clases JS -collapsed-, mismos listeners). Así el
     estado de colapsado/expandido y los listeners del tooltip sobreviven
     intactos a cada navegación, sin depender de sincronizar clases a mano. --}}
@persist('sidebar')
<aside class="sidebar-container bg-[var(--sidebar-color-bg)]" id="sidebar">

    <div class="sidebar-content">

        <div class="logo-container">
            <div class="logo-text-block">
                <span class="logo-app-name">Servicio Social</span>
                <span class="logo-app-sub">ITSCO</span>
            </div>

            <button class="sidebar-toggle-btn" onclick="toggleSidebarCollapse()" data-tooltip="Contraer barra lateral">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <defs>
                        <clipPath id="sidebarToggleClip">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                        </clipPath>
                    </defs>
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <rect x="3" y="3" width="6" height="18" fill="currentColor" clip-path="url(#sidebarToggleClip)"/>
                    <path d="M9 3v18"/>
                </svg>
            </button>
        </div>

        <nav class="py-4">
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   wire:navigate>
                    <svg class="nav-item-icon nav-item-icon-outline" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <polyline points="21 12 12 3 3 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                        <path d="M19,10V20.3a.77.77,0,0,1-.83.7H14.3V14.1H9.7V21H5.83A.77.77,0,0,1,5,20.3V10" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                    </svg>
                    <svg class="nav-item-icon nav-item-icon-filled" width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21.71,12.71a1,1,0,0,1-1.42,0L20,12.42V20.3A1.77,1.77,0,0,1,18.17,22H16a1,1,0,0,1-1-1V15.1a1,1,0,0,0-1-1H10a1,1,0,0,0-1,1V21a1,1,0,0,1-1,1H5.83A1.77,1.77,0,0,1,4,20.3V12.42l-.29.29a1,1,0,0,1-1.42,0,1,1,0,0,1,0-1.42l9-9a1,1,0,0,1,1.42,0l9,9A1,1,0,0,1,21.71,12.71Z"/>
                    </svg>
                    <span class="nav-item-text">{{ __('Inicio') }}</span>
                </a>

                <a href="{{ route('periods') }}"
                   class="nav-item {{ request()->routeIs('periods') ? 'active' : '' }}"
                   wire:navigate>
                    <svg class="nav-item-icon nav-item-icon-outline" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V12Z" stroke-width="1.5"/>
                        <path d="M7 4V2.5" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M17 4V2.5" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M2.5 9H21.5" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <svg class="nav-item-icon nav-item-icon-filled" width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 14V12C22 11.161 22 10.4153 21.9871 9.75H2.0129C2 10.4153 2 11.161 2 12V14C2 17.7712 2 19.6569 3.17157 20.8284C4.34315 22 6.22876 22 10 22H14C17.7712 22 19.6569 22 20.8284 20.8284C22 19.6569 22 17.7712 22 14Z"/>
                        <path d="M7.75 2.5C7.75 2.08579 7.41421 1.75 7 1.75C6.58579 1.75 6.25 2.08579 6.25 2.5V4.07926C4.81067 4.19451 3.86577 4.47737 3.17157 5.17157C2.47737 5.86577 2.19451 6.81067 2.07926 8.25H21.9207C21.8055 6.81067 21.5226 5.86577 20.8284 5.17157C20.1342 4.47737 19.1893 4.19451 17.75 4.07926V2.5C17.75 2.08579 17.4142 1.75 17 1.75C16.5858 1.75 16.25 2.08579 16.25 2.5V4.0129C15.5847 4 14.839 4 14 4H10C9.16097 4 8.41527 4 7.75 4.0129V2.5Z"/>
                    </svg>
                    <span class="nav-item-text">{{ __('Periodo') }}</span>
                </a>

                <a href="{{ route('campuses.index') }}"
                   class="nav-item {{ request()->routeIs('campuses.index', 'careers.index', 'semesters.index') ? 'active' : '' }}"
                   data-match-paths="{{ route('campuses.index', [], false) }},{{ route('careers.index', [], false) }},{{ route('semesters.index', [], false) }}"
                   wire:navigate>
                    <svg class="nav-item-icon nav-item-icon-outline" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 3L3 8L12 13L21 8L12 3Z" stroke-width="1.5" stroke-linejoin="round"/>
                        <path d="M3 12L12 17L21 12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 16L12 21L21 16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <svg class="nav-item-icon nav-item-icon-filled" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L2 8L12 14L22 8L12 2Z"/>
                        <path d="M2 12.5L12 18.5L22 12.5L20.5 11.5L12 16.5L3.5 11.5L2 12.5Z"/>
                        <path d="M2 16.5L12 22.5L22 16.5L20.5 15.5L12 20.5L3.5 15.5L2 16.5Z"/>
                    </svg>
                    <span class="nav-item-text">{{ __('Catálogos') }}</span>
                </a>
            @else
                <a href="{{ route('student-documents.index') }}"
                   class="nav-item {{ request()->routeIs('student-documents.index') ? 'active' : '' }}"
                   wire:navigate>
                    <svg class="nav-item-icon" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span class="nav-item-text">{{ __('Mis Documentos') }}</span>
                </a>
            @endif
        </nav>

        <div class="user-menu-container">
            <a href="{{ route('settings.profile') }}"
               class="nav-item {{ request()->routeIs('settings.*', 'admin.create-admin', 'admin.trash') ? 'active' : '' }}"
               data-match-paths="{{ route('settings.profile', [], false) }},{{ route('admin.create-admin', [], false) }},{{ route('admin.trash', [], false) }}"
               wire:navigate>
                <svg class="nav-item-icon nav-item-icon-outline" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M7.84308 3.80211C9.8718 2.6007 10.8862 2 12 2C13.1138 2 14.1282 2.6007 16.1569 3.80211L16.8431 4.20846C18.8718 5.40987 19.8862 6.01057 20.4431 7C21 7.98943 21 9.19084 21 11.5937V12.4063C21 14.8092 21 16.0106 20.4431 17C19.8862 17.9894 18.8718 18.5901 16.8431 19.7915L16.1569 20.1979C14.1282 21.3993 13.1138 22 12 22C10.8862 22 9.8718 21.3993 7.84308 20.1979L7.15692 19.7915C5.1282 18.5901 4.11384 17.9894 3.55692 17C3 16.0106 3 14.8092 3 12.4063V11.5937C3 9.19084 3 7.98943 3.55692 7C4.11384 6.01057 5.1282 5.40987 7.15692 4.20846L7.84308 3.80211Z" stroke-width="1.5"/>
                    <circle cx="12" cy="12" r="3" stroke-width="1.5"/>
                </svg>
                <svg class="nav-item-icon nav-item-icon-filled" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.4277 2C11.3139 2 10.2995 2.6007 8.27081 3.80211L7.58466 4.20846C5.55594 5.40987 4.54158 6.01057 3.98466 7C3.42773 7.98943 3.42773 9.19084 3.42773 11.5937V12.4063C3.42773 14.8092 3.42773 16.0106 3.98466 17C4.54158 17.9894 5.55594 18.5901 7.58466 19.7915L8.27081 20.1979C10.2995 21.3993 11.3139 22 12.4277 22C13.5416 22 14.5559 21.3993 16.5847 20.1979L17.2708 19.7915C19.2995 18.5901 20.3139 17.9894 20.8708 17C21.4277 16.0106 21.4277 14.8092 21.4277 12.4063V11.5937C21.4277 9.19084 21.4277 7.98943 20.8708 7C20.3139 6.01057 19.2995 5.40987 17.2708 4.20846L16.5847 3.80211C14.5559 2.6007 13.5416 2 12.4277 2ZM8.67773 12C8.67773 9.92893 10.3567 8.25 12.4277 8.25C14.4988 8.25 16.1777 9.92893 16.1777 12C16.1777 14.0711 14.4988 15.75 12.4277 15.75C10.3567 15.75 8.67773 14.0711 8.67773 12Z"/>
                </svg>
                <span class="nav-item-text">{{ __('Configuración') }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item">
                    <svg class="nav-item-icon" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.95,17.5A2.853,2.853,0,0,1,0,14.75v-12A2.854,2.854,0,0,1,2.95,0h8.8a.75.75,0,0,1,0,1.5H2.95A1.362,1.362,0,0,0,1.5,2.75v12A1.363,1.363,0,0,0,2.95,16h8.8a.75.75,0,0,1,0,1.5Zm9.269-4.219a.751.751,0,0,1,0-1.061L14.939,9.5H5.75a.75.75,0,0,1,0-1.5h9.19L12.219,5.28A.75.75,0,1,1,13.28,4.22l4,4a.749.749,0,0,1,0,1.06l-4,4a.751.751,0,0,1-1.061,0Z" transform="translate(3.25 3.25)"/>
                    </svg>
                    <span class="nav-item-text">{{ __('Cerrar Sesión') }}</span>
                </button>
            </form>
        </div>

    </div>
</aside>
@endpersist

<div class="app-main-col">

@php
    // Mismo texto que el título real de cada página (su propio
    // <flux:heading>/x-auth-header), para que el topbar nunca diga algo
    // distinto de lo que ya se ve en el contenido.
    $sectionTitle = $title ?? match (true) {
        request()->routeIs('dashboard') => __('Inicio'),
        request()->routeIs('periods') => __('Periodos Académicos'),
        request()->routeIs('campuses.index') => __('Campus'),
        request()->routeIs('careers.index') => __('Carreras'),
        request()->routeIs('semesters.index') => __('Semestres'),
        request()->routeIs('periods.students') => __('Estudiantes del periodo'),
        request()->routeIs('periods.documents') => __('Documentos del periodo'),
        request()->routeIs('periods.revision') => __('Revisión del periodo'),
        // Solo se muestra una vez que el estudiante ya puede interactuar con
        // sus documentos (perfil aprobado); mientras tanto la pantalla de
        // estado (sin perfil / pendiente / rechazado) ocupa todo el foco.
        request()->routeIs('student-documents.index') => optional(auth()->user()->student)->status === 'aprobado' ? __('Mis Documentos') : null,
        default => __('Panel'),
    };
@endphp

<header class="app-topbar">
    <div class="app-topbar-search">
        @stack('topbar-search')
    </div>

    @stack('topbar-switcher')

    <div class="flex items-center gap-3">
        @unless(auth()->user()->hasRole('admin'))
            <livewire:students.notifications.bell />
        @endunless

        <div class="topbar-profile">
            <div class="user-avatar">{{ auth()->user()->initials() }}</div>
            <div class="user-details">
                <span class="user-name">{{ auth()->user()->shortName() }}</span>
                <span class="user-email">{{ auth()->user()->email }}</span>
            </div>
        </div>
    </div>
</header>

@php
    // Mismo stack que arriba (@stack('topbar-search')), leído sin
    // consumirlo para poder reusarlo acá dentro del botón de búsqueda
    // móvil, en vez de que cada página duplique otro buscador solo-mobile.
    $topbarSearchHtml = trim((string) $__env->yieldPushContent('topbar-search'));
@endphp

@if($sectionTitle)
<header class="app-topbar-mobile lg:hidden" id="mobileTopbar">
    @php
        $isSettingsRoute = request()->routeIs('settings.*', 'admin.create-admin', 'admin.trash');
    @endphp
    <div class="app-topbar-mobile-left {{ $isSettingsRoute ? 'app-topbar-mobile-left--switcher' : '' }}">
        @stack('topbar-mobile-back')
        {{-- Ajustes (General/Administradores/Papelera): el switcher va aquí
             mismo, en vez del título, pegado al lado derecho de este
             contenedor — mismo tamaño de topbar, solo mobile. --}}
        @if($isSettingsRoute)
            <x-settings-switcher class="catalog-switcher--mobile" x-show="!$store.trashSelection || $store.trashSelection.selected.length === 0" />
        @else
            <h1 class="app-topbar-mobile-title" x-show="!$store.trashSelection || $store.trashSelection.selected.length === 0">{{ $sectionTitle }}</h1>
        @endif
    </div>

    <div class="app-topbar-mobile-right">
        @unless(auth()->user()->hasRole('admin'))
            <livewire:students.notifications.bell />
        @endunless

        @stack('topbar-mobile-meta')

        @if($topbarSearchHtml !== '')
            <div class="app-topbar-mobile-search">{!! $topbarSearchHtml !!}</div>

            <button type="button" class="app-topbar-mobile-search-btn" onclick="toggleMobileTopbarSearch()" aria-label="{{ __('Buscar') }}">
                <svg class="app-topbar-mobile-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.6725 16.6412L21 21"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"/>
                </svg>
                <svg class="app-topbar-mobile-close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        @endif
    </div>
</header>
@endif

<main class="main-content">
    <div class="main-content-inner">
        @if($sectionTitle)
        <div class="main-content-header">
            <h1 class="main-content-title">{{ $sectionTitle }}</h1>

            <div class="main-content-toolbar">
                @stack('header-filters')
                @stack('topbar-actions')
            </div>
        </div>
        @endif

        {{ $slot }}
    </div>
</main>

</div><!-- /.app-main-col -->

</div><!-- /.app-shell -->

@include('partials.mobile-bottom-nav')

@persist('sidebar-tooltip')
<div id="customTooltip"></div>
@endpersist

<script data-navigate-once>
    // Sincroniza localStorage → cookie para que el servidor sepa el tema
    var _appearance = localStorage.getItem('flux.appearance') || 'system';
    document.cookie = 'appearance=' + _appearance + ';path=/;max-age=31536000;SameSite=Lax';

    // Cuando Flux cambie la apariencia, actualiza la cookie también
    document.addEventListener('flux-appearance-changed', function(e) {
        document.cookie = 'appearance=' + e.detail + ';path=/;max-age=31536000;SameSite=Lax';
    });

    function applyTheme() {
        var a = localStorage.getItem('flux.appearance') || 'system';
        var dark = a === 'dark' || (a === 'system' && matchMedia('(prefers-color-scheme:dark)').matches);
        document.documentElement.classList.toggle('dark', dark);
        document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
    }

    var sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

    function updateSidebarToggleTooltip(collapsed) {
        const btn = document.querySelector('.sidebar-toggle-btn');
        btn?.setAttribute('data-tooltip', collapsed ? 'Expandir barra lateral' : 'Contraer barra lateral');
    }
    updateSidebarToggleTooltip(sidebarCollapsed);

    // El buscador y el botón "Nuevo X" de cada página se empujan al topbar
    // (directivas push/stack de Blade, ver el archivo), que vive en este
    // layout — fuera del propio div del componente Livewire de la página.
    // wire:model/wire:click solo enlazan con elementos DENTRO del wire:id
    // del componente, así que aquí no hacen nada; en su lugar hablamos con
    // el componente por JS usando Livewire.first() (siempre hay un solo
    // componente por página).
    let topbarSearchTimer = null;
    function topbarSearchInput(value, property = 'search') {
        clearTimeout(topbarSearchTimer);
        topbarSearchTimer = setTimeout(function () {
            window.Livewire?.first()?.set(property, value);
        }, 300);
    }

    function topbarAction(method) {
        window.Livewire?.first()?.call(method);
    }

    // Selección múltiple de Papelera (mobile): vive en un Alpine store
    // porque las cards (dentro del componente Livewire) y los botones de
    // esta topbar (fuera de él, en este layout) no comparten árbol de
    // x-data — solo así ambos leen/escriben el mismo estado.
    //
    // Se registra tanto en 'alpine:init' como de inmediato si Alpine ya
    // arrancó: este script trae "data-navigate-once", así que la PRIMERA
    // vez que aparece en el DOM puede ser vía wire:navigate (ej. justo
    // después del login, que redirige por soft-navigate desde un layout
    // que no incluye este script) — en ese caso Alpine ya arrancó en la
    // página anterior y 'alpine:init' no vuelve a disparar nunca, dejando
    // el store sin crear (y con él, $store.trashSelection undefined en
    // el título mobile hasta el próximo reload real).
    function registerTrashSelectionStore() {
        if (Alpine.store('trashSelection')) return;
        Alpine.store('trashSelection', {
            selected: [],
            confirmingPurge: false,
            toggle(key) {
                this.selected = this.selected.includes(key)
                    ? this.selected.filter(k => k !== key)
                    : [...this.selected, key];
            },
            cancel() {
                this.selected = [];
                this.confirmingPurge = false;
            },
        });
    }

    if (window.Alpine) {
        registerTrashSelectionStore();
    } else {
        document.addEventListener('alpine:init', registerTrashSelectionStore);
    }

    function trashSelectionRestore() {
        const store = Alpine.store('trashSelection');
        window.Livewire?.first()?.call('restoreSelected', store.selected).then(() => { store.selected = []; });
    }

    function trashSelectionPurge() {
        const store = Alpine.store('trashSelection');
        window.Livewire?.first()?.call('purgeSelected', store.selected).then(() => {
            store.selected = [];
            store.confirmingPurge = false;
        });
    }

    function updateMainContentPadding() {
        const el = document.querySelector('.main-content');
        const inner = el?.querySelector('.main-content-inner');
        if (!el || !inner) return;
        const currentPadding = parseFloat(getComputedStyle(inner).paddingBottom) || 0;
        const contentHeight = inner.scrollHeight - currentPadding;
        el.classList.toggle('overflowing', contentHeight > el.clientHeight + 1);
    }

    function initMainContentPadding() {
        const el = document.querySelector('.main-content');
        if (!el) return;
        updateMainContentPadding();
        new ResizeObserver(updateMainContentPadding).observe(el);
        new MutationObserver(updateMainContentPadding).observe(el, { childList: true, subtree: true });
    }

    initMainContentPadding();
    window.addEventListener('load', updateMainContentPadding);
    window.addEventListener('resize', updateMainContentPadding);

    // El sidebar ahora persiste entre navegaciones, así que el servidor ya no puede
    // marcar el nav-item activo en cada navegación (ese nodo nunca se
    // vuelve a renderizar). Lo hacemos por JS comparando contra la URL.
    function updateActiveNavItem() {
        document.querySelectorAll('#sidebar .nav-item').forEach(function (item) {
            try {
                if (item.dataset.matchPaths) {
                    const paths = item.dataset.matchPaths.split(',');
                    item.classList.toggle('active', paths.includes(window.location.pathname));
                    return;
                }
                const url = new URL(item.href, window.location.origin);
                item.classList.toggle('active', url.pathname === window.location.pathname);
            } catch (e) {}
        });
    }

    // Botón de búsqueda de la barra superior móvil: alterna entre mostrar
    // el título de la sección o el buscador (mismo contenido pujado por la
    // página a 'topbar-search', ver arriba) dentro de la misma barra.
    function toggleMobileTopbarSearch() {
        const bar = document.getElementById('mobileTopbar');
        if (!bar) return;
        const opening = !bar.classList.contains('search-open');
        bar.classList.toggle('search-open', opening);
        if (opening) {
            setTimeout(() => bar.querySelector('.topbar-search-input')?.focus(), 50);
        }
    }

    function toggleSidebarCollapse() {
        const sidebar = document.getElementById('sidebar');
        sidebarCollapsed = !sidebarCollapsed;
        sidebar.classList.toggle('collapsed', sidebarCollapsed);
        document.documentElement.classList.toggle('sidebar-collapsed', sidebarCollapsed);
        localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
        updateSidebarToggleTooltip(sidebarCollapsed);
    }

    // Tooltip del botón de contraer/expandir sidebar: aparece con un pequeño
    // delay (como cualquier tooltip nativo) y se posiciona con JS para que
    // nunca se recorte, tanto con el sidebar expandido como colapsado.
    (function initSidebarToggleTooltip() {
        const tooltipEl = document.getElementById('customTooltip');
        const btn = document.querySelector('.sidebar-toggle-btn');
        if (!tooltipEl || !btn) return;

        let showTimer = null;

        function positionTooltip() {
            const rect = btn.getBoundingClientRect();
            const tipRect = tooltipEl.getBoundingClientRect();
            let left = rect.right - tipRect.width;
            left = Math.max(8, Math.min(left, window.innerWidth - tipRect.width - 8));
            tooltipEl.style.left = left + 'px';
            tooltipEl.style.top = (rect.bottom + 8) + 'px';
        }

        function showTooltip() {
            tooltipEl.textContent = btn.getAttribute('data-tooltip') || '';
            positionTooltip();
            tooltipEl.classList.add('show');
        }

        function hideTooltip() {
            clearTimeout(showTimer);
            tooltipEl.classList.remove('show');
        }

        btn.addEventListener('mouseenter', function () {
            showTimer = setTimeout(showTooltip, 500);
        });
        btn.addEventListener('mouseleave', hideTooltip);
        btn.addEventListener('click', hideTooltip);
    })();

    document.addEventListener('livewire:navigating', function() {
        console.log('[navigating] dark:', document.documentElement.classList.contains('dark'));
        console.log('[navigating] visibility sidebar:', getComputedStyle(document.getElementById('sidebar')).visibility);
        console.log('[navigating] classList:', document.documentElement.className);

        document.body.classList.add('livewire-navigating');

        applyTheme();

        if (localStorage.getItem('sidebarCollapsed') === 'true')
            document.documentElement.classList.add('sidebar-collapsed');
        document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape', bubbles: true }));
    });

    document.addEventListener('livewire:navigated', function() {
        console.log('[navigated] dark:', document.documentElement.classList.contains('dark'));
        console.log('[navigated] classList:', document.documentElement.className);

        applyTheme()        

        const saved = localStorage.getItem('sidebarCollapsed') === 'true';
        const sidebar = document.getElementById('sidebar');
        sidebar?.classList.toggle('collapsed', saved);
        document.documentElement.classList.toggle('sidebar-collapsed', saved);
        sidebarCollapsed = saved;
        updateSidebarToggleTooltip(saved);
        updateActiveNavItem();
        initMainContentPadding();
        setTimeout(() => document.body.classList.remove('livewire-navigating'), 50);
    });

    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(m) {
            console.log('[MutationObserver] classList cambió a:', document.documentElement.className);
            console.trace(); // muestra el stack trace para saber QUIÉN lo cambió
        });
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
</script>

@fluxScripts
</body>
</html>