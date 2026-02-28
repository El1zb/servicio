{{-- =================== MODAL RECHAZO ESTUDIANTE =================== --}}
@if($showRejectModal && $selectedStudent)
    <flux:modal
        wire:model="showRejectModal"
        :dismissible="false"
        class="w-[95vw] sm:w-[85vw] md:w-[600px] lg:w-[650px] max-w-[95vw]">

        <div class="flex flex-col max-h-[85vh]">

            {{-- ── Header ── --}}
            <div class="flex items-center justify-between px-4 sm:px-6 py-4 sm:py-5 flex-shrink-0"
                style="border-bottom: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="min-w-0">
                        <h3 class="text-sm font-semibold leading-tight"
                            style="color: var(--period-detail-text-primary);">
                            Rechazar Estudiante
                        </h3>
                        <p class="text-xs mt-0.5 truncate" style="color: var(--period-detail-text-secondary);">
                            {{ $selectedStudent->name }}
                            {{ $selectedStudent->last_name_paterno }}
                            {{ $selectedStudent->last_name_materno }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Body ── --}}
            <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-4 sm:py-5"
                style="background-color: var(--period-detail-card-bg);">
                <div class="space-y-4">

                    {{-- Aviso --}}
                    <div class="p-3 sm:p-4 rounded-lg sm:rounded-xl border"
                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border);">
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" style="color: var(--period-detail-status-rejected-text);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-semibold mb-0.5"
                                    style="color: var(--period-detail-status-rejected-text);">Atención</p>
                                <p class="text-xs leading-relaxed" style="color: var(--period-detail-text-secondary);">
                                    Esta acción rechazará el registro del estudiante. Por favor, proporciona un motivo detallado.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Motivo --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--period-detail-text-primary);">
                            Motivo del rechazo
                            <span style="color: var(--period-detail-status-rejected-text);">*</span>
                        </label>
                        <textarea
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg border text-sm resize-none transition-all focus:ring-2 focus:ring-opacity-50"
                            style="background-color: var(--period-detail-bg);
                                   border-color: var(--period-detail-border);
                                   color: var(--period-detail-text-primary);"
                            rows="5"
                            placeholder="Describe el motivo del rechazo de manera clara y profesional..."
                            wire:model="rejectionReason">
                        </textarea>
                        @error('rejectionReason')
                            <p class="mt-2 text-xs sm:text-sm flex items-center gap-2" style="color: #ee6e6c;">
                                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1.5 text-xs" style="color: var(--period-detail-text-secondary);">
                            Este motivo será visible para el estudiante.
                        </p>
                    </div>

                </div>
            </div>

            {{-- ── Footer ── --}}
            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
                style="border-top: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

                <button wire:click="$set('showRejectModal', false)"
                    class="w-full sm:w-auto px-4 py-2 rounded-lg text-sm transition-all duration-200 hover:-translate-y-0.5 text-center"
                    style="color: var(--period-detail-text-secondary); background-color: transparent;"
                    onmouseover="this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'"
                    onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent'">
                    Cancelar
                </button>

                <button wire:click="confirmReject"
                    class="w-full sm:w-auto px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2"
                    style="background-color: var(--period-detail-btn-reject-bg); color: var(--period-detail-btn-reject-text);"
                    onmouseover="this.style.opacity='0.85'"
                    onmouseout="this.style.opacity='1'">
                    Confirmar
                </button>

            </div>

        </div>
    </flux:modal>
@endif