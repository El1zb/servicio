{{-- Resumen de alertas --}}
<div class="bg-[var(--index-card-bg)] rounded-xl shadow-lg overflow-hidden border border-[var(--index-border)] p-5">
    <div class="mb-4">
        <h3 class="font-bold text-[var(--index-text-primary)]">Resumen</h3>
        <p class="text-xs text-[var(--index-text-secondary)]">Estado de tus entregas</p>
    </div>

    <div class="space-y-3">

        {{-- Por vencer --}}
        <div class="flex items-center justify-between p-3 rounded-lg bg-[var(--index-content-bg)]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-[var(--index-status-pending)] rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-[var(--index-text-primary)]">Por vencer</p>
                    <p class="text-xs text-[var(--index-text-secondary)]">Próximos 7 días</p>
                </div>
            </div>
            {{-- ⚠️ NO CAMBIAR --}}
            <span class="text-2xl font-bold text-[var(--index-status-pending)]">{{ count($upcomingDocs) }}</span>
        </div>

        {{-- Rechazados --}}
        <div class="flex items-center justify-between p-3 rounded-lg bg-[var(--index-content-bg)]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-[var(--index-status-rejected-icon)] rounded-lg flex items-center justify-center">
                    <i class="fas fa-times text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-[var(--index-text-primary)]">Rechazados</p>
                    <p class="text-xs text-[var(--index-text-secondary)]">Requiere corrección</p>
                </div>
            </div>
            {{-- ⚠️ NO CAMBIAR --}}
            <span class="text-2xl font-bold text-[var(--index-status-rejected-icon)]">{{ $rejectedCount }}</span>
        </div>

        {{-- En Revisión --}}
        <div class="flex items-center justify-between p-3 rounded-lg bg-[var(--index-content-bg)]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-[var(--index-status-normal-icon)] rounded-lg flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-[var(--index-text-primary)]">En Revisión</p>
                    <p class="text-xs text-[var(--index-text-secondary)]">Pendiente de revisión</p>
                </div>
            </div>
            {{-- ⚠️ NO CAMBIAR --}}
            <span class="text-2xl font-bold text-[var(--index-status-normal-icon)]">{{ $reviewCount }}</span>
        </div>

        {{-- Aprobados --}}
        <div class="flex items-center justify-between p-3 rounded-lg bg-[var(--index-content-bg)]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-[var(--index-status-approved-icon)] rounded-lg flex items-center justify-center">
                    <i class="fas fa-check text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-[var(--index-text-primary)]">Aprobados</p>
                    <p class="text-xs text-[var(--index-text-secondary)]">Finalizados</p>
                </div>
            </div>
            {{-- ⚠️ NO CAMBIAR --}}
            <span class="text-2xl font-bold text-[var(--index-status-approved-icon)]">{{ $approvedCount }}</span>
        </div>

        {{-- Vencidos --}}
        <div class="flex items-center justify-between p-3 rounded-lg bg-[var(--index-content-bg)]"
             x-data="{ expiredDocs: {{ json_encode($calendarEvents) }} }">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-[var(--index-text-primary)]">Vencidos</p>
                    <p class="text-xs text-[var(--index-text-secondary)]">Requiere acción</p>
                </div>
            </div>
            {{-- ⚠️ NO CAMBIAR: contador Alpine --}}
            <span class="text-2xl font-bold text-orange-500"
                  x-text="expiredDocs.filter(d => d.isExpired && !d.hasFile && d.status !== 'revisado').length">
            </span>
        </div>

    </div>
</div>