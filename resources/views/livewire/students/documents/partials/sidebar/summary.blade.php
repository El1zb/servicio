{{-- Resumen de alertas --}}
<div class="bg-[var(--color-card-bg)] rounded-2xl p-4 w-full select-none cursor-default">

    <div class="mb-4">
        <h3 class="font-bold text-[var(--color-primary)] text-base tracking-wide">Resumen</h3>
        <p class="text-xs text-[var(--color-secondary)] mt-0.5">Estado de entregas</p>
    </div>

    <div class="space-y-1">

        {{-- Por vencer --}}
        <div class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-default">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-1.5 rounded-full bg-yellow-400 flex-shrink-0"></div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text)]">Por vencer</p>
                    <p class="text-xs text-[var(--color-secondary)]">Próximos 7 días</p>
                </div>
            </div>
            {{-- ⚠️ NO CAMBIAR --}}
            <span class="text-lg font-bold text-yellow-400 tabular-nums">{{ count($upcomingDocs) }}</span>
        </div>

        {{-- Rechazados --}}
        <div class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-default">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text)]">Rechazados</p>
                    <p class="text-xs text-[var(--color-secondary)]">Requiere corrección</p>
                </div>
            </div>
            {{-- ⚠️ NO CAMBIAR --}}
            <span class="text-lg font-bold text-red-500 tabular-nums">{{ $rejectedCount }}</span>
        </div>

        {{-- En Revisión --}}
        <div class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-default">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 flex-shrink-0"></div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text)]">En Revisión</p>
                    <p class="text-xs text-[var(--color-secondary)]">Pendiente de revisión</p>
                </div>
            </div>
            {{-- ⚠️ NO CAMBIAR --}}
            <span class="text-lg font-bold text-blue-500 tabular-nums">{{ $reviewCount }}</span>
        </div>

        {{-- Aprobados --}}
        <div class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-default">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-1.5 rounded-full bg-green-500 flex-shrink-0"></div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text)]">Aprobados</p>
                    <p class="text-xs text-[var(--color-secondary)]">Finalizados</p>
                </div>
            </div>
            {{-- ⚠️ NO CAMBIAR --}}
            <span class="text-lg font-bold text-green-500 tabular-nums">{{ $approvedCount }}</span>
        </div>

        {{-- Vencidos --}}
        <div class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-default"
             x-data="{ expiredDocs: {{ json_encode($calendarEvents) }} }">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-1.5 rounded-full bg-gray-400 flex-shrink-0"></div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text)]">Vencidos</p>
                    <p class="text-xs text-[var(--color-secondary)]">Requiere acción</p>
                </div>
            </div>
            {{-- ⚠️ NO CAMBIAR: contador Alpine --}}
            <span class="text-lg font-bold text-gray-400 tabular-nums"
                  x-text="expiredDocs.filter(d => d.isExpired && !d.hasFile && d.status !== 'revisado').length">
            </span>
        </div>

    </div>
</div>