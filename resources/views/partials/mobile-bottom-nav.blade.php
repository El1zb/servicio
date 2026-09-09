{{--
    Dock flotante inferior (solo <lg), estilo glassmorphism en dos cápsulas:
    la de navegación (ícono arriba + etiqueta abajo, el activo cambia de color
    y su ícono pasa de outline a relleno — mismo par de SVG que el sidebar de
    escritorio) y, separado a un lado, un círculo propio solo para Salir —
    una acción fuerte que no debe mezclarse con la navegación normal. Ambas
    comparten el mismo estilo de vidrio ($glass). Fuera de @persist a
    propósito: wire:navigate reemplaza este bloque completo en cada
    navegación, así que el ítem activo se resuelve con request()->routeIs()
    en el propio render — por eso basta un simple @if($isActive) para elegir
    el SVG relleno, sin el truco CSS de dos <svg> superpuestos que sí
    necesita el sidebar persistido.
--}}
@php
    $isAdmin = auth()->user()->hasRole('admin');
    $item = 'flex flex-col items-center gap-1 rounded-2xl px-2.5 py-1 transition-colors';
    // nav-activate (no active-bg): esa otra variable es para fondos — en
    // modo oscuro es casi negra, invisible sobre este glass oscuro.
    $active = 'text-[var(--sidebar-color-nav-activate)]';
    $inactive = 'text-[var(--sidebar-color-nav)]';
    $glass = 'border border-white/30 bg-linear-to-b from-[rgba(255,255,255,0.45)] to-[rgba(255,255,255,0.22)] shadow-[inset_0_1px_0_0_rgba(255,255,255,0.4),0_20px_45px_-15px_rgba(0,0,0,0.18)] backdrop-blur-[20px] backdrop-saturate-[180%] dark:border-white/8 dark:from-[rgba(23,23,23,0.45)] dark:to-[rgba(23,23,23,0.22)] dark:shadow-[inset_0_1px_0_0_rgba(255,255,255,0.06),0_20px_45px_-15px_rgba(0,0,0,0.5)]';
@endphp

{{-- id como gancho para ocultarlo mientras el panel de notificaciones está
     abierto a pantalla completa (ver body.notif-panel-open en sidebar.css) --}}
<div id="mobileBottomNav" class="fixed bottom-[calc(1rem+env(safe-area-inset-bottom))] left-1/2 z-50 flex -translate-x-1/2 items-center gap-3 lg:hidden">
<nav
    class="{{ $glass }} flex items-center gap-1 rounded-full px-2 py-2"
    aria-label="{{ __('Navegación principal') }}"
>
    @if($isAdmin)
        @php $isActive = request()->routeIs('dashboard'); @endphp
        <a href="{{ route('dashboard') }}" wire:navigate @class([$item, $active => $isActive, $inactive => ! $isActive])>
            @if($isActive)
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21.71,12.71a1,1,0,0,1-1.42,0L20,12.42V20.3A1.77,1.77,0,0,1,18.17,22H16a1,1,0,0,1-1-1V15.1a1,1,0,0,0-1-1H10a1,1,0,0,0-1,1V21a1,1,0,0,1-1,1H5.83A1.77,1.77,0,0,1,4,20.3V12.42l-.29.29a1,1,0,0,1-1.42,0,1,1,0,0,1,0-1.42l9-9a1,1,0,0,1,1.42,0l9,9A1,1,0,0,1,21.71,12.71Z"/>
                </svg>
            @else
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <polyline points="21 12 12 3 3 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                    <path d="M19,10V20.3a.77.77,0,0,1-.83.7H14.3V14.1H9.7V21H5.83A.77.77,0,0,1,5,20.3V10" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                </svg>
            @endif
            <span class="text-[10px] font-medium leading-none whitespace-nowrap">{{ __('Inicio') }}</span>
        </a>

        @php $isActive = request()->routeIs('periods'); @endphp
        <a href="{{ route('periods') }}" wire:navigate @class([$item, $active => $isActive, $inactive => ! $isActive])>
            @if($isActive)
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22 14V12C22 11.161 22 10.4153 21.9871 9.75H2.0129C2 10.4153 2 11.161 2 12V14C2 17.7712 2 19.6569 3.17157 20.8284C4.34315 22 6.22876 22 10 22H14C17.7712 22 19.6569 22 20.8284 20.8284C22 19.6569 22 17.7712 22 14Z"/>
                    <path d="M7.75 2.5C7.75 2.08579 7.41421 1.75 7 1.75C6.58579 1.75 6.25 2.08579 6.25 2.5V4.07926C4.81067 4.19451 3.86577 4.47737 3.17157 5.17157C2.47737 5.86577 2.19451 6.81067 2.07926 8.25H21.9207C21.8055 6.81067 21.5226 5.86577 20.8284 5.17157C20.1342 4.47737 19.1893 4.19451 17.75 4.07926V2.5C17.75 2.08579 17.4142 1.75 17 1.75C16.5858 1.75 16.25 2.08579 16.25 2.5V4.0129C15.5847 4 14.839 4 14 4H10C9.16097 4 8.41527 4 7.75 4.0129V2.5Z"/>
                </svg>
            @else
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V12Z" stroke-width="1.5"/>
                    <path d="M7 4V2.5" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M17 4V2.5" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M2.5 9H21.5" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            @endif
            <span class="text-[10px] font-medium leading-none whitespace-nowrap">{{ __('Periodo') }}</span>
        </a>

        @php $isActive = request()->routeIs('campuses.index', 'careers.index', 'semesters.index'); @endphp
        <a href="{{ route('campuses.index') }}" wire:navigate @class([$item, $active => $isActive, $inactive => ! $isActive])>
            @if($isActive)
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L2 8L12 14L22 8L12 2Z"/>
                    <path d="M2 12.5L12 18.5L22 12.5L20.5 11.5L12 16.5L3.5 11.5L2 12.5Z"/>
                    <path d="M2 16.5L12 22.5L22 16.5L20.5 15.5L12 20.5L3.5 15.5L2 16.5Z"/>
                </svg>
            @else
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M12 3L3 8L12 13L21 8L12 3Z" stroke-width="1.5" stroke-linejoin="round"/>
                    <path d="M3 12L12 17L21 12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3 16L12 21L21 16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            @endif
            <span class="text-[10px] font-medium leading-none whitespace-nowrap">{{ __('Catálogo') }}</span>
        </a>
    @else
        @php $isActive = request()->routeIs('student-documents.index'); @endphp
        <a href="{{ route('student-documents.index') }}" wire:navigate @class([$item, $active => $isActive, $inactive => ! $isActive])>
            @if($isActive)
                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            @else
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            @endif
            <span class="text-[10px] font-medium leading-none whitespace-nowrap">{{ __('Documentos') }}</span>
        </a>
    @endif

    @php $isActive = request()->routeIs('settings.*', 'admin.create-admin', 'admin.trash'); @endphp
    <a href="{{ route('settings.profile') }}" wire:navigate @class([$item, $active => $isActive, $inactive => ! $isActive])>
        @if($isActive)
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.4277 2C11.3139 2 10.2995 2.6007 8.27081 3.80211L7.58466 4.20846C5.55594 5.40987 4.54158 6.01057 3.98466 7C3.42773 7.98943 3.42773 9.19084 3.42773 11.5937V12.4063C3.42773 14.8092 3.42773 16.0106 3.98466 17C4.54158 17.9894 5.55594 18.5901 7.58466 19.7915L8.27081 20.1979C10.2995 21.3993 11.3139 22 12.4277 22C13.5416 22 14.5559 21.3993 16.5847 20.1979L17.2708 19.7915C19.2995 18.5901 20.3139 17.9894 20.8708 17C21.4277 16.0106 21.4277 14.8092 21.4277 12.4063V11.5937C21.4277 9.19084 21.4277 7.98943 20.8708 7C20.3139 6.01057 19.2995 5.40987 17.2708 4.20846L16.5847 3.80211C14.5559 2.6007 13.5416 2 12.4277 2ZM8.67773 12C8.67773 9.92893 10.3567 8.25 12.4277 8.25C14.4988 8.25 16.1777 9.92893 16.1777 12C16.1777 14.0711 14.4988 15.75 12.4277 15.75C10.3567 15.75 8.67773 14.0711 8.67773 12Z"/>
            </svg>
        @else
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M7.84308 3.80211C9.8718 2.6007 10.8862 2 12 2C13.1138 2 14.1282 2.6007 16.1569 3.80211L16.8431 4.20846C18.8718 5.40987 19.8862 6.01057 20.4431 7C21 7.98943 21 9.19084 21 11.5937V12.4063C21 14.8092 21 16.0106 20.4431 17C19.8862 17.9894 18.8718 18.5901 16.8431 19.7915L16.1569 20.1979C14.1282 21.3993 13.1138 22 12 22C10.8862 22 9.8718 21.3993 7.84308 20.1979L7.15692 19.7915C5.1282 18.5901 4.11384 17.9894 3.55692 17C3 16.0106 3 14.8092 3 12.4063V11.5937C3 9.19084 3 7.98943 3.55692 7C4.11384 6.01057 5.1282 5.40987 7.15692 4.20846L7.84308 3.80211Z" stroke-width="1.5"/>
                <circle cx="12" cy="12" r="3" stroke-width="1.5"/>
            </svg>
        @endif
        <span class="text-[10px] font-medium leading-none whitespace-nowrap">{{ __('Configuración') }}</span>
    </a>
</nav>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" aria-label="{{ __('Cerrar Sesión') }}"
            class="{{ $glass }} flex h-16 w-16 items-center justify-center rounded-full text-[var(--sidebar-color-nav)]">
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
            <path d="M2.95,17.5A2.853,2.853,0,0,1,0,14.75v-12A2.854,2.854,0,0,1,2.95,0h8.8a.75.75,0,0,1,0,1.5H2.95A1.362,1.362,0,0,0,1.5,2.75v12A1.363,1.363,0,0,0,2.95,16h8.8a.75.75,0,0,1,0,1.5Zm9.269-4.219a.751.751,0,0,1,0-1.061L14.939,9.5H5.75a.75.75,0,0,1,0-1.5h9.19L12.219,5.28A.75.75,0,1,1,13.28,4.22l4,4a.749.749,0,0,1,0,1.06l-4,4a.751.751,0,0,1-1.061,0Z" transform="translate(3.25 3.25)"/>
        </svg>
    </button>
</form>
</div>
