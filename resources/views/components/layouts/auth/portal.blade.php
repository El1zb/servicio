<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased auth-portal" style="background-color: #FFFFFF;"
        x-data="{
            showForm: false,
            init() {
                {{-- window persiste entre transiciones wire:navigate (misma
                     pestaña/sesión SPA), pero se reinicia en una carga real
                     de página (URL directa, recarga, pestaña nueva) — así el
                     recibimiento solo se salta al navegar dentro del flujo,
                     nunca en una visita nueva. --}}
                this.showForm = window.__authPortalFormOpen === true;
            },
            openForm() {
                this.showForm = true;
                window.__authPortalFormOpen = true;
            },
            closeForm() {
                this.showForm = false;
                window.__authPortalFormOpen = false;
            }
        }">
        <div class="min-h-screen w-full flex flex-col lg:flex-row">

            {{-- Pantalla de bienvenida en móvil: navbar con acceso rápido +
                 foto con el texto anclado abajo, para no tapar la pantalla. --}}
            <div class="lg:hidden fixed inset-0 z-20 flex flex-col" style="background-color: #FFFFFF;" x-show="!showForm" x-cloak>

                {{-- Navbar --}}
                <div class="flex items-center justify-between px-5 py-4 flex-shrink-0">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/itsco-mark.png') }}" alt="ITSCO" class="h-6 w-6 object-contain">
                        <span class="text-sm font-bold tracking-wide" style="color: var(--color-primary-2);">ITSCO</span>
                    </div>

                    <button type="button" @click="openForm()"
                            title="{{ $authCta ?? 'Iniciar sesión' }}"
                            class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                            style="background-color: var(--color-primary-2); color: #fff;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M15 20.6151C14.0907 20.8619 13.0736 21 12 21C8.13401 21 5 19.2091 5 17C5 14.7909 8.13401 13 12 13C15.866 13 19 14.7909 19 17C19 17.3453 18.9234 17.6804 18.7795 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                {{-- Foto --}}
                <div class="flex-1 min-h-0 px-4 pb-4">
                    <div class="relative w-full h-full rounded-[28px] overflow-hidden">
                        <img src="{{ asset('images/auth-campus.jpg') }}" alt="Campus ITSCO" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(0,0,0,0.10) 0%, rgba(0,0,0,0.20) 55%, rgba(0,0,0,0.80) 100%);"></div>

                        <div class="relative z-10 h-full flex flex-col justify-end p-7">
                            <h2 class="text-2xl leading-tight" style="color: #fff; font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-weight: 700;">
                                Portal de Servicio Social
                            </h2>
                            <p class="mt-2 text-sm leading-relaxed" style="color: rgba(255,255,255,0.85);">
                                Consulta tus documentos, entregas y el avance de tu servicio social del ITSCO en un solo lugar.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel izquierdo: foto del campus (escritorio) --}}
            <div class="hidden lg:block lg:w-1/2 p-4">
                <div class="relative w-full h-full rounded-[28px] overflow-hidden">
                    <img src="{{ asset('images/auth-campus.jpg') }}" alt="Campus ITSCO" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(0,0,0,0.20) 0%, rgba(0,0,0,0.25) 45%, rgba(0,0,0,0.88) 100%);"></div>

                    <div class="relative z-10 h-full flex flex-col justify-end p-10">
                        <div class="max-w-md">
                            <h2 class="text-4xl xl:text-5xl leading-tight" style="color: #fff; font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-weight: 700;">
                                Portal de Servicio Social
                            </h2>
                            <p class="mt-3 text-sm leading-relaxed" style="color: rgba(255,255,255,0.85);">
                                Consulta tus documentos, entregas y el avance de tu servicio social del ITSCO en un solo lugar.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel derecho: formulario --}}
            <div class="relative w-full lg:w-1/2 lg:flex items-center justify-center px-6 py-10 sm:px-12 lg:px-20"
                 style="background-color: #FFFFFF;"
                 :class="{ 'hidden': !showForm, 'flex': showForm }">

                {{-- Regresar al recibimiento (solo móvil) --}}
                <button type="button" @click="closeForm()"
                        class="lg:hidden absolute top-5 right-5 w-9 h-9 rounded-full flex items-center justify-center"
                        style="color: var(--color-secondary);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>

                <div class="mx-auto w-full max-w-sm flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>

        </div>
        @fluxScripts
    </body>
</html>
