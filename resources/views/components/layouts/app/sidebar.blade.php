<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[var(--color-section-bg)] text-[var(--text-section-title)]">

        <flux:sidebar sticky stashable class="w-64 min-h-screen border-e border-[var(--sidebar-border)] bg-gradient-to-b from-[var(--sidebar-bg)] to-[var(--sidebar-bg-alt)] dark:border-[var(--sidebar-border)] dark:from-[var(--sidebar-bg)] dark:to-[var(--sidebar-bg-alt)]">

            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">

                <!-- SECCIÓN PRINCIPAL -->
                <flux:navlist.group :heading="__('Plataforma')" class="grid">
                    
                    <flux:navlist.item 
                        icon="home" 
                        :href="route('dashboard')" 
                        :current="request()->routeIs('dashboard')" 
                        wire:navigate
                    >
                        {{ __('Inicio') }}
                    </flux:navlist.item>
                </flux:navlist.group>

                <!-- Línea separadora -->
                <div class="border-t border-[var(--color-divider)] my-2"></div>

                <!-- SECCIÓN DE ADMINISTRACIÓN ACADÉMICA -->
                @if(auth()->user()->hasRole('admin'))
                <flux:navlist.group :heading="__('Administración')" class="grid">
                    <flux:navlist.item 
                        icon="calendar" 
                        :href="route('periods.index')" 
                        :current="request()->routeIs('periods.index')" 
                        wire:navigate
                        class="data-[active=true]:bg-[var(--color-primary-light)] data-[active=true]:text-[var(--color-primary)]"
                    >
                        {{ __('Periodos') }}
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="building-library" 
                        :href="route('campuses.index')" 
                        :current="request()->routeIs('campuses.index')" 
                        wire:navigate
                        class="data-[active=true]:bg-[var(--color-primary-light)] data-[active=true]:text-[var(--color-primary)]"
                    >
                        {{ __('Campus') }}
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="book-open" 
                        :href="route('careers.index')" 
                        :current="request()->routeIs('careers.index')" 
                        wire:navigate
                        class="data-[active=true]:bg-[var(--color-primary-light)] data-[active=true]:text-[var(--color-primary)]"
                    >
                        {{ __('Carreras') }}
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="academic-cap" 
                        :href="route('semesters.index')" 
                        :current="request()->routeIs('semesters.index')" 
                        wire:navigate
                        class="data-[active=true]:bg-[var(--color-primary-light)] data-[active=true]:text-[var(--color-primary)]"
                    >
                        {{ __('Semestres') }}
                    </flux:navlist.item>
                </flux:navlist.group>

                <!-- Línea separadora -->
                <div class="border-t border-[var(--color-divider)] my-2"></div>

                <!-- SECCIÓN DE APROBACIONES -->
                <flux:navlist.group :heading="__('Aprobación de Estudiantes')" class="grid">
                    <flux:navlist.item 
                        icon="check-circle" 
                        :href="route('admin.students-approval')" 
                        :current="request()->routeIs('admin.students-approval')" 
                        wire:navigate
                        class="data-[active=true]:bg-[var(--color-primary-light)] data-[active=true]:text-[var(--color-primary)]"
                    >
                        {{ __('Aprobación') }}
                    </flux:navlist.item>
                </flux:navlist.group>
                
                <!-- Línea separadora -->
                <div class="border-t border-[var(--color-divider)] my-2"></div>

                <!-- SECCIÓN DE DOCUMENTOS -->
                <flux:navlist.group :heading="__('Gestión de Documentos')" class="grid">
                    <flux:navlist.item 
                        icon="folder" 
                        :href="route('files.index')" 
                        :current="request()->routeIs('files.index')" 
                        wire:navigate
                        class="data-[active=true]:bg-[var(--color-primary-light)] data-[active=true]:text-[var(--color-primary)]"
                    >
                        {{ __('Archivos Base') }}
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="folder-open" 
                        :href="route('students.index')" 
                        :current="request()->routeIs('students.index')" 
                        wire:navigate
                        class="data-[active=true]:bg-[var(--color-primary-light)] data-[active=true]:text-[var(--color-primary)]"
                    >
                        {{ __('Doc. de Estudiantes') }}
                    </flux:navlist.item>
                </flux:navlist.group>

                @endif


                <!-- SECCIÓN DEL ESTUDIANTE -->
                @if(!auth()->user()->hasRole('admin'))
                    <flux:navlist.group :heading="__('Mi Perfil')" class="grid">
                        <flux:navlist.item 
                            icon="user" 
                            :href="route('students.profile')" 
                            :current="request()->routeIs('students.profile')" 
                            wire:navigate
                            class="data-[active=true]:bg-[var(--color-primary-light)] data-[active=true]:text-[var(--color-primary)]"
                        >
                            {{ __('Perfil') }}
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="folder" 
                            :href="route('student-documents.index')" 
                            :current="request()->routeIs('student-documents.index')" 
                            wire:navigate
                            class="data-[active=true]:bg-[var(--color-primary-light)] data-[active=true]:text-[var(--color-primary)]"
                        >
                            {{ __('Mis Documentos') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                @endif


            </flux:navlist>


            <flux:spacer />

            <!-- <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist> -->

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px] bg-[var(--color-surface-elevated)] border border-[var(--color-border)] shadow-[var(--shadow-md)]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-[var(--color-primary-light)] text-[var(--color-text-inverse)]"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator class="border-[var(--color-divider)]" />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Configuración') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator class="border-[var(--color-divider)]" />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden bg-[var(--color-surface)] border-b border-[var(--color-border)]">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu class="bg-[var(--color-surface-elevated)] border border-[var(--color-border)] shadow-[var(--shadow-md)]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-[var(--color-primary-light)] text-[var(--color-text-inverse)]"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator class="border-[var(--color-divider)]" />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Configuración') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator class="border-[var(--color-divider)]" />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
