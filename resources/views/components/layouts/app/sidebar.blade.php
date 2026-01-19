<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            :root {
                --sidebar-width: 280px;
                --transition-speed: 300ms;
            }

            body {
                background-color: var(--color-bg-all);
                color: var(--text-section-title);
            }

            .sidebar-container {
                width: var(--sidebar-width);
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 40;
                overflow: visible;
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

            .nav-item:hover {
                background: var(--sidebar-item-bg-hover);
                color: var(--sidebar-item-text-hover);
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
            }

            .logo-container {
                padding: 24px 20px;
                display: flex;
                align-items: center;
                gap: 16px;
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

            .main-content {
                margin-left: var(--sidebar-width);
                background-color: var(--color-bg-all);
                color: var(--text-section-title);
                min-height: 100vh;
            }

            @media (max-width: 1024px) {
                .sidebar-container {
                    transform: translateX(-100%);
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
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 39;
                    display: none;
                }

                .mobile-overlay.show {
                    display: block;
                }

                .user-dropdown {
                    width: calc(100% - 32px) !important;
                    left: 16px !important;
                    bottom: 90px !important;
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
        </style>
    </head>
    <body class="min-h-screen">

        <!-- Overlay móvil -->
        <div class="mobile-overlay" id="mobileOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar-container" 
        style="background: linear-gradient(to bottom, var(--sidebar-bg), var(--sidebar-bg-alt));" 
        id="sidebar">
            <div class="sidebar-content">
               <!-- Logo -->
                <div class="logo-container flex items-center gap-4">
                    @php
                        $welcome = \App\Models\WelcomeSection::find(1);
                    @endphp

                    {{-- Logo cuadrado con fondo blanco --}}
                    <div class="logo-icon w-16 h-16 rounded-md bg-white flex items-center justify-center overflow-hidden shadow-sm">
                        @if($welcome && $welcome->logo)
                            <img src="{{ asset('storage/' . $welcome->logo) }}" 
                                alt="{{ $welcome->abreviatura ?? 'ITSCO' }}" 
                                class="w-full h-full object-contain">
                        @else
                            <span class="text-black font-bold text-lg">{{ $welcome->abreviatura ?? 'ITSCO' }}</span>
                        @endif
                    </div>

                    {{-- Texto a la izquierda del logo --}}
                    <div class="flex flex-col text-left">
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
            let userDropdownOpen = false;

            function toggleUserDropdown() {
                const dropdown = document.getElementById('userDropdown');
                userDropdownOpen = !userDropdownOpen;
                
                if (userDropdownOpen) {
                    dropdown.classList.add('show');
                } else {
                    dropdown.classList.remove('show');
                }
            }

            function toggleMobileSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobileOverlay');
                
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('show');
            }

            // Cerrar dropdown al hacer clic fuera
            document.addEventListener('click', function(event) {
                const userMenu = document.querySelector('.user-menu');
                const dropdown = document.getElementById('userDropdown');
                
                if (userDropdownOpen && !userMenu.contains(event.target) && !dropdown.contains(event.target)) {
                    toggleUserDropdown();
                }
            });

            // Cerrar sidebar móvil al hacer clic en overlay
            document.getElementById('mobileOverlay').addEventListener('click', toggleMobileSidebar);

            // Cerrar sidebar móvil al navegar (Livewire)
            document.addEventListener('livewire:navigated', function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobileOverlay');
                
                if (sidebar.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('show');
                }
                
                // Cerrar dropdown si está abierto
                if (userDropdownOpen) {
                    toggleUserDropdown();
                }
            });
        </script>

        @fluxScripts
    </body>
</html>