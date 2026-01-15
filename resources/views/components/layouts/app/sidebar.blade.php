<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            :root {
                --sidebar-width: 280px;
                --sidebar-collapsed-width: 80px;
                --transition-speed: 300ms;
            }

            .sidebar-container {
                width: var(--sidebar-width);
                transition: width var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 40;
            }

            .sidebar-container.collapsed {
                width: var(--sidebar-collapsed-width);
            }

            .sidebar-content {
                height: 100%;
                overflow-y: auto;
                overflow-x: hidden;
                scrollbar-width: thin;
                scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
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
                color: rgba(255, 255, 255, 0.7);
                text-decoration: none;
                transition: all 200ms ease;
                font-size: 14px;
                font-weight: 500;
                white-space: nowrap;
            }

            .nav-item:hover {
                background: rgba(255, 255, 255, 0.05);
                color: rgba(255, 255, 255, 0.95);
            }

            .nav-item.active {
                background: rgba(99, 102, 241, 0.1);
                color: rgb(129, 140, 248);
            }

            .nav-item.active::before {
                content: '';
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 4px;
                height: 70%;
                background: rgb(129, 140, 248);
                border-radius: 0 4px 4px 0;
            }

            .nav-item-icon {
                width: 20px;
                height: 20px;
                flex-shrink: 0;
            }

            .nav-item-text {
                opacity: 1;
                transition: opacity var(--transition-speed) ease;
            }

            .collapsed .nav-item-text {
                opacity: 0;
                width: 0;
            }

            .nav-group-heading {
                padding: 8px 16px;
                margin: 20px 12px 8px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: rgba(255, 255, 255, 0.4);
                transition: opacity var(--transition-speed) ease;
            }

            .collapsed .nav-group-heading {
                opacity: 0;
            }

            .divider {
                height: 1px;
                background: rgba(255, 255, 255, 0.1);
                margin: 16px 20px;
            }

            .toggle-btn {
                position: absolute;
                right: -12px;
                top: 24px;
                width: 24px;
                height: 24px;
                background: rgb(30, 41, 59);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 200ms ease;
                z-index: 50;
            }

            .toggle-btn:hover {
                background: rgb(51, 65, 85);
                transform: scale(1.1);
            }

            .toggle-btn svg {
                width: 14px;
                height: 14px;
                color: rgba(255, 255, 255, 0.7);
                transition: transform var(--transition-speed) ease;
            }

            .collapsed .toggle-btn svg {
                transform: rotate(180deg);
            }

            .logo-container {
                padding: 24px 20px;
                display: flex;
                align-items: center;
                gap: 12px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .logo-text {
                font-size: 18px;
                font-weight: 700;
                color: white;
                transition: opacity var(--transition-speed) ease;
            }

            .collapsed .logo-text {
                opacity: 0;
                width: 0;
            }

            .user-menu {
                margin: 16px 12px;
                padding: 12px;
                border-radius: 10px;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.1);
                cursor: pointer;
                transition: all 200ms ease;
            }

            .user-menu:hover {
                background: rgba(255, 255, 255, 0.05);
            }

            .user-info {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                background: linear-gradient(135deg, rgb(99, 102, 241), rgb(139, 92, 246));
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
                font-size: 14px;
                flex-shrink: 0;
            }

            .user-details {
                flex: 1;
                min-width: 0;
                transition: opacity var(--transition-speed) ease;
            }

            .collapsed .user-details {
                opacity: 0;
                width: 0;
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
                color: rgba(255, 255, 255, 0.5);
                display: block;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .main-content {
                margin-left: var(--sidebar-width);
                transition: margin-left var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            }

            .sidebar-container.collapsed ~ .main-content {
                margin-left: var(--sidebar-collapsed-width);
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

                .toggle-btn {
                    display: none;
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
    <body class="min-h-screen bg-[#0f172a] text-white">
        <!-- Overlay móvil -->
        <div class="mobile-overlay" id="mobileOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar-container bg-gradient-to-b from-[#1e293b] to-[#0f172a]" id="sidebar">
            <!-- Toggle Button -->
            <button class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <div class="sidebar-content">
                <!-- Logo -->
                <div class="logo-container">
                    <x-app-logo />
                    <span class="logo-text">Mi Plataforma</span>
                </div>

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
                <div style="margin-top: auto; padding-top: 16px;" class="hidden lg:block">
                    <a href="{{ route('settings.profile') }}" 
                       class="nav-item {{ request()->routeIs('settings.profile') ? 'active' : '' }}"
                       wire:navigate>
                        <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="nav-item-text">{{ __('Configuración') }}</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="nav-item w-full text-left">
                            <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="nav-item-text">{{ __('Cerrar Sesión') }}</span>
                        </button>
                    </form>

                    <div class="divider"></div>

                    <div class="user-menu">
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

                <flux:menu class="bg-[#1e293b] border border-[rgba(255,255,255,0.1)]">
                    <div style="padding: 12px;">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-email">{{ auth()->user()->email }}</div>
                    </div>

                    <flux:menu.separator class="border-[rgba(255,255,255,0.1)]" />

                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Configuración') }}
                    </flux:menu.item>

                    <flux:menu.separator class="border-[rgba(255,255,255,0.1)]" />

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
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('collapsed');
                
                // Guardar estado en localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }

            function toggleMobileSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobileOverlay');
                
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('show');
            }

            // Cerrar sidebar móvil al hacer clic en overlay
            document.getElementById('mobileOverlay').addEventListener('click', toggleMobileSidebar);

            // Restaurar estado del sidebar al cargar
            document.addEventListener('DOMContentLoaded', function() {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    document.getElementById('sidebar').classList.add('collapsed');
                }
            });

            // Cerrar sidebar móvil al navegar (Livewire)
            document.addEventListener('livewire:navigated', function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobileOverlay');
                
                if (sidebar.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('show');
                }
            });
        </script>

        @fluxScripts
    </body>
</html>