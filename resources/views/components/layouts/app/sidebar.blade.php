<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        
        <script>
            (function() {
                const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (collapsed) {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            })();
        </script>
        
        <style>
            /* ===========================
               VARIABLES SIDEBAR
            =========================== */
            :root {
                --sidebar-width: 255px;
                --sidebar-collapsed-width: 80px;
                --transition-speed: 300ms;

                --sidebar-bg: #0a0a0a;
                --sidebar-bg-alt: #0a0a0a;
                --sidebar-border: #191919;

                --sidebar-title-text: #ffffff;
                --sidebar-subtitle-text: #d6d6d6;
                --sidebar-item-text: #e6e6e6;

                --sidebar-item-bg-active: #1b1b1b;
                --sidebar-item-text-active: #f0f6fc;

                --sidebar-item-bg-hover: #1b1b1b;
                --sidebar-item-text-hover: #f0f6fc;

                --sidebar-bg-user: #0a0a0a;
                --sidebar-logo-user: #f0f6fc;
                --sidebar-logo-letra-user: #0d1117;
                --sidebar-email-user: #8b949e;
                --sidebar-divisor: rgba(255, 255, 255, 0.08);
            }

            /* ===========================
               TRANSICIONES
            =========================== */
            body.livewire-navigating * {
                transition: none !important;
                animation: none !important;
            }

            html.sidebar-collapsed .sidebar-container,
            html.sidebar-collapsed .main-content {
                transition: none;
            }

            html.sidebar-collapsed .sidebar-container {
                width: var(--sidebar-collapsed-width);
            }

            html.sidebar-collapsed .main-content {
                margin-left: var(--sidebar-collapsed-width);
            }

            .sidebar-container:not(.navigating) {
                transition: width var(--transition-speed) ease;
            }

            .main-content:not(.navigating) {
                transition: margin-left var(--transition-speed) ease;
            }

            /* ===========================
               SIDEBAR BASE
            =========================== */
            .sidebar-container {
                width: var(--sidebar-width);
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 40;
                overflow: visible;
                transition: width var(--transition-speed) ease;
                border-right: 1px solid var(--sidebar-border);
            }

            .sidebar-container.collapsed {
                width: var(--sidebar-collapsed-width);
            }

            .sidebar-content {
                height: 100%;
                overflow-y: auto;
                overflow-x: visible;
                scrollbar-width: thin;
                scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
                display: flex;
                flex-direction: column;
            }

            .sidebar-content::-webkit-scrollbar { width: 6px; }
            .sidebar-content::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 3px;
            }

            /* ===========================
               BOTÓN TOGGLE DESKTOP
            =========================== */
            .sidebar-toggle-btn {
                position: absolute;
                right: -16px;
                top: 24px;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: var(--sidebar-bg);
                border: 2px solid var(--sidebar-border);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 200ms ease;
                z-index: 50;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            }

            .sidebar-toggle-btn:hover {
                background: var(--sidebar-item-bg-hover);
                transform: scale(1.1);
            }

            .sidebar-toggle-btn svg {
                width: 18px;
                height: 18px;
                color: white;
                transition: transform var(--transition-speed) ease;
            }

            .sidebar-container.collapsed .sidebar-toggle-btn svg {
                transform: rotate(180deg);
            }

            /* ===========================
               LOGO
            =========================== */
            .logo-container {
                padding: 16px 14px;
                display: flex;
                align-items: center;
                gap: 10px;
                transition: all var(--transition-speed) ease;
                border-bottom: 1px solid var(--sidebar-border);
                overflow: hidden;
            }

            .sidebar-container.collapsed .logo-container {
                justify-content: center;
                padding: 16px 10px;
                gap: 0;
            }

            .logo-initials {
                display: none;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 3px;
                flex-shrink: 0;
            }

            .sidebar-container.collapsed .logo-initials {
                display: flex;
            }

            .logo-initials-main {
                font-size: 15px;
                font-weight: 700;
                color: var(--sidebar-title-text);
                letter-spacing: 0.02em;
                line-height: 1;
            }

            .logo-initials-sub {
                font-size: 8px;
                font-weight: 600;
                color: var(--sidebar-subtitle-text);
                letter-spacing: 0.12em;
                text-transform: uppercase;
            }

            .logo-text-block {
                display: flex;
                flex-direction: column;
                gap: 4px;
                opacity: 1;
                overflow: hidden;
                white-space: nowrap;
                transition: opacity var(--transition-speed) ease,
                            width var(--transition-speed) ease;
            }

            .sidebar-container.collapsed .logo-text-block {
                opacity: 0;
                width: 0;
            }

            .logo-app-name {
                font-size: 16px;
                font-weight: 600;
                color: var(--sidebar-title-text);
                letter-spacing: 0.01em;
                line-height: 1;
            }

            /* ===========================
               NAV ITEMS
            =========================== */
            .nav-item {
                position: relative;
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 16px;
                margin: 4px 12px;
                border-radius: 10px;
                color: var(--sidebar-item-text);
                text-decoration: none;
                transition: all 200ms ease;
                font-size: 14px;
                font-weight: 500;
                white-space: nowrap;
            }

            .sidebar-container.collapsed .nav-item {
                justify-content: center;
                padding: 12px;
            }

            .nav-item:hover {
                background: var(--sidebar-item-bg-hover);
                color: var(--sidebar-item-text-hover);
            }

            .sidebar-container:not(.collapsed) .nav-item:hover {
                transform: translateX(2px);
            }

            .nav-item.active {
                background: var(--sidebar-item-bg-active);
                color: var(--sidebar-item-text-active);
            }

            .nav-item.active::before {
                content: '';
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 3px;
                height: 60%;
                background: var(--sidebar-item-text-active);
                border-radius: 0 4px 4px 0;
            }

            .nav-item-icon {
                width: 22px;
                height: 22px;
                flex-shrink: 0;
            }

            .nav-item-text {
                opacity: 1;
                transition: opacity var(--transition-speed) ease;
            }

            .sidebar-container.collapsed .nav-item-text {
                opacity: 0;
                width: 0;
                overflow: hidden;
            }

            /* ===========================
               USER MENU
            =========================== */
            .user-menu-container {
                margin-top: auto;
                padding: 16px;
                position: relative; /* ← necesario para el dropdown absolute en móvil */
            }

            .user-menu {
                padding: 10px;
                border-radius: 12px;
                background: var(--sidebar-bg-user);
                cursor: pointer;
                transition: all 200ms ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .user-menu:hover {
                background: var(--sidebar-item-bg-hover);
                transform: translateY(-2px);
            }

            .sidebar-container.collapsed .user-menu:hover {
                background: var(--sidebar-bg-user);
                transform: translateY(-2px);
            }

            .user-info {
                display: flex;
                align-items: center;
                gap: 12px;
                width: 100%;
            }

            .sidebar-container.collapsed .user-info {
                justify-content: center;
            }

            .user-avatar {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                background: var(--sidebar-logo-user);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--sidebar-logo-letra-user);
                font-weight: 600;
                font-size: 16px;
                flex-shrink: 0;
            }

            .user-details {
                flex: 1;
                min-width: 0;
                opacity: 1;
                transition: opacity var(--transition-speed) ease;
            }

            .sidebar-container.collapsed .user-details {
                opacity: 0;
                width: 0;
                overflow: hidden;
            }

            .user-name {
                font-size: 14px;
                font-weight: 600;
                color: white;
                display: block;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .user-email {
                font-size: 12px;
                color: var(--sidebar-email-user);
                display: block;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            /* ===========================
               DROPDOWN USUARIO (desktop)
            =========================== */
            .user-dropdown {
                position: fixed;
                bottom: 100px;
                left: 16px;
                width: calc(var(--sidebar-width) - 32px);
                background: var(--sidebar-bg-user);
                border-radius: 12px;
                border: 1px solid var(--sidebar-border);
                padding: 8px;
                opacity: 0;
                visibility: hidden;
                transform: translateY(10px);
                transition: all 200ms cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5);
                z-index: 9999;
            }

            .sidebar-container.collapsed .user-dropdown {
                left: calc(var(--sidebar-collapsed-width) + 16px);
                width: 250px;
            }

            .user-dropdown.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            .dropdown-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 14px;
                color: var(--sidebar-item-text);
                text-decoration: none;
                transition: all 200ms ease;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                background: none;
                border: none;
                width: 100%;
                text-align: left;
                border-radius: 8px;
            }

            .dropdown-item:hover {
                background: var(--sidebar-item-bg-hover);
                color: var(--sidebar-item-text-hover);
                transform: translateX(4px);
            }

            .dropdown-item svg {
                width: 20px;
                height: 20px;
            }

            .dropdown-divider {
                height: 1px;
                background: var(--sidebar-divisor);
                margin: 8px 0;
            }

            /* ===========================
               MAIN CONTENT
            =========================== */
            .main-content {
                margin-left: var(--sidebar-width);
                background-color: var(--color-bg-all);
                color: var(--text-section-title);
                min-height: 100vh;
            }

            .sidebar-container.collapsed ~ .main-content {
                margin-left: var(--sidebar-collapsed-width);
            }

            /* ===========================
               MOBILE HEADER
            =========================== */
            .mobile-header {
                display: none;
                position: sticky;
                top: 0;
                z-index: 30;
                background: var(--sidebar-bg);
                border-bottom: 1px solid var(--sidebar-border);
                padding: 0 16px;
                height: 60px;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
            }

            .mobile-toggle {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                background: rgba(255, 255, 255, 0.06);
                border: 1px solid var(--sidebar-border);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                flex-shrink: 0;
                transition: background 200ms ease;
            }

            .mobile-toggle:hover {
                background: rgba(255, 255, 255, 0.1);
            }

            .mobile-toggle svg {
                width: 22px;
                height: 22px;
                color: white;
            }

            /* Título centrado en el header móvil */
            .mobile-header-title {
                flex: 1;
                text-align: center;
                font-size: 15px;
                font-weight: 600;
                color: var(--sidebar-title-text);
                letter-spacing: 0.01em;
                pointer-events: none;
            }

            /* Avatar en el header móvil */
            .mobile-header-avatar {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                background: var(--sidebar-logo-user);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--sidebar-logo-letra-user);
                font-weight: 600;
                font-size: 14px;
                flex-shrink: 0;
                border: 1px solid rgba(255, 255, 255, 0.1);
                cursor: pointer;
            }

            /* ===========================
               RESPONSIVE MÓVIL
            =========================== */
            @media (max-width: 1024px) {
                .mobile-header { display: flex; }

                .sidebar-toggle-btn { display: none; }

                /* ── CRÍTICO: resetear TODO estado colapsado en móvil ────────────
                   Cuando el sidebar está colapsado en desktop (80px) y luego
                   se redimensiona a móvil, hereda el ancho de 80px y queda
                   chiquito. Hay que sobreescribir con !important todo. ────── */
                .sidebar-container,
                .sidebar-container.collapsed {
                    transform: translateX(-100%);
                    width: min(300px, 85vw) !important;
                    max-width: 300px !important;
                    transition: transform var(--transition-speed) ease !important;
                    box-shadow: none;
                }

                .sidebar-container.mobile-open,
                .sidebar-container.collapsed.mobile-open {
                    transform: translateX(0) !important;
                    box-shadow: 8px 0 40px rgba(0, 0, 0, 0.7);
                }

                /* html.sidebar-collapsed en <html> tampoco debe afectar móvil */
                html.sidebar-collapsed .sidebar-container {
                    width: min(300px, 85vw) !important;
                }
                html.sidebar-collapsed .main-content {
                    margin-left: 0 !important;
                }

                /* Revertir visualmente todos los estados "collapsed" dentro del sidebar */
                .sidebar-container.collapsed .nav-item {
                    justify-content: flex-start !important;
                    padding: 13px 16px !important;
                }
                .sidebar-container.collapsed .nav-item-text {
                    opacity: 1 !important;
                    width: auto !important;
                    overflow: visible !important;
                }
                .sidebar-container.collapsed .logo-container {
                    justify-content: flex-start !important;
                    padding: 20px 18px 16px !important;
                    gap: 10px !important;
                }
                .sidebar-container.collapsed .logo-text-block {
                    opacity: 1 !important;
                    width: auto !important;
                }
                .sidebar-container.collapsed .logo-initials {
                    display: none !important;
                }
                .sidebar-container.collapsed .user-info {
                    justify-content: flex-start !important;
                }
                .sidebar-container.collapsed .user-details {
                    opacity: 1 !important;
                    width: auto !important;
                    overflow: visible !important;
                }

                .main-content { margin-left: 0 !important; }

                /* Overlay */
                .mobile-overlay {
                    position: fixed;
                    inset: 0;
                    background: rgba(0, 0, 0, 0.65);
                    z-index: 39;
                    display: none;
                    backdrop-filter: blur(3px);
                    -webkit-backdrop-filter: blur(3px);
                }
                .mobile-overlay.show { display: block; }

                /* Logo: nunca mostrar iniciales colapsadas en móvil */
                .logo-container {
                    padding: 20px 18px 16px;
                    gap: 10px;
                }
                .logo-initials { display: none !important; }
                .logo-text-block {
                    opacity: 1 !important;
                    width: auto !important;
                }

                /* Botón X de cerrar sidebar */
                .mobile-close-btn {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 34px;
                    height: 34px;
                    border-radius: 8px;
                    background: rgba(255, 255, 255, 0.08);
                    border: 1px solid var(--sidebar-border);
                    cursor: pointer;
                    transition: background 200ms ease;
                    flex-shrink: 0;
                }
                .mobile-close-btn:hover { background: rgba(255, 255, 255, 0.14); }
                .mobile-close-btn svg { width: 18px; height: 18px; color: white; }

                /* Nav items en móvil */
                .nav-item {
                    padding: 13px 16px;
                    margin: 3px 10px;
                    font-size: 15px;
                    border-radius: 10px;
                    justify-content: flex-start !important;
                    gap: 14px;
                }
                .nav-item-icon { width: 22px; height: 22px; }
                .nav-item-text {
                    opacity: 1 !important;
                    width: auto !important;
                    overflow: visible !important;
                }

                /* User menu en el sidebar móvil */
                .user-menu-container {
                    position: sticky;
                    bottom: 0;
                    background: linear-gradient(to top, var(--sidebar-bg) 75%, transparent);
                    padding: 10px 14px 18px;
                    margin-top: auto;
                }

                .user-menu {
                    padding: 10px 12px;
                    border-radius: 12px;
                    border: 1px solid var(--sidebar-border);
                }

                .user-avatar { width: 40px; height: 40px; font-size: 15px; }

                .user-details {
                    opacity: 1 !important;
                    width: auto !important;
                    overflow: visible !important;
                }
                .user-name { font-size: 14px; }
                .user-email { font-size: 12px; }

                /* Dropdown usuario: position absolute relativa al contenedor
                   para evitar conflicto con el sidebar fixed+transform */
                .user-dropdown {
                    position: absolute !important;
                    bottom: calc(100% + 6px) !important;
                    left: 14px !important;
                    right: 14px !important;
                    width: auto !important;
                    max-width: none !important;
                }
            }

            /* Pantallas muy pequeñas */
            @media (max-width: 360px) {
                .sidebar-container {
                    width: 92vw;
                    max-width: 92vw;
                }
                .nav-item { font-size: 14px; padding: 12px 14px; }
            }
        </style>
    </head>
    <body class="min-h-screen">

        <div class="mobile-overlay" id="mobileOverlay"></div>

        <!-- ═══ SIDEBAR ════════════════════════════════════════════════════ -->
        <aside class="sidebar-container" 
               style="background: linear-gradient(to bottom, var(--sidebar-bg), var(--sidebar-bg-alt));" 
               id="sidebar">

            <button class="sidebar-toggle-btn" onclick="toggleSidebarCollapse()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="sidebar-content">

                <!-- ── ENCABEZADO CON BOTÓN X EN MÓVIL ─────────────── -->
                <div class="logo-container">
                    {{-- Colapsado (solo desktop): iniciales --}}
                    <div class="logo-initials">
                        <span class="logo-initials-main">SS</span>
                        <span class="logo-initials-sub">ITSCO</span>
                    </div>

                    {{-- Expandido: nombre --}}
                    <div class="logo-text-block" style="flex: 1;">
                        <span class="logo-app-name">Servicio Social</span>
                        <span style="font-size: 9px; font-weight: 600; letter-spacing: 0.14em; text-transform: uppercase; color: var(--sidebar-subtitle-text); line-height: 1;">ITSCO</span>
                    </div>

                    {{-- Botón X — solo visible en móvil --}}
                    <button class="mobile-close-btn lg:hidden" onclick="toggleMobileSidebar()">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- ── NAVEGACIÓN ───────────────────────────────────── -->
                <nav class="py-4">
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('dashboard') }}" 
                           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           wire:navigate>
                            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="nav-item-text">{{ __('Inicio') }}</span>
                        </a>

                        <a href="{{ route('campuses.index') }}" 
                           class="nav-item {{ request()->routeIs('campuses.index') ? 'active' : '' }}"
                           wire:navigate>
                            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="nav-item-text">{{ __('Campus') }}</span>
                        </a>

                        <a href="{{ route('careers.index') }}" 
                           class="nav-item {{ request()->routeIs('careers.index') ? 'active' : '' }}"
                           wire:navigate>
                            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="nav-item-text">{{ __('Carreras') }}</span>
                        </a>

                        <a href="{{ route('semesters.index') }}" 
                           class="nav-item {{ request()->routeIs('semesters.index') ? 'active' : '' }}"
                           wire:navigate>
                            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                            <span class="nav-item-text">{{ __('Semestres') }}</span>
                        </a>
                    @endif

                    @if(!auth()->user()->hasRole('admin'))
                        <a href="{{ route('students.profile') }}" 
                           class="nav-item {{ request()->routeIs('students.profile') ? 'active' : '' }}"
                           wire:navigate>
                            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="nav-item-text">{{ __('Perfil') }}</span>
                        </a>

                        <a href="{{ route('student-documents.index') }}" 
                           class="nav-item {{ request()->routeIs('student-documents.index') ? 'active' : '' }}"
                           wire:navigate>
                            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            <span class="nav-item-text">{{ __('Mis Documentos') }}</span>
                        </a>
                    @endif
                </nav>

                <!-- ── USER MENU ────────────────────────────────────── -->
                <div class="user-menu-container">
                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('settings.profile') }}" class="dropdown-item" wire:navigate>
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ __('Configuración') }}</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>{{ __('Cerrar Sesión') }}</span>
                            </button>
                        </form>
                    </div>

                    <div class="user-menu" onclick="toggleUserDropdown()">
                        <div class="user-info">
                            <div class="user-avatar">{{ auth()->user()->initials() }}</div>
                            <div class="user-details">
                                <span class="user-name">{{ auth()->user()->name }}</span>
                                <span class="user-email">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

        <!-- ═══ MOBILE HEADER ══════════════════════════════════════════════ -->
        <header class="mobile-header">
            {{-- Botón hamburger --}}
            <button class="mobile-toggle" onclick="toggleMobileSidebar()" aria-label="Abrir menú">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- Título centrado --}}
            <span class="mobile-header-title">Servicio Social</span>

            {{-- Avatar con dropdown de Flux — wire:ignore evita crashes SPA --}}
            <div wire:ignore>
                <flux:dropdown position="bottom" align="end">
                    <div class="mobile-header-avatar">
                        {{ auth()->user()->initials() }}
                    </div>

                    <flux:menu style="background: var(--sidebar-bg); border: 1px solid var(--sidebar-border); min-width: 200px;">
                        <div style="padding: 10px 14px 8px;">
                            <div style="font-size: 13px; font-weight: 600; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ auth()->user()->name }}
                            </div>
                            <div style="font-size: 11px; color: var(--sidebar-email-user); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ auth()->user()->email }}
                            </div>
                        </div>

                        <flux:menu.separator style="border-color: var(--sidebar-border);" />

                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                            {{ __('Configuración') }}
                        </flux:menu.item>

                        <flux:menu.separator style="border-color: var(--sidebar-border);" />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                                {{ __('Cerrar Sesión') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </header>

        <!-- ═══ MAIN CONTENT ══════════════════════════════════════════════ -->
        <main class="main-content">
            {{ $slot }}
        </main>

        <script>
            // ─── Estado inicial del sidebar (sin flash) ──────────────────────────
            (function() {
                const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (collapsed) {
                    document.documentElement.classList.add('sidebar-collapsed');
                    const sidebar = document.getElementById('sidebar');
                    if (sidebar) sidebar.classList.add('collapsed');
                }
            })();

            if (typeof userDropdownOpen === 'undefined') var userDropdownOpen = false;
            if (typeof sidebarCollapsed === 'undefined') {
                var sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            }

            // ─── Funciones del sidebar ───────────────────────────────────────────
            function toggleSidebarCollapse() {
                const sidebar = document.getElementById('sidebar');
                sidebarCollapsed = !sidebarCollapsed;
                sidebar.classList.toggle('collapsed', sidebarCollapsed);
                document.documentElement.classList.toggle('sidebar-collapsed', sidebarCollapsed);
                localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
            }

            function toggleUserDropdown() {
                const dropdown = document.getElementById('userDropdown');
                userDropdownOpen = !userDropdownOpen;
                dropdown?.classList.toggle('show', userDropdownOpen);
            }

            function toggleMobileSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobileOverlay');
                const isOpening = !sidebar?.classList.contains('mobile-open');

                sidebar?.classList.toggle('mobile-open');
                overlay?.classList.toggle('show');

                // Al abrir en móvil: remover 'collapsed' visualmente para que
                // se vea expandido, pero recordar el estado para restaurarlo al cerrar
                if (isOpening && sidebar?.classList.contains('collapsed')) {
                    sidebar.dataset.wasCollapsed = 'true';
                } else if (!isOpening && sidebar?.dataset.wasCollapsed === 'true') {
                    delete sidebar.dataset.wasCollapsed;
                }
            }

            // ─── Cerrar dropdown al hacer click fuera ───────────────────────────
            document.addEventListener('click', function(event) {
                const userMenu = document.querySelector('.user-menu');
                const dropdown = document.getElementById('userDropdown');
                if (userDropdownOpen && !userMenu?.contains(event.target) && !dropdown?.contains(event.target)) {
                    toggleUserDropdown();
                }
            });

            // ─── Overlay cierra el sidebar móvil ────────────────────────────────
            function bindOverlay() {
                const overlay = document.getElementById('mobileOverlay');
                if (overlay) {
                    const fresh = overlay.cloneNode(true);
                    overlay.parentNode.replaceChild(fresh, overlay);
                    fresh.addEventListener('click', toggleMobileSidebar);
                }
            }
            bindOverlay();

            // ─── Livewire SPA hooks ──────────────────────────────────────────────
            document.addEventListener('livewire:navigating', function() {
                document.body.classList.add('livewire-navigating');

                // Preservar estado colapsado
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }

                // Cerrar dropdown del sidebar
                if (userDropdownOpen) {
                    userDropdownOpen = false;
                    document.getElementById('userDropdown')?.classList.remove('show');
                }

                // Cerrar cualquier panel de Flux abierto (dropdown móvil)
                document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape', bubbles: true }));
            });

            document.addEventListener('livewire:navigated', function() {
                // Restaurar sidebar
                const savedState = localStorage.getItem('sidebarCollapsed') === 'true';
                const sidebar = document.getElementById('sidebar');
                sidebar?.classList.toggle('collapsed', savedState);
                document.documentElement.classList.toggle('sidebar-collapsed', savedState);

                // Cerrar sidebar móvil
                if (sidebar?.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                    document.getElementById('mobileOverlay')?.classList.remove('show');
                }

                // Reset dropdown sidebar
                if (userDropdownOpen) {
                    userDropdownOpen = false;
                    document.getElementById('userDropdown')?.classList.remove('show');
                }

                // Re-enlazar overlay (Livewire puede haberlo reemplazado)
                bindOverlay();

                setTimeout(() => document.body.classList.remove('livewire-navigating'), 50);
            });
        </script>

        @fluxScripts
    </body>
</html>