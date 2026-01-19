<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#2D7A4F">
    <title>Servicio Social - ITSCO</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|inter:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css'])
</head>


<body class="bg-[var(--color-background)] text-[var(--color-text-primary)] font-[var(--font-sans)] transition-colors duration-200">
<div class="min-h-screen flex flex-col">

    {{-- Header --}}
    <header
        class="sticky top-0 z-50
        bg-gradient-to-b from-[var(--color-header-bg-1)] to-[var(--color-header-bg-2)]
        backdrop-blur-xl
        border-b border-[var(--color-border-header)]
        shadow-sm
        animate-fade-in-down duration-normal">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ url('/') }}"
                    class="flex items-center gap-3 group
                        transition-transform duration-200 hover:scale-105
                        animate-fade-in-left delay-100">

                    {{-- Logo con fondo blanco --}}
                    <div class="w-10 h-10 rounded-[var(--radius-md)] bg-white flex items-center justify-center overflow-hidden shadow-sm">
                        @if($welcome && $welcome->logo)
                            <img src="{{ asset('storage/' . $welcome->logo) }}" 
                                alt="{{ $welcome->abreviatura ?? 'Logo' }}" 
                                class="w-full h-full object-contain">

                        @else
                            {{-- Fallback si no hay logo --}}
                            <span class="text-gray-800 font-bold">{{ $welcome->abreviatura ?? 'ITSCO' }}</span>
                        @endif
                    </div>

                    {{-- Títulos --}}
                    <div class="flex flex-col leading-tight">
                        <span
                            class="text-[length:var(--text-base)]
                                font-[var(--font-semibold)]
                                text-[var(--text-logo-header-1)]
                                animate-fade-in-up delay-150">
                            {{ $welcome->header_title ?? 'Servicio Social' }}
                        </span>

                        <span
                            class="text-[length:var(--text-xs)]
                                text-[var(--text-logo-header-2)]
                                hidden sm:block
                                animate-fade-in-up delay-200">
                            {{ $welcome->header_subtitle ?? 'ITSCO' }}
                        </span>
                    </div>
                </a>


                {{-- Actions --}}
                <div class="flex items-center gap-3 animate-fade-in-right delay-150">

                    {{-- Auth --}}
                    @if (Route::has('login'))
                        <nav class="flex items-center gap-2 animate-fade-in delay-250">

                            @auth
                                @if(auth()->user()->hasRole('admin'))
                                    {{-- Admin → Dashboard --}}
                                    <a href="{{ route('dashboard') }}"
                                    class="px-4 py-2
                                            rounded-[var(--radius-md)]
                                            bg-[var(--color-header-bg)]
                                            text-[var(--text-header)]
                                            font-[var(--font-medium)]
                                            hover:bg-[var(--color-header-bg-hover)]
                                            transition-colors duration-200
                                            transition-transform duration-300 hover:scale-105 hover-glow
                                            animate-fade-in-up delay-300">
                                        Dashboard
                                    </a>
                                @else
                                    {{-- Usuario normal / Estudiante → Perfil --}}
                                    <a href="{{ route('students.profile') }}"
                                    class="px-4 py-2
                                            rounded-[var(--radius-md)]
                                            bg-[var(--color-header-bg)]
                                            text-[var(--text-header)]
                                            font-[var(--font-medium)]
                                            hover:bg-[var(--color-header-bg-hover)]
                                            transition-colors duration-200
                                            transition-transform duration-300 hover:scale-105 hover-glow
                                            animate-fade-in-up delay-300">
                                        Mi perfil
                                    </a>
                                @endif
                            @else
                                {{-- Invitado --}}
                                <a href="{{ route('login') }}"
                                class="px-3 py-2
                                        rounded-[var(--radius-md)]
                                        text-[var(--text-login-header)]
                                        font-[var(--font-medium)]
                                        transition-colors duration-200
                                        transition-transform duration-200 hover:scale-105
                                        animate-fade-in-up delay-300">
                                    Iniciar sesión
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                    class="px-4 py-2
                                            rounded-[var(--radius-md)]
                                            bg-[var(--color-header-bg)]
                                            text-[var(--text-header)]
                                            font-[var(--font-medium)]
                                            hover:bg-[var(--color-header-bg-hover)]
                                            transition-colors duration-200
                                            transition-transform duration-300 hover:scale-105 hover-glow
                                            animate-fade-in-up delay-300">
                                        Registrarse
                                    </a>
                                @endif
                            @endauth

                        </nav>
                    @endif


                </div>
            </div>
        </div>
    </header>

    {{-- Hero Institucional Mejorado --}}
    <section class="relative bg-gradient-to-br from-[var(--color-hero-bg-1)] via-[var(--color-hero-bg-2)] to-[var(--color-hero-bg-1)] border-b border-[var(--color-border-hero)] overflow-hidden">
        
        {{-- Elementos decorativos de fondo --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.03)_1px,transparent_1px)] bg-[size:64px_64px] opacity-40 animate-[grid-fade_20s_ease-in-out_infinite]"></div>
            <div class="absolute top-[-20%] left-[-15%] w-[600px] h-[600px] bg-[var(--color-hero-accent-primary)] rounded-full blur-[120px] opacity-20 animate-[pulse_8s_ease-in-out_infinite]"></div>
            <div class="absolute bottom-[-20%] right-[-15%] w-[550px] h-[550px] bg-[var(--color-hero-accent-secondary)] rounded-full blur-[120px] opacity-20 animate-[pulse_10s_ease-in-out_infinite] animation-delay-2s"></div>
            <div class="absolute inset-0">
                <div class="absolute top-[20%] left-[10%] w-2 h-2 bg-[var(--color-hero-accent-primary)] rounded-full opacity-40 animate-[float_6s_ease-in-out_infinite]"></div>
                <div class="absolute top-[60%] left-[15%] w-1.5 h-1.5 bg-[var(--color-hero-accent-secondary)] rounded-full opacity-30 animate-[float_8s_ease-in-out_infinite] animation-delay-1s"></div>
                <div class="absolute top-[30%] right-[12%] w-2 h-2 bg-[var(--color-hero-accent-primary)] rounded-full opacity-40 animate-[float_7s_ease-in-out_infinite] animation-delay-3s"></div>
                <div class="absolute bottom-[40%] right-[18%] w-1.5 h-1.5 bg-[var(--color-hero-accent-secondary)] rounded-full opacity-30 animate-[float_9s_ease-in-out_infinite] animation-delay-2s"></div>
            </div>
        </div>
        
        <div class="relative max-w-6xl mx-auto px-4 py-24 sm:py-32 lg:py-40 text-center">
            
            {{-- Badge institucional --}}
            <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[var(--color-badge-bg)] backdrop-blur-sm border border-[var(--color-badge-border)] text-[var(--color-badge-text)] text-sm font-[var(--font-semibold)] mb-8 animate-fade-in-down hover:scale-105 hover:border-[var(--color-hero-accent-primary)] transition-all duration-300 cursor-default shadow-lg shadow-[var(--color-hero-glow-subtle)]">
                <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span class="tracking-wider">{{ $welcome->indicador ?? 'Agiliza en línea' }}</span>
            </div>

            {{-- Título principal --}}
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-[var(--font-bold)] mb-6 animate-fade-in-up animation-delay-100 leading-[1.1]">
                <span class="text-[var(--text-hero-title)] block mb-2">{{ $welcome->main_encabezado ?? 'Gestión de' }}</span>
                <span class="bg-gradient-to-r from-[var(--color-hero-accent-primary)] via-[var(--text-hero-highlight)] to-[var(--color-hero-accent-secondary)] bg-clip-text text-transparent animate-gradient bg-[length:200%_auto]">{{ $welcome->main_encabezado2 ?? 'Servicio Social' }}</span>
            </h1>

            {{-- Información institucional --}}
            <div class="mb-10 animate-fade-in-up animation-delay-200">
                <p class="text-[length:var(--text-xl)] text-[var(--text-hero-subtitle)] font-[var(--font-medium)] mb-3">
                    {{ $welcome->main_subencabezado ?? 'Instituto Tecnológico Superior de Cosamaloapan' }}
                </p>
            </div>

            {{-- Características clave --}}
            <div class="flex flex-wrap items-center justify-center gap-6 mb-10 animate-fade-in-up delay-300">
                <div class="flex items-center gap-2 text-[var(--text-hero-body)] text-[length:var(--text-sm)]">
                    <svg class="w-5 h-5 text-[var(--color-hero-accent-primary)]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-[var(--font-medium)]">Fácil de usar</span>
                </div>
                <div class="flex items-center gap-2 text-[var(--text-hero-body)] text-[length:var(--text-sm)]">
                    <svg class="w-5 h-5 text-[var(--color-hero-accent-primary)]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-[var(--font-medium)]">Seguro</span>
                </div>
                <div class="flex items-center gap-2 text-[var(--text-hero-body)] text-[length:var(--text-sm)]">
                    <svg class="w-5 h-5 text-[var(--color-hero-accent-primary)]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-[var(--font-medium)]">Ahorra tiempo</span>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up animation-delay-400 mb-16">
                <a href="#informacion" class="group inline-flex items-center justify-center gap-2 w-full sm:w-auto px-8 py-3.5 rounded-[var(--radius-md)] bg-[var(--color-btn-primary)] text-[var(--text-btn-primary)] font-[var(--font-semibold)] text-[length:var(--text-base)] shadow-lg shadow-[var(--color-btn-primary-shadow)] hover:bg-[var(--color-btn-primary-hover)] hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                    <span>Conocer más</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                @guest
                    <a href="{{ route('login') }}" class="group inline-flex items-center justify-center gap-2 w-full sm:w-auto px-8 py-3.5 rounded-[var(--radius-md)] bg-[var(--color-btn-secondary)] border-2 border-[var(--color-btn-secondary-border)] text-[var(--text-btn-secondary)] font-[var(--font-semibold)] text-[length:var(--text-base)] shadow-md hover:bg-[var(--color-btn-secondary-hover)] hover:-translate-y-0.5 transition-all duration-300">
                        <span>Iniciar sesión</span>
                    </a>
                @endguest
            </div>

        </div>

        <!-- Indicador de scroll tipo mouse animado -->
        <div class="absolute bottom-32 left-1/2 -translate-x-1/2 animate-mouse-bounce">
            <div class="w-6 h-10 rounded-full border-2 border-[var(--text-hero-body)] flex justify-center items-start p-1">
                <!-- Bolita animada simulando scroll -->
                <div class="w-1 h-2 bg-[var(--text-hero-body)] rounded-full animate-scroll"></div>
            </div>
        </div>
    </section>

    {{-- Main --}}
    <main id="informacion" class="flex-1">

        {{-- Sección ¿Qué es? --}}
        <section id="informacion" class="relative bg-[var(--color-section-bg)] overflow-hidden">
            
            {{-- Decoración de fondo sutil --}}
            <div class="absolute inset-0 opacity-30">
                <div class="absolute inset-0 bg-[linear-gradient(rgba(30,64,175,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(30,64,175,0.02)_1px,transparent_1px)] bg-[size:32px_32px]"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 py-20 sm:py-28">
                
                {{-- Encabezado de sección --}}
                <div class="text-center mb-16 animate-fade-in-up">
                    {{-- Badge de sección --}}
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full
                                bg-[var(--color-icon-bg)] 
                                text-[var(--color-icon-text)] text-[length:var(--text-xs)]
                                font-[var(--font-semibold)] mb-4
                                uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Información general
                    </div>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-[var(--font-bold)] text-[var(--text-section-title)] mb-4">
                        ¿Qué es el Servicio Social?
                    </h2>
                    <p class="text-[length:var(--text-lg)] sm:text-[length:var(--text-xl)] text-[var(--text-section-subtitle)] max-w-3xl mx-auto leading-relaxed">
                        Una actividad académica obligatoria que forma parte integral de tu formación profesional
                    </p>
                </div>

                {{-- Grid de cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 max-w-5xl mx-auto">
                    
                    {{-- Card 1: Formación integral --}}
                    <div class="group p-6 lg:p-7 rounded-[var(--radius-lg)] 
                                bg-[var(--color-card-bg)] 
                                border-2 border-[var(--color-card-border)]
                                shadow-[var(--shadow-sm)]
                                hover:border-[var(--color-icon-text)]
                                hover:shadow-xl hover:shadow-[var(--color-card-shadow-hover)]
                                hover:-translate-y-2
                                transition-all duration-300 
                                animate-fade-in-up delay-100">
                        
                        {{-- Icono --}}
                        <div class="mb-5">
                            <div class="w-14 h-14 rounded-[var(--radius-md)] 
                                        bg-[var(--color-icon-bg)] 
                                        flex items-center justify-center
                                        group-hover:scale-110
                                        transition-transform duration-300">
                                <svg class="w-7 h-7 text-[var(--color-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Contenido --}}
                        <h3 class="text-[length:var(--text-xl)] font-[var(--font-bold)] text-[var(--text-card-title)] mb-3">
                            Formación integral
                        </h3>
                        <p class="text-[length:var(--text-base)] text-[var(--text-card-body)] leading-relaxed">
                            Complementa tu preparación académica mediante la práctica de conocimientos en contextos reales de aplicación profesional.
                        </p>
                    </div>

                    {{-- Card 2: Compromiso social --}}
                    <div class="group p-6 lg:p-7 rounded-[var(--radius-lg)] 
                                bg-[var(--color-card-bg)] 
                                border-2 border-[var(--color-card-border)]
                                shadow-[var(--shadow-sm)]
                                hover:border-[var(--color-icon-text)]
                                hover:shadow-xl hover:shadow-[var(--color-card-shadow-hover)]
                                hover:-translate-y-2
                                transition-all duration-300 
                                animate-fade-in-up delay-200">
                        
                        {{-- Icono --}}
                        <div class="mb-5">
                            <div class="w-14 h-14 rounded-[var(--radius-md)] 
                                        bg-[var(--color-icon-bg)] 
                                        flex items-center justify-center
                                        group-hover:scale-110
                                        transition-transform duration-300">
                                <svg class="w-7 h-7 text-[var(--color-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Contenido --}}
                        <h3 class="text-[length:var(--text-xl)] font-[var(--font-bold)] text-[var(--text-card-title)] mb-3">
                            Compromiso social
                        </h3>
                        <p class="text-[length:var(--text-base)] text-[var(--text-card-body)] leading-relaxed">
                            Contribuye al desarrollo y bienestar de comunidades, aplicando tus conocimientos en beneficio de la sociedad.
                        </p>
                    </div>

                    {{-- Card 3: Valores profesionales --}}
                    <div class="group p-6 lg:p-7 rounded-[var(--radius-lg)] 
                                bg-[var(--color-card-bg)] 
                                border-2 border-[var(--color-card-border)]
                                shadow-[var(--shadow-sm)]
                                hover:border-[var(--color-icon-text)]
                                hover:shadow-xl hover:shadow-[var(--color-card-shadow-hover)]
                                hover:-translate-y-2
                                transition-all duration-300 
                                animate-fade-in-up delay-300">
                        
                        {{-- Icono --}}
                        <div class="mb-5">
                            <div class="w-14 h-14 rounded-[var(--radius-md)] 
                                        bg-[var(--color-icon-bg)] 
                                        flex items-center justify-center
                                        group-hover:scale-110
                                        transition-transform duration-300">
                                <svg class="w-7 h-7 text-[var(--color-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Contenido --}}
                        <h3 class="text-[length:var(--text-xl)] font-[var(--font-bold)] text-[var(--text-card-title)] mb-3">
                            Valores profesionales
                        </h3>
                        <p class="text-[length:var(--text-base)] text-[var(--text-card-body)] leading-relaxed">
                            Fortalece tu ética profesional, responsabilidad y compromiso con el entorno a través de experiencias significativas.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        {{-- Sección Objetivo --}}
        <section class="relative bg-[var(--color-objetivo-bg)] border-y border-[var(--color-objetivo-border)] overflow-hidden">
            
            {{-- Decoración de fondo con patrón sutil --}}
            <div class="absolute inset-0 opacity-30">
                <div class="absolute inset-0 bg-[linear-gradient(rgba(30,64,175,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(30,64,175,0.03)_1px,transparent_1px)] bg-[size:40px_40px]"></div>
                
                {{-- Círculos decorativos sutiles --}}
                <div class="absolute top-[20%] right-[5%] w-[300px] h-[300px] bg-[var(--color-objetivo-icon-bg)] rounded-full blur-[100px] opacity-10"></div>
                <div class="absolute bottom-[20%] left-[5%] w-[250px] h-[250px] bg-[var(--color-objetivo-check)] rounded-full blur-[100px] opacity-10"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 py-20 sm:py-28">
                <div class="max-w-4xl mx-auto">
                    
                    {{-- Card principal con efecto elevado --}}
                    <div class="p-8 sm:p-10 lg:p-12 rounded-[var(--radius-xl)] 
                                bg-[var(--color-objetivo-card-bg)]
                                border-2 border-[var(--color-objetivo-card-border)]
                                shadow-xl shadow-[var(--color-objetivo-card-shadow)]
                                hover:shadow-2xl
                                transition-shadow duration-300
                                animate-fade-in-up">
                        
                        {{-- Encabezado de la card --}}
                        <div class="flex items-start gap-4 sm:gap-6 mb-8">
                            {{-- Icono principal --}}
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-[var(--radius-lg)] 
                                        bg-[var(--color-objetivo-icon-bg)] 
                                        flex items-center justify-center flex-shrink-0
                                        shadow-lg shadow-[var(--color-objetivo-icon-bg)]/30">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-[var(--color-objetivo-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            
                            {{-- Títulos --}}
                            <div class="flex-1">
                                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-[var(--font-bold)] text-[var(--text-objetivo-title)] mb-2">
                                    Objetivo del Servicio Social
                                </h2>
                                <p class="text-[length:var(--text-base)] sm:text-[length:var(--text-lg)] text-[var(--text-objetivo-subtitle)] font-[var(--font-medium)]">
                                    El propósito fundamental de esta actividad académica
                                </p>
                            </div>
                        </div>

                        {{-- Línea divisoria sutil --}}
                        <div class="h-px bg-gradient-to-r from-transparent via-[var(--color-objetivo-card-border)] to-transparent mb-8"></div>

                        {{-- Lista de objetivos --}}
                        <div class="space-y-5 lg:space-y-6">
                            
                            {{-- Objetivo 1 --}}
                            <div class="flex items-start gap-4 group">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded-full bg-[var(--color-objetivo-check)]/10 
                                                flex items-center justify-center
                                                group-hover:scale-110 transition-transform duration-200">
                                        <svg class="w-5 h-5 text-[var(--color-objetivo-check)]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-[length:var(--text-base)] sm:text-[length:var(--text-lg)] text-[var(--text-objetivo-body)] leading-relaxed">
                                    Aplicar los conocimientos y competencias adquiridos durante tu formación académica en situaciones reales de trabajo.
                                </p>
                            </div>

                            {{-- Objetivo 2 --}}
                            <div class="flex items-start gap-4 group">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded-full bg-[var(--color-objetivo-check)]/10 
                                                flex items-center justify-center
                                                group-hover:scale-110 transition-transform duration-200">
                                        <svg class="w-5 h-5 text-[var(--color-objetivo-check)]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-[length:var(--text-base)] sm:text-[length:var(--text-lg)] text-[var(--text-objetivo-body)] leading-relaxed">
                                    Contribuir al desarrollo social y comunitario mediante proyectos que generen un impacto positivo en la población.
                                </p>
                            </div>

                            {{-- Objetivo 3 --}}
                            <div class="flex items-start gap-4 group">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded-full bg-[var(--color-objetivo-check)]/10 
                                                flex items-center justify-center
                                                group-hover:scale-110 transition-transform duration-200">
                                        <svg class="w-5 h-5 text-[var(--color-objetivo-check)]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-[length:var(--text-base)] sm:text-[length:var(--text-lg)] text-[var(--text-objetivo-body)] leading-relaxed">
                                    Fortalecer valores profesionales, éticos y de responsabilidad social que te distingan como egresado del ITSCO.
                                </p>
                            </div>

                        </div>

                        {{-- Nota adicional opcional --}}
                        <div class="mt-8 pt-6 border-t border-[var(--color-objetivo-card-border)]">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-[var(--color-objetivo-check)] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-[length:var(--text-sm)] sm:text-[length:var(--text-base)] text-[var(--text-objetivo-subtitle)] italic">
                                    El cumplimiento del servicio social es un requisito indispensable para obtener tu título profesional.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </section>

        {{-- Sección Requisitos Generales --}}
        <section class="relative bg-[var(--color-requisitos-bg)] overflow-hidden">
            
            {{-- Decoración de fondo sutil --}}
            <div class="absolute inset-0 opacity-30">
                <div class="absolute inset-0 bg-[linear-gradient(rgba(30,64,175,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(30,64,175,0.02)_1px,transparent_1px)] bg-[size:32px_32px]"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 py-20 sm:py-28">
                
                {{-- Encabezado de sección --}}
                <div class="text-center mb-16 animate-fade-in-up">
                    
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-[var(--font-bold)] text-[var(--text-requisitos-title)] mb-4">
                        Requisitos Generales
                    </h2>
                    <p class="text-[length:var(--text-lg)] sm:text-[length:var(--text-xl)] text-[var(--text-requisitos-subtitle)] max-w-2xl mx-auto leading-relaxed">
                        Antes de iniciar tu servicio social, verifica que cumples con los siguientes criterios
                    </p>
                </div>

                {{-- Grid de cards de requisitos --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 lg:gap-8 max-w-4xl mx-auto">
                    
                    {{-- Card 1: Avance académico --}}
                    <div class="group p-6 lg:p-7 rounded-[var(--radius-lg)] 
                                bg-[var(--color-requisitos-card-bg)] 
                                border-2 border-[var(--color-requisitos-card-border)]
                                shadow-[var(--shadow-sm)]
                                hover:border-[var(--color-requisitos-icon-text)]
                                hover:shadow-xl hover:shadow-[var(--color-requisitos-card-shadow-hover)]
                                hover:-translate-y-2
                                transition-all duration-300 
                                animate-fade-in-up delay-100">
                        
                        <div class="flex items-start gap-4">
                            {{-- Icono --}}
                            <div class="w-12 h-12 rounded-[var(--radius-md)] 
                                        bg-[var(--color-requisitos-icon-bg)] 
                                        flex items-center justify-center flex-shrink-0
                                        group-hover:scale-110
                                        transition-transform duration-300">
                                <svg class="w-6 h-6 text-[var(--color-requisitos-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            
                            {{-- Contenido --}}
                            <div>
                                <h3 class="text-[length:var(--text-lg)] font-[var(--font-bold)] text-[var(--text-requisitos-card-title)] mb-2">
                                    Avance académico
                                </h3>
                                <p class="text-[length:var(--text-base)] text-[var(--text-requisitos-card-body)] leading-relaxed">
                                    Al menos el {{ $welcome->requisito_avance_academico ?? '70%' }} de créditos aprobados de tu plan de estudios
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Inscripción vigente --}}
                    <div class="group p-6 lg:p-7 rounded-[var(--radius-lg)] 
                                bg-[var(--color-requisitos-card-bg)] 
                                border-2 border-[var(--color-requisitos-card-border)]
                                shadow-[var(--shadow-sm)]
                                hover:border-[var(--color-requisitos-icon-text)]
                                hover:shadow-xl hover:shadow-[var(--color-requisitos-card-shadow-hover)]
                                hover:-translate-y-2
                                transition-all duration-300 
                                animate-fade-in-up delay-200">
                        
                        <div class="flex items-start gap-4">
                            {{-- Icono --}}
                            <div class="w-12 h-12 rounded-[var(--radius-md)] 
                                        bg-[var(--color-requisitos-icon-bg)] 
                                        flex items-center justify-center flex-shrink-0
                                        group-hover:scale-110
                                        transition-transform duration-300">
                                <svg class="w-6 h-6 text-[var(--color-requisitos-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            
                            {{-- Contenido --}}
                            <div>
                                <h3 class="text-[length:var(--text-lg)] font-[var(--font-bold)] text-[var(--text-requisitos-card-title)] mb-2">
                                    Inscripción vigente
                                </h3>
                                <p class="text-[length:var(--text-base)] text-[var(--text-requisitos-card-body)] leading-relaxed">
                                    Estar inscrito oficialmente en el periodo académico en curso
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Normativa institucional --}}
                    <div class="group p-6 lg:p-7 rounded-[var(--radius-lg)] 
                                bg-[var(--color-requisitos-card-bg)] 
                                border-2 border-[var(--color-requisitos-card-border)]
                                shadow-[var(--shadow-sm)]
                                hover:border-[var(--color-requisitos-icon-text)]
                                hover:shadow-xl hover:shadow-[var(--color-requisitos-card-shadow-hover)]
                                hover:-translate-y-2
                                transition-all duration-300 
                                animate-fade-in-up delay-300">
                        
                        <div class="flex items-start gap-4">
                            {{-- Icono --}}
                            <div class="w-12 h-12 rounded-[var(--radius-md)] 
                                        bg-[var(--color-requisitos-icon-bg)] 
                                        flex items-center justify-center flex-shrink-0
                                        group-hover:scale-110
                                        transition-transform duration-300">
                                <svg class="w-6 h-6 text-[var(--color-requisitos-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            
                            {{-- Contenido --}}
                            <div>
                                <h3 class="text-[length:var(--text-lg)] font-[var(--font-bold)] text-[var(--text-requisitos-card-title)] mb-2">
                                    Normativa institucional
                                </h3>
                                <p class="text-[length:var(--text-base)] text-[var(--text-requisitos-card-body)] leading-relaxed">
                                    Cumplir con el reglamento de servicio social del ITSCO
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 4: Sin adeudos --}}
                    <div class="group p-6 lg:p-7 rounded-[var(--radius-lg)] 
                                bg-[var(--color-requisitos-card-bg)] 
                                border-2 border-[var(--color-requisitos-card-border)]
                                shadow-[var(--shadow-sm)]
                                hover:border-[var(--color-requisitos-icon-text)]
                                hover:shadow-xl hover:shadow-[var(--color-requisitos-card-shadow-hover)]
                                hover:-translate-y-2
                                transition-all duration-300 
                                animate-fade-in-up delay-400">
                        
                        <div class="flex items-start gap-4">
                            {{-- Icono --}}
                            <div class="w-12 h-12 rounded-[var(--radius-md)] 
                                        bg-[var(--color-requisitos-icon-bg)] 
                                        flex items-center justify-center flex-shrink-0
                                        group-hover:scale-110
                                        transition-transform duration-300">
                                <svg class="w-6 h-6 text-[var(--color-requisitos-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            
                            {{-- Contenido --}}
                            <div>
                                <h3 class="text-[length:var(--text-lg)] font-[var(--font-bold)] text-[var(--text-requisitos-card-title)] mb-2">
                                    Sin adeudos
                                </h3>
                                <p class="text-[length:var(--text-base)] text-[var(--text-requisitos-card-body)] leading-relaxed">
                                    No tener pendientes administrativos ni financieros con la institución
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Nota informativa opcional --}}
                <div class="mt-12 max-w-3xl mx-auto">
                    <div class="p-5 rounded-[var(--radius-md)] 
                                bg-[var(--color-requisitos-icon-bg)] 
                                border border-[var(--color-requisitos-icon-text)]/20
                                animate-fade-in-up delay-500">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-[var(--color-requisitos-icon-text)] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-[length:var(--text-sm)] sm:text-[length:var(--text-base)] text-[var(--text-requisitos-card-title)] leading-relaxed">
                                <span class="font-[var(--font-semibold)]">Nota importante:</span> Verifica con el departamento de servicios escolares que cumples todos los requisitos antes de iniciar tu registro.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- Sección Duración y Créditos --}}
        <section class="relative bg-[var(--color-duracion-bg)] border-y border-[var(--color-duracion-border)] overflow-hidden">
            
            {{-- Decoración de fondo con patrón sutil --}}
            <div class="absolute inset-0 opacity-30">
                <div class="absolute inset-0 bg-[linear-gradient(rgba(30,64,175,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(30,64,175,0.03)_1px,transparent_1px)] bg-[size:40px_40px]"></div>
                
                {{-- Círculos decorativos sutiles --}}
                <div class="absolute top-[15%] left-[5%] w-[350px] h-[350px] bg-[var(--color-duracion-icon-text)] rounded-full blur-[100px] opacity-10"></div>
                <div class="absolute bottom-[15%] right-[5%] w-[300px] h-[300px] bg-[var(--color-duracion-number)] rounded-full blur-[100px] opacity-10"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 py-20 sm:py-28">
                
                {{-- Encabezado de sección --}}
                <div class="text-center mb-16 animate-fade-in-up">

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-[var(--font-bold)] text-[var(--text-duracion-title)] mb-4">
                        Duración y Créditos
                    </h2>
                    <p class="text-[length:var(--text-lg)] sm:text-[length:var(--text-xl)] text-[var(--text-duracion-subtitle)] max-w-2xl mx-auto leading-relaxed">
                        Información sobre el tiempo y valor curricular del servicio social
                    </p>
                </div>

                {{-- Grid de estadísticas --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 lg:gap-8 max-w-4xl mx-auto">
                    
                    {{-- Card 1: Horas totales (destacada) --}}
                    <div class="group p-8 lg:p-10 rounded-[var(--radius-xl)] 
                                bg-[var(--color-duracion-card-bg)]
                                border-2 border-[var(--color-duracion-card-border-highlight)]
                                shadow-xl shadow-[var(--color-duracion-card-shadow)]
                                hover:shadow-2xl hover:-translate-y-2
                                transition-all duration-300
                                text-center
                                animate-fade-in-up delay-100">
                        
                        {{-- Icono --}}
                        <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-[var(--radius-lg)] 
                                    bg-[var(--color-duracion-icon-bg)] 
                                    flex items-center justify-center mx-auto mb-6
                                    group-hover:scale-110
                                    transition-transform duration-300">
                            <svg class="w-8 h-8 lg:w-10 lg:h-10 text-[var(--color-duracion-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        
                        {{-- Número --}}
                        <div class="text-5xl lg:text-6xl font-[var(--font-bold)] text-[var(--color-duracion-number)] mb-3">
                            {{ $welcome->horas_total ?? '500' }}
                        </div>
                        
                        {{-- Label --}}
                        <p class="text-[length:var(--text-base)] font-[var(--font-semibold)] text-[var(--text-duracion-label)]">
                            Horas totales
                        </p>
                    </div>

                    {{-- Card 2: Créditos --}}
                    <div class="group p-8 lg:p-10 rounded-[var(--radius-xl)] 
                                bg-[var(--color-duracion-card-bg)]
                                border-2 border-[var(--color-duracion-card-border)]
                                shadow-lg shadow-[var(--color-duracion-card-shadow)]
                                hover:shadow-xl hover:-translate-y-2
                                transition-all duration-300
                                text-center
                                animate-fade-in-up delay-200">
                        
                        {{-- Icono --}}
                        <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-[var(--radius-lg)] 
                                    bg-[var(--color-duracion-icon-bg)] 
                                    flex items-center justify-center mx-auto mb-6
                                    group-hover:scale-110
                                    transition-transform duration-300">
                            <svg class="w-8 h-8 lg:w-10 lg:h-10 text-[var(--color-duracion-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        
                        {{-- Número --}}
                        <div class="text-5xl lg:text-6xl font-[var(--font-bold)] text-[var(--color-duracion-number)] mb-3">
                            {{ $welcome->creditos ?? '10' }}
                        </div>
                        
                        {{-- Label --}}
                        <p class="text-[length:var(--text-base)] font-[var(--font-semibold)] text-[var(--text-duracion-label)]">
                            Créditos
                        </p>
                    </div>

                    {{-- Card 3: Meses máximo --}}
                    <div class="group p-8 lg:p-10 rounded-[var(--radius-xl)] 
                                bg-[var(--color-duracion-card-bg)]
                                border-2 border-[var(--color-duracion-card-border)]
                                shadow-lg shadow-[var(--color-duracion-card-shadow)]
                            
                                hover:shadow-xl hover:-translate-y-2
                                transition-all duration-300
                                text-center
                                animate-fade-in-up delay-300">
                        
                        {{-- Icono --}}
                        <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-[var(--radius-lg)] 
                                    bg-[var(--color-duracion-icon-bg)] 
                                    flex items-center justify-center mx-auto mb-6
                                    group-hover:scale-110
                                    transition-transform duration-300">
                            <svg class="w-8 h-8 lg:w-10 lg:h-10 text-[var(--color-duracion-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        
                        {{-- Número --}}
                        <div class="text-5xl lg:text-6xl font-[var(--font-bold)] text-[var(--color-duracion-number)] mb-3">
                            {{ $welcome->meses_maximo ?? '6' }}
                        </div>
                        
                        {{-- Label --}}
                        <p class="text-[length:var(--text-base)] font-[var(--font-semibold)] text-[var(--text-duracion-label)]">
                            Meses máximo
                        </p>
                    </div>

                </div>
            </div>
        </section>

        {{-- Sección ¿Qué te permite esta plataforma? --}}
        <section class="relative bg-[var(--color-plataforma-bg)] overflow-hidden">
            
            {{-- Decoración de fondo sutil --}}
            <div class="absolute inset-0 opacity-30">
                <div class="absolute inset-0 bg-[linear-gradient(rgba(30,64,175,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(30,64,175,0.02)_1px,transparent_1px)] bg-[size:32px_32px]"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 py-20 sm:py-28">
                
                {{-- Encabezado de sección --}}
                <div class="text-center mb-16 animate-fade-in-up">
                    {{-- Badge de sección --}}
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full
                                bg-[var(--color-plataforma-icon-bg)] 
                                text-[var(--color-plataforma-icon-text)] text-[length:var(--text-xs)]
                                font-[var(--font-semibold)] mb-4
                                uppercase tracking-wider">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        Funcionalidades del sistema
                    </div>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-[var(--font-bold)] text-[var(--text-plataforma-title)] mb-4">
                        ¿Qué te permite esta plataforma?
                    </h2>
                    <p class="text-[length:var(--text-lg)] sm:text-[length:var(--text-xl)] text-[var(--text-plataforma-subtitle)] max-w-2xl mx-auto leading-relaxed">
                        Gestiona todo tu proceso de Servicio Social desde un solo lugar
                    </p>
                </div>

                {{-- Grid de funcionalidades (2x2) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 max-w-4xl mx-auto">
                    
                    {{-- Card 1: Registro digital --}}
                    <div class="group p-7 lg:p-8 rounded-[var(--radius-lg)] 
                                bg-[var(--color-plataforma-card-bg)] 
                                border-2 border-[var(--color-plataforma-card-border)]
                                shadow-[var(--shadow-sm)]
                                hover:border-[var(--color-plataforma-icon-text)]
                                hover:shadow-xl hover:shadow-[var(--color-plataforma-card-shadow-hover)]
                                hover:-translate-y-2
                                transition-all duration-300 
                                animate-fade-in-up delay-100">
                        
                        <div class="flex items-start gap-4">
                            {{-- Icono --}}
                            <div class="w-12 h-12 rounded-[var(--radius-md)] 
                                        bg-[var(--color-plataforma-icon-bg)] 
                                        flex items-center justify-center flex-shrink-0 mt-1
                                        group-hover:scale-110
                                        transition-transform duration-300">
                                <svg class="w-6 h-6 text-[var(--color-plataforma-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            
                            {{-- Contenido --}}
                            <div>
                                <h3 class="text-[length:var(--text-lg)] font-[var(--font-bold)] text-[var(--text-plataforma-card-title)] mb-2">
                                    Registro digital
                                </h3>
                                <p class="text-[length:var(--text-base)] text-[var(--text-plataforma-card-body)] leading-relaxed">
                                    Registra tu Servicio Social de forma completamente digital
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Gestión de documentos --}}
                    <div class="group p-7 lg:p-8 rounded-[var(--radius-lg)] 
                                bg-[var(--color-plataforma-card-bg)] 
                                border-2 border-[var(--color-plataforma-card-border)]
                                shadow-[var(--shadow-sm)]
                                hover:border-[var(--color-plataforma-icon-text)]
                                hover:shadow-xl hover:shadow-[var(--color-plataforma-card-shadow-hover)]
                                hover:-translate-y-2
                                transition-all duration-300 
                                animate-fade-in-up delay-200">
                        
                        <div class="flex items-start gap-4">
                            {{-- Icono --}}
                            <div class="w-12 h-12 rounded-[var(--radius-md)] 
                                        bg-[var(--color-plataforma-icon-bg)] 
                                        flex items-center justify-center flex-shrink-0 mt-1
                                        group-hover:scale-110
                                        transition-transform duration-300">
                                <svg class="w-6 h-6 text-[var(--color-plataforma-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            
                            {{-- Contenido --}}
                            <div>
                                <h3 class="text-[length:var(--text-lg)] font-[var(--font-bold)] text-[var(--text-plataforma-card-title)] mb-2">
                                    Gestión de documentos
                                </h3>
                                <p class="text-[length:var(--text-base)] text-[var(--text-plataforma-card-body)] leading-relaxed">
                                    Sube y administra todos tus documentos oficiales
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Control de actividades --}}
                    <div class="group p-7 lg:p-8 rounded-[var(--radius-lg)] 
                                bg-[var(--color-plataforma-card-bg)] 
                                border-2 border-[var(--color-plataforma-card-border)]
                                shadow-[var(--shadow-sm)]
                                hover:border-[var(--color-plataforma-icon-text)]
                                hover:shadow-xl hover:shadow-[var(--color-plataforma-card-shadow-hover)]
                                hover:-translate-y-2
                                transition-all duration-300 
                                animate-fade-in-up delay-300">
                        
                        <div class="flex items-start gap-4">
                            {{-- Icono --}}
                            <div class="w-12 h-12 rounded-[var(--radius-md)] 
                                        bg-[var(--color-plataforma-icon-bg)] 
                                        flex items-center justify-center flex-shrink-0 mt-1
                                        group-hover:scale-110
                                        transition-transform duration-300">
                                <svg class="w-6 h-6 text-[var(--color-plataforma-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            
                            {{-- Contenido --}}
                            <div>
                                <h3 class="text-[length:var(--text-lg)] font-[var(--font-bold)] text-[var(--text-plataforma-card-title)] mb-2">
                                    Control de actividades
                                </h3>
                                <p class="text-[length:var(--text-base)] text-[var(--text-plataforma-card-body)] leading-relaxed">
                                    Lleva el control detallado de tus actividades
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Card 4: Seguimiento en tiempo real --}}
                    <div class="group p-7 lg:p-8 rounded-[var(--radius-lg)] 
                                bg-[var(--color-plataforma-card-bg)] 
                                border-2 border-[var(--color-plataforma-card-border)]
                                shadow-[var(--shadow-sm)]
                                hover:border-[var(--color-plataforma-icon-text)]
                                hover:shadow-xl hover:shadow-[var(--color-plataforma-card-shadow-hover)]
                                hover:-translate-y-2
                                transition-all duration-300 
                                animate-fade-in-up delay-400">
                        
                        <div class="flex items-start gap-4">
                            {{-- Icono --}}
                            <div class="w-12 h-12 rounded-[var(--radius-md)] 
                                        bg-[var(--color-plataforma-icon-bg)] 
                                        flex items-center justify-center flex-shrink-0 mt-1
                                        group-hover:scale-110
                                        transition-transform duration-300">
                                <svg class="w-6 h-6 text-[var(--color-plataforma-icon-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            
                            {{-- Contenido --}}
                            <div>
                                <h3 class="text-[length:var(--text-lg)] font-[var(--font-bold)] text-[var(--text-plataforma-card-title)] mb-2">
                                    Seguimiento en tiempo real
                                </h3>
                                <p class="text-[length:var(--text-base)] text-[var(--text-plataforma-card-body)] leading-relaxed">
                                    Consulta el estado y liberación de tu proceso en todo momento
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- CTA adicional opcional --}}
                <div class="mt-12 text-center animate-fade-in-up delay-500">
                    <div class="inline-flex items-center gap-2 text-[var(--text-plataforma-subtitle)] text-[length:var(--text-sm)]">
                        <svg class="w-5 h-5 text-[var(--color-plataforma-icon-text)]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-[var(--font-medium)]">Todo tu proceso centralizado en una sola plataforma segura</span>
                    </div>
                </div>

            </div>
        </section>

        {{-- Sección CTA (Call to Action) --}}
        @guest
        <section class="relative bg-[var(--color-cta-bg)] border-y border-[var(--color-cta-border)] overflow-hidden">
            
            {{-- Decoración de fondo con patrón sutil --}}
            <div class="absolute inset-0 opacity-30">
                <div class="absolute inset-0 bg-[linear-gradient(rgba(30,64,175,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(30,64,175,0.03)_1px,transparent_1px)] bg-[size:40px_40px]"></div>
                
                {{-- Círculos decorativos sutiles --}}
                <div class="absolute top-[20%] left-[10%] w-[300px] h-[300px] bg-[var(--color-cta-btn-primary)] rounded-full blur-[100px] opacity-10"></div>
                <div class="absolute bottom-[20%] right-[10%] w-[250px] h-[250px] bg-[var(--color-cta-btn-primary)] rounded-full blur-[100px] opacity-10"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 py-20 sm:py-28">
                <div class="max-w-3xl mx-auto">
                    
                    {{-- Card CTA principal --}}
                    <div class="p-10 sm:p-12 lg:p-14 rounded-[var(--radius-xl)] 
                                bg-[var(--color-cta-card-bg)]
                                border-2 border-[var(--color-cta-card-border)]
                                shadow-2xl shadow-[var(--color-cta-card-shadow)]
                                text-center
                                animate-fade-in-up">
                        
                        {{-- Icono decorativo superior --}}
                        <div class="w-16 h-16 rounded-[var(--radius-lg)] 
                                    bg-[var(--color-cta-btn-primary)]/10
                                    flex items-center justify-center mx-auto mb-6
                                    animate-pulse-slow">
                            <svg class="w-8 h-8 text-[var(--color-cta-btn-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>

                        {{-- Título --}}
                        <h2 class="text-3xl sm:text-4xl font-[var(--font-bold)] text-[var(--text-cta-title)] mb-4">
                            ¿Listo para comenzar?
                        </h2>
                        
                        {{-- Descripción --}}
                        <p class="text-[length:var(--text-lg)] sm:text-[length:var(--text-xl)] text-[var(--text-cta-body)] mb-10 max-w-xl mx-auto leading-relaxed">
                            Inicia tu proceso de Servicio Social de manera digital
                        </p>

                        {{-- Línea divisoria decorativa --}}
                        <div class="h-px bg-gradient-to-r from-transparent via-[var(--color-cta-card-border)] to-transparent mb-10"></div>

                        {{-- Botones de acción --}}
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            {{-- Botón primario: Registrarse --}}
                            <a href="{{ route('register') }}"
                            class="group inline-flex items-center justify-center gap-2
                                    px-8 py-4 rounded-[var(--radius-md)]
                                    bg-[var(--color-cta-btn-primary)]
                                    text-[var(--text-cta-btn-primary)] font-[var(--font-semibold)]
                                    text-[length:var(--text-base)]
                                    shadow-lg shadow-[var(--color-cta-btn-primary-shadow)]
                                    hover:bg-[var(--color-cta-btn-primary-hover)]
                                    hover:shadow-xl
                                    hover:-translate-y-0.5
                                    transition-all duration-300">
                                <span>Registrarse ahora</span>
                            </a>

                            {{-- Botón secundario: Iniciar sesión --}}
                            <a href="{{ route('login') }}"
                            class="group inline-flex items-center justify-center gap-2
                                    px-8 py-4 rounded-[var(--radius-md)]
                                    bg-[var(--color-cta-btn-secondary)]
                                    border-2 border-[var(--color-cta-btn-secondary-border)]
                                    text-[var(--text-cta-btn-secondary)] font-[var(--font-semibold)]
                                    text-[length:var(--text-base)]
                                    shadow-md
                                    hover:-translate-y-0.5
                                    transition-all duration-300">
                                <span>Iniciar sesión</span>
                            </a>
                        </div>

                        {{-- Nota adicional --}}
                        <div class="mt-8 pt-6 border-t border-[var(--color-cta-card-border)]">
                            <div class="flex items-center justify-center gap-2 text-[var(--text-cta-body)] text-[length:var(--text-sm)]">
                                <svg class="w-4 h-4 text-[var(--color-cta-btn-primary)]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-[var(--font-medium)]">Proceso 100% seguro y respaldado institucionalmente</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </section>
        @endguest

    </main>

    {{-- Footer Institucional --}}
    <footer class="relative bg-[var(--color-footer-bg)] border-t border-[var(--color-footer-border)] overflow-hidden">
        
        {{-- Decoración de fondo sutil --}}
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-4">
            
            {{-- Contenido principal del footer --}}
            <div class="py-12 sm:py-16">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                    
                    {{-- Columna 1: Información institucional --}}
                    <div class="lg:col-span-2">
                        {{-- Logo o nombre --}}
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-[var(--radius-md)] bg-white flex items-center justify-center overflow-hidden shadow-sm">
                                @if($welcome && $welcome->logo)
                                    <img src="{{ asset('storage/' . $welcome->logo) }}" 
                                        alt="{{ $welcome->abreviatura ?? 'Logo' }}" 
                                        class="w-full h-full object-contain">
                                @else
                                    {{-- Si no hay logo, mostrar la abreviatura --}}
                                    <span class="text-gray-800 font-bold">{{ $welcome->abreviatura ?? 'ITSCO' }}</span>
                                @endif
                            </div>
                            <h3 class="text-[length:var(--text-lg)] font-[var(--font-bold)] text-[var(--text-footer-primary)]">
                                {{ $welcome->abreviatura ?? 'ITSCO' }}
                            </h3>
                        </div>
                        <p class="text-[length:var(--text-sm)] text-[var(--text-footer-secondary)] leading-relaxed mb-4 max-w-md">
                                {{ $welcome->nombre_institucion ?? 'Instituto Tecnológico Superior de Cosamaloapan' }}
                        </p>
                        <p class="text-[length:var(--text-xs)] text-[var(--text-footer-muted)] leading-relaxed max-w-md">
                            Plataforma oficial para la gestión del Servicio Social
                        </p>
                    </div>

                    {{-- Columna 2: Enlaces rápidos --}}
                    <div>
                        <h4 class="text-[length:var(--text-sm)] font-[var(--font-semibold)] text-[var(--text-footer-primary)] uppercase tracking-wider mb-4">
                            Enlaces
                        </h4>
                        <ul class="space-y-3">
                            <li>
                                <a href="#informacion" class="text-[length:var(--text-sm)] text-[var(--color-footer-link)] hover:text-[var(--color-footer-link-hover)] transition-colors duration-200">
                                    ¿Qué es?
                                </a>
                            </li>
                            <li>
                                <a href="#informacion" class="text-[length:var(--text-sm)] text-[var(--color-footer-link)] hover:text-[var(--color-footer-link-hover)] transition-colors duration-200">
                                    Requisitos
                                </a>
                            </li>
                            <li>
                                <a href="#informacion" class="text-[length:var(--text-sm)] text-[var(--color-footer-link)] hover:text-[var(--color-footer-link-hover)] transition-colors duration-200">
                                    Duración
                                </a>
                            </li>
                            @guest
                            <li>
                                <a href="{{ route('login') }}" class="text-[length:var(--text-sm)] text-[var(--color-footer-accent)] hover:text-[var(--color-footer-accent-hover)] font-[var(--font-medium)] transition-colors duration-200">
                                    Iniciar sesión
                                </a>
                            </li>
                            @endguest
                        </ul>
                    </div>

                    {{-- Columna 3: Contacto --}}
                    <div>
                        <h4 class="text-[length:var(--text-sm)] font-[var(--font-semibold)] text-[var(--text-footer-primary)] uppercase tracking-wider mb-4">
                            Contacto
                        </h4>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-[var(--color-footer-accent)] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:{{ $welcome->email_contacto ?? 'serviciosocial@itsco.edu.mx' }}" 
                                class="text-[length:var(--text-sm)] text-[var(--color-footer-link)] hover:text-[var(--color-footer-link-hover)] transition-colors duration-200">
                                    {{ $welcome->email_contacto ?? 'serviciosocial@itsco.edu.mx' }}
                                </a>

                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-[var(--color-footer-accent)] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-[length:var(--text-sm)] text-[var(--text-footer-secondary)]">
                                    {{ $welcome->telefono_contacto ?? ' (288) 000 0000' }}
                                </span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-[var(--color-footer-accent)] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-[length:var(--text-sm)] text-[var(--text-footer-secondary)] leading-relaxed">
                                    {{ $welcome->direccion_contacto ?? 'Cosamaloapan, Veracruz' }}
                                </span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            {{-- Línea divisoria --}}
            <div class="h-px bg-[var(--color-footer-divider)]"></div>

            {{-- Barra inferior --}}
            <div class="py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                {{-- Copyright --}}
                <p class="text-[length:var(--text-xs)] text-[var(--text-footer-muted)] text-center sm:text-left">
                    © {{ date('Y') }} Instituto Tecnológico Superior de Cosamaloapan.
                </p>

                {{-- Links legales opcionales --}}
                <div class="flex items-center gap-4">
                    <a href="#" class="text-[length:var(--text-xs)] text-[var(--text-footer-muted)] hover:text-[var(--text-footer-secondary)] transition-colors duration-200">
                        Privacidad
                    </a>
                    <span class="text-[var(--text-footer-muted)]">•</span>
                    <a href="#" class="text-[length:var(--text-xs)] text-[var(--text-footer-muted)] hover:text-[var(--text-footer-secondary)] transition-colors duration-200">
                        Términos
                    </a>
                </div>
            </div>

        </div>
    </footer>

</div>
</body>
</html>