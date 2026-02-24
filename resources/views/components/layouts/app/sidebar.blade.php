<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        
        <!-- 🎯 CRITICAL: Aplicar estado INMEDIATAMENTE (blocking script) -->
        <script>
            // Este script DEBE ejecutarse de forma síncrona ANTES del render
            (function() {
                const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (collapsed) {
                    // Aplicar clase al html ANTES de que el CSS se procese
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            })();
        </script>
        
        <style>
            :root {
                --sidebar-width: 280px;
                --sidebar-collapsed-width: 80px;
                --transition-speed: 300ms;
            }

            /* 🎯 Estado por defecto - SIN transiciones durante carga inicial */
            body.livewire-navigating * {
                transition: none !important;
            }

            /* 🎯 Aplicar estado colapsado desde CSS puro */
            html.sidebar-collapsed .sidebar-container {
                width: var(--sidebar-collapsed-width);
            }

            html.sidebar-collapsed .main-content {
                margin-left: var(--sidebar-collapsed-width);
            }

            .sidebar-container {
                width: var(--sidebar-width);
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 40;
                overflow: visible;
                transition: width var(--transition-speed) ease;
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

            .sidebar-content::-webkit-scrollbar {
                width: 6px;
            }

            .sidebar-content::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 3px;
            }

            /* Botón de toggle */
            .sidebar-toggle-btn {
                position: absolute;
                right: -16px;
                top: 24px;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: var(--sidebar-bg-user);
                border: 2px solid var(--sidebar-bg);
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
                background: var(--sidebar-item-text);
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

            .logo-container {
                padding: 24px 20px;
                display: flex;
                align-items: center;
                gap: 16px;
                transition: all var(--transition-speed) ease;
            }

            .sidebar-container.collapsed .logo-container {
                justify-content: center;
                padding: 24px 10px;
            }

            .logo-icon {
                width: 64px;
                height: 64px;
                background: white;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                flex-shrink: 0;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
                transition: all var(--transition-speed) ease;
            }

            .sidebar-container.collapsed .logo-icon {
                width: 48px;
                height: 48px;
            }

            .logo-icon img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .logo-icon span {
                color: #1e293b;
                font-weight: 700;
                font-size: 18px;
            }

            .logo-text {
                font-size: 18px;
                font-weight: 700;
                color: white;
                line-height: 1.3;
                opacity: 1;
                transition: opacity var(--transition-speed) ease;
            }

            .sidebar-container.collapsed .logo-text {
                opacity: 0;
                width: 0;
                overflow: hidden;
            }

            .user-menu-container {
                margin-top: auto;
                padding: 16px;
                position: relative;
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

            .sidebar-container.collapsed .user-menu {
                padding: 10px;
            }

            .user-menu:hover {
                background: var(--sidebar-item-bg-hover);
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

            .user-dropdown {
                position: fixed;
                bottom: 100px;
                left: 16px;
                width: calc(var(--sidebar-width) - 32px);
                background: var(--sidebar-bg-user);
                border-radius: 12px;
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
                color: white;
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

            /* 🎯 SOLUCIÓN: Aplicar margin-left INSTANTÁNEAMENTE sin transición */
            .main-content {
                margin-left: var(--sidebar-width);
                background-color: var(--color-bg-all);
                color: var(--text-section-title);
                min-height: 100vh;
                /* ❌ REMOVEMOS la transición del margin-left */
            }

            .sidebar-container.collapsed ~ .main-content {
                margin-left: var(--sidebar-collapsed-width);
            }

            @media (max-width: 1024px) {
                .sidebar-toggle-btn {
                    display: none;
                }

                .sidebar-container {
                    transform: translateX(-100%);
                    width: 100vw;
                    transition: transform var(--transition-speed) ease;
                }

                .sidebar-container.collapsed {
                    width: 100vw;
                }

                .sidebar-container.mobile-open {
                    transform: translateX(0);
                }

                .main-content {
                    margin-left: 0 !important;
                }

                .mobile-overlay {
                    position: fixed;
                    inset: 0;
                    background: rgba(0, 0, 0, 0.6);
                    z-index: 39;
                    display: none;
                    backdrop-filter: blur(2px);
                }

                .mobile-overlay.show {
                    display: block;
                }

                .logo-container {
                    padding: 32px 24px;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                }

                .logo-icon {
                    width: 72px;
                    height: 72px;
                }

                .logo-text {
                    font-size: 20px;
                    opacity: 1 !important;
                    width: auto !important;
                }

                .nav-item {
                    padding: 16px 20px;
                    margin: 6px 16px;
                    font-size: 16px;
                    border-radius: 12px;
                    justify-content: flex-start !important;
                }

                .nav-item-icon {
                    width: 24px;
                    height: 24px;
                }

                .nav-item-text {
                    opacity: 1 !important;
                    width: auto !important;
                }

                .user-menu-container {
                    position: sticky;
                    bottom: 0;
                    background: linear-gradient(to top, var(--sidebar-bg) 70%, transparent);
                    padding: 20px;
                    margin-top: auto;
                }

                .user-menu {
                    padding: 14px;
                    border-radius: 14px;
                }

                .user-avatar {
                    width: 48px;
                    height: 48px;
                    font-size: 18px;
                }

                .user-details {
                    opacity: 1 !important;
                    width: auto !important;
                }

                .user-name {
                    font-size: 15px;
                }

                .user-email {
                    font-size: 13px;
                }

                .user-dropdown {
                    width: calc(100vw - 48px) !important;
                    left: 24px !important;
                    bottom: 110px !important;
                    max-width: 400px;
                    margin: 0 auto;
                    right: 24px !important;
                }

                .mobile-close-btn {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 44px;
                    height: 44px;
                    border-radius: 12px;
                    background: rgba(255, 255, 255, 0.1);
                    border: none;
                    cursor: pointer;
                    transition: all 200ms ease;
                    margin-left: auto;
                }

                .mobile-close-btn:hover {
                    background: rgba(255, 255, 255, 0.15);
                    transform: scale(1.05);
                }

                .mobile-close-btn svg {
                    width: 24px;
                    height: 24px;
                    color: white;
                }
            }

            .mobile-header {
                display: none;
                position: sticky;
                top: 0;
                z-index: 30;
                background: rgb(15, 23, 42);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                padding: 16px 20px;
            }

            @media (max-width: 1024px) {
                .mobile-header {
                    display: flex;
                    align-items: center;
                    gap: 16px;
                }
            }

            .mobile-toggle {
                width: 40px;
                height: 40px;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.05);
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }

            .mobile-toggle svg {
                width: 24px;
                height: 24px;
                color: white;
            }

            /* 🎯 Desactivar transiciones durante navegación Livewire */
            body.livewire-navigating,
            body.livewire-navigating * {
                transition: none !important;
                animation: none !important;
            }

            /* 🎯 El sidebar y main-content NO deben tener transición en el margin/width durante carga */
            html.sidebar-collapsed .sidebar-container,
            html.sidebar-collapsed .main-content {
                transition: none;
            }

            /* Solo aplicar transiciones cuando el usuario interactúa manualmente */
            .sidebar-container:not(.navigating) {
                transition: width var(--transition-speed) ease;
            }

            .main-content:not(.navigating) {
                transition: margin-left var(--transition-speed) ease;
            }
            
        </style>
    </head>
    <body class="min-h-screen">

        <!-- Overlay móvil -->
        <div class="mobile-overlay" id="mobileOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar-container" 
        style="background: linear-gradient(to bottom, var(--sidebar-bg), var(--sidebar-bg-alt));" 
        id="sidebar"
        data-collapsed="false">
            <!-- Botón de toggle para desktop -->
            <button class="sidebar-toggle-btn" onclick="toggleSidebarCollapse()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="sidebar-content">
                <!-- Botón de cerrar para móvil -->
                <button class="mobile-close-btn lg:hidden" onclick="toggleMobileSidebar()" style="position: absolute; top: 20px; right: 20px; z-index: 50;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

               <!-- Logo -->
                <div class="logo-container flex items-center gap-4">
                    @php
                        $welcome = \App\Models\WelcomeSection::find(1);
                    @endphp

                    <div class="logo-icon w-16 h-16 rounded-md bg-white flex items-center justify-center overflow-hidden shadow-sm">
                        @if($welcome && $welcome->logo)
                            <img src="{{ asset('storage/' . $welcome->logo) }}" 
                                alt="{{ $welcome->abreviatura ?? 'ITSCO' }}" 
                                class="w-full h-full object-contain">
                        @else
                            <span class="text-black font-bold text-lg">{{ $welcome->abreviatura ?? 'ITSCO' }}</span>
                        @endif
                    </div>

                    <div class="flex flex-col text-left logo-text">
                        <span class="text-xl font-extrabold text-[var(--sidebar-title-text)] leading-snug">
                            Servicio Social
                        </span>
                        <span class="text-sm font-medium text-[var(--sidebar-subtitle-text)]">
                            ITSCO
                        </span>
                    </div>
                </div>

                <!-- Navigation -->
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

                <!-- User Menu Desktop -->
                <div class="user-menu-container">
                    <!-- Dropdown -->
                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('settings.profile') }}" 
                           class="dropdown-item"
                           wire:navigate>
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

                    <!-- User Info Button -->
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

        <!-- Mobile Header -->
        <header class="mobile-header">
            <button class="mobile-toggle" onclick="toggleMobileSidebar()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div style="flex: 1;"></div>

            <flux:dropdown position="top" align="end">
                <div class="user-avatar" style="width: 40px; height: 40px; cursor: pointer;">
                    {{ auth()->user()->initials() }}
                </div>

                <flux:menu class="!bg-[var(--sidebar-bg)]">
                    <div style="padding: 12px;">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-email">{{ auth()->user()->email }}</div>
                    </div>

                    <flux:menu.separator class="border-[var(--sidebar-border)]" />

                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Configuración') }}
                    </flux:menu.item>

                    <flux:menu.separator class="border-[var(--sidebar-border)]" />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            {{ $slot }}
        </main>

        <script>
            // 🎯 APLICAR ESTADO INMEDIATAMENTE (antes de cualquier render)
            (function() {
                const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (collapsed) {
                    document.documentElement.classList.add('sidebar-collapsed');
                    const sidebar = document.getElementById('sidebar');
                    if (sidebar) {
                        sidebar.classList.add('collapsed');
                    }
                }
            })();

            let userDropdownOpen = false;
            let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            function toggleSidebarCollapse() {
                const sidebar = document.getElementById('sidebar');
                sidebarCollapsed = !sidebarCollapsed;
                
                if (sidebarCollapsed) {
                    sidebar.classList.add('collapsed');
                    document.documentElement.classList.add('sidebar-collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                    document.documentElement.classList.remove('sidebar-collapsed');
                }
                
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
                
                sidebar?.classList.toggle('mobile-open');
                overlay?.classList.toggle('show');
            }

            // Cerrar dropdown al hacer clic fuera
            document.addEventListener('click', function(event) {
                const userMenu = document.querySelector('.user-menu');
                const dropdown = document.getElementById('userDropdown');
                
                if (userDropdownOpen && !userMenu?.contains(event.target) && !dropdown?.contains(event.target)) {
                    toggleUserDropdown();
                }
            });

            // Cerrar sidebar móvil al hacer clic en overlay
            document.getElementById('mobileOverlay')?.addEventListener('click', toggleMobileSidebar);

            // 🎯 EVENTOS DE LIVEWIRE - Prevenir flash visual
            document.addEventListener('livewire:navigating', function() {
                // Desactivar TODAS las transiciones durante navegación
                document.body.classList.add('livewire-navigating');
                
                // Mantener el estado actual visible
                const currentCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (currentCollapsed) {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            });

            document.addEventListener('livewire:navigated', function() {
                // Reaplicar estado INMEDIATAMENTE después de navegación
                const savedState = localStorage.getItem('sidebarCollapsed') === 'true';
                const sidebar = document.getElementById('sidebar');
                
                if (savedState) {
                    sidebar?.classList.add('collapsed');
                    document.documentElement.classList.add('sidebar-collapsed');
                } else {
                    sidebar?.classList.remove('collapsed');
                    document.documentElement.classList.remove('sidebar-collapsed');
                }
                
                // Cerrar elementos abiertos
                if (sidebar?.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                    document.getElementById('mobileOverlay')?.classList.remove('show');
                }
                
                if (userDropdownOpen) {
                    userDropdownOpen = false;
                    document.getElementById('userDropdown')?.classList.remove('show');
                }
                
                // Reactivar transiciones después de 50ms
                setTimeout(() => {
                    document.body.classList.remove('livewire-navigating');
                }, 50);
            });
        </script>

        @fluxScripts
    </body>
</html>