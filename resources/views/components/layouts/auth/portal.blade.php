<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased auth-portal !bg-white"
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

            {{-- Pantalla de bienvenida en móvil: tarjeta con padding lateral,
                 título arriba-izquierda y bloque descripción+CTA abajo centrado. --}}
            <div class="lg:hidden fixed inset-0 z-20 p-4" x-show="!showForm" x-cloak>
                <div class="relative w-full h-full rounded-[28px] overflow-hidden">
                    <img src="{{ asset('images/auth-campus.jpg') }}" alt="Campus ITSCO" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 auth-portal-overlay-mobile"></div>

                    <div class="relative z-10 h-full flex flex-col justify-between px-7 py-14 md:p-7">
                        <h2 class="font-heading font-bold text-4xl leading-tight text-left text-white">
                            Portal de Servicio Social
                        </h2>

                        <div class="flex flex-col items-center text-center gap-4 mx-auto max-w-xs w-full">
                            <p class="text-sm leading-relaxed text-white/85">
                                Consulta tus documentos, entregas y el avance de tu servicio social del ITSCO en un solo lugar.
                            </p>

                            <button type="button" @click="openForm()"
                               class="w-full px-6 py-3 rounded-full text-sm font-semibold text-center bg-[var(--color-primary-2)] text-white md:w-fit">
                                Comenzar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel izquierdo: foto del campus (escritorio) --}}
            <div class="hidden lg:block lg:w-1/2 p-4">
                <div class="relative w-full h-full rounded-[28px] overflow-hidden">
                    <img src="{{ asset('images/auth-campus.jpg') }}" alt="Campus ITSCO" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 auth-portal-overlay-desktop"></div>

                    <div class="relative z-10 h-full flex flex-col justify-end p-10">
                        <div class="max-w-md">
                            <h2 class="font-heading font-bold text-4xl xl:text-5xl leading-tight text-white">
                                Portal de Servicio Social
                            </h2>
                            <p class="mt-3 text-sm leading-relaxed text-white/85">
                                Consulta tus documentos, entregas y el avance de tu servicio social del ITSCO en un solo lugar.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panel derecho: formulario --}}
            <div class="relative hidden w-full min-h-screen lg:w-1/2 lg:min-h-0 lg:flex items-center justify-center px-6 py-10 sm:px-12 lg:px-20 !bg-white"
                 :class="{ 'hidden': !showForm, 'flex': showForm }">

                {{-- Regresar al recibimiento (solo móvil) --}}
                <button type="button" @click="closeForm()"
                        class="lg:hidden absolute top-5 left-5 w-9 h-9 rounded-full flex items-center justify-center"
                        style="color: var(--color-secondary);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                </button>

                <div class="mx-auto w-full max-w-sm flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>

        </div>
        @fluxScripts
    </body>
</html>
