{{-- Quick Stats Bar --}}
<div class="w-full mb-6">
    <div class="grid gap-4 [grid-template-columns:repeat(auto-fit,minmax(220px,1fr))]">

        {{-- Total Estudiantes --}}
        <div class="h-full rounded-xl p-5 shadow-lg" style="background-color: var(--color-card-bg);">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-sm mb-1" style="color: var(--color-secondary);">Total Estudiantes</p>
                    <p class="text-3xl font-bold" style="color: var(--color-primary-2);">
                        {{ $period->students->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                     style="background-color: var(--color-icon-bg);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color: var(--color-secondary);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Aprobados --}}
        <div class="h-full rounded-xl p-5 shadow-lg" style="background-color: var(--color-card-bg);">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-sm mb-1" style="color: var(--color-secondary);">Aprobados</p>
                    <p class="text-3xl font-bold" style="color: var(--color-primary-2);">
                        {{ $period->students->where('status','aprobado')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                     style="background-color: rgba(16, 185, 129, 0.2);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color: rgb(52, 211, 153);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pendientes --}}
        <div class="h-full rounded-xl p-5 shadow-lg" style="background-color: var(--color-card-bg);">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-sm mb-1" style="color: var(--color-secondary);">Pendientes</p>
                    <p class="text-3xl font-bold" style="color: var(--color-primary-2);">
                        {{ $period->students->where('status','pendiente')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                    style="background-color: rgba(232, 210, 50, 0.2);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="color: rgb(250, 204, 21);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Rechazados --}}
        <div class="h-full rounded-xl p-5 shadow-lg" style="background-color: var(--color-card-bg);">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-sm mb-1" style="color: var(--color-secondary);">Rechazados</p>
                    <p class="text-3xl font-bold" style="color: var(--color-primary-2);">
                        {{ $period->students->where('status','rechazado')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                     style="background-color: rgba(239, 68, 68, 0.2);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color: rgb(248, 113, 113);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Documentos Base --}}
        <div class="h-full rounded-xl p-5 shadow-lg" style="background-color: var(--color-card-bg);">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-sm mb-1" style="color: var(--color-secondary);">Documentos Base</p>
                    <p class="text-3xl font-bold" style="color: var(--color-primary-2);">
                        {{ $period->files->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                     style="background-color: rgba(59, 130, 246, 0.2);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color: rgb(96, 165, 250);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>
</div>