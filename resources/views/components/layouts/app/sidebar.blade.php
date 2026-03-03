<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <script>
        if (localStorage.getItem('sidebarCollapsed') === 'true')
            document.documentElement.classList.add('sidebar-collapsed');
    </script>
</head>
<body class="min-h-screen">

<div class="mobile-overlay" id="mobileOverlay"></div>

<aside class="sidebar-container" style="background: var(--color-bg);" id="sidebar">

    <button class="sidebar-toggle-btn" onclick="toggleSidebarCollapse()">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    <div class="sidebar-content">

        <div class="logo-container">
            <div class="logo-initials">
                <span class="logo-initials-main">SS</span>
                <span class="logo-initials-sub">ITSCO</span>
            </div>
            <div class="logo-text-block" style="flex:1;">
                <span class="logo-app-name">Servicio Social</span>
                <span class="logo-app-sub">ITSCO</span>
            </div>
            <button class="mobile-close-btn lg:hidden" onclick="toggleMobileSidebar()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="py-4">
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('dashboard') }}"
                   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   wire:navigate>
                    <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="nav-item-text">{{ __('Inicio') }}</span>
                </a>

                <a href="{{ route('campuses.index') }}"
                   class="nav-item {{ request()->routeIs('campuses.index') ? 'active' : '' }}"
                   wire:navigate>
                    <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="nav-item-text">{{ __('Campus') }}</span>
                </a>

                <a href="{{ route('careers.index') }}"
                   class="nav-item {{ request()->routeIs('careers.index') ? 'active' : '' }}"
                   wire:navigate>
                    <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="nav-item-text">{{ __('Carreras') }}</span>
                </a>

                <a href="{{ route('semesters.index') }}"
                   class="nav-item {{ request()->routeIs('semesters.index') ? 'active' : '' }}"
                   wire:navigate>
                    <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                    <span class="nav-item-text">{{ __('Semestres') }}</span>
                </a>
            @else
                <a href="{{ route('students.profile') }}"
                   class="nav-item {{ request()->routeIs('students.profile') ? 'active' : '' }}"
                   wire:navigate>
                    <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="nav-item-text">{{ __('Perfil') }}</span>
                </a>

                <a href="{{ route('student-documents.index') }}"
                   class="nav-item {{ request()->routeIs('student-documents.index') ? 'active' : '' }}"
                   wire:navigate>
                    <svg class="nav-item-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span class="nav-item-text">{{ __('Mis Documentos') }}</span>
                </a>
            @endif
        </nav>

        <div class="user-menu-container">
            <div class="user-dropdown" id="userDropdown">
                <a href="{{ route('settings.profile') }}" class="dropdown-item" wire:navigate>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ __('Configuración') }}</span>
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
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

<header class="mobile-header">
    <button class="mobile-toggle" onclick="toggleMobileSidebar()" aria-label="Abrir menú">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <span class="mobile-header-title">Servicio Social</span>

    <div wire:ignore>
        <flux:dropdown position="bottom" align="end">
            <div class="mobile-header-avatar">{{ auth()->user()->initials() }}</div>

            <flux:menu>
                <div class="mobile-menu-user-info">
                    <div class="mobile-menu-user-name">{{ auth()->user()->name }}</div>
                    <div class="mobile-menu-user-email">{{ auth()->user()->email }}</div>
                </div>
                <flux:menu.separator/>
                <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                    {{ __('Configuración') }}
                </flux:menu.item>
                <flux:menu.separator/>
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

<main class="main-content">
    {{ $slot }}
</main>

<script>
    var userDropdownOpen = false;
    var sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

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
        sidebar?.classList.toggle('mobile-open');
        overlay?.classList.toggle('show');
    }

    document.addEventListener('click', function(e) {
        const userMenu = document.querySelector('.user-menu');
        const dropdown = document.getElementById('userDropdown');
        if (userDropdownOpen && !userMenu?.contains(e.target) && !dropdown?.contains(e.target))
            toggleUserDropdown();
    });

    function bindOverlay() {
        const overlay = document.getElementById('mobileOverlay');
        if (!overlay) return;
        const fresh = overlay.cloneNode(true);
        overlay.parentNode.replaceChild(fresh, overlay);
        fresh.addEventListener('click', toggleMobileSidebar);
    }
    bindOverlay();

    document.addEventListener('livewire:navigating', function() {
        document.body.classList.add('livewire-navigating');
        if (localStorage.getItem('sidebarCollapsed') === 'true')
            document.documentElement.classList.add('sidebar-collapsed');
        if (userDropdownOpen) {
            userDropdownOpen = false;
            document.getElementById('userDropdown')?.classList.remove('show');
        }
        document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape', bubbles: true }));
    });

    document.addEventListener('livewire:navigated', function() {
        const saved = localStorage.getItem('sidebarCollapsed') === 'true';
        const sidebar = document.getElementById('sidebar');
        sidebar?.classList.toggle('collapsed', saved);
        document.documentElement.classList.toggle('sidebar-collapsed', saved);

        if (sidebar?.classList.contains('mobile-open')) {
            sidebar.classList.remove('mobile-open');
            document.getElementById('mobileOverlay')?.classList.remove('show');
        }
        if (userDropdownOpen) {
            userDropdownOpen = false;
            document.getElementById('userDropdown')?.classList.remove('show');
        }
        bindOverlay();
        setTimeout(() => document.body.classList.remove('livewire-navigating'), 50);
    });
</script>

@fluxScripts
</body>
</html>