{{-- =================== MODAL REVISIÓN RÁPIDA =================== --}}
<flux:modal
    wire:model="showQuickReviewModal"
    :dismissible="false"
    class="w-[95vw] sm:w-[90vw] md:w-[85vw] lg:w-[860px] xl:w-[960px] max-w-[95vw]">

    @if($quickReviewDoc)
    <div class="flex flex-col" style="height: 80vh; max-height: 88vh;">

        {{-- ── Header ── --}}
        <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 flex-shrink-0"
            style="border-bottom: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">

            <div class="flex items-center gap-3 min-w-0 flex-1">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                    style="background-color: var(--period-detail-bg);">
                    <svg class="w-4 h-4" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-sm font-semibold leading-tight truncate"
                        style="color: var(--period-detail-text-primary);">
                        {{ $quickReviewDoc->file->name ?? $quickReviewDoc->name }}
                    </h3>
                    <p class="text-xs mt-0.5 truncate" style="color: var(--period-detail-text-secondary);">
                        {{ $quickReviewDoc->student->name }}
                        {{ $quickReviewDoc->student->last_name_paterno }}
                        {{ $quickReviewDoc->student->last_name_materno }}
                        · {{ $quickReviewDoc->student->control_number }}
                    </p>
                </div>
            </div>

            {{-- Badge de estado --}}
            <div class="flex-shrink-0 ml-3">
                @if($quickReviewDoc->file->upload_mode === 'admin_only')
                    @if($quickReviewDoc->file->is_individual)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border"
                            style="{{ $currentIndividualUpload
                                ? 'background-color: var(--period-detail-status-approved-bg); color: var(--period-detail-status-approved-icon-color); border-color: rgba(16,185,129,0.3);'
                                : 'background-color: var(--period-detail-bg); color: var(--period-detail-text-secondary); border-color: var(--period-detail-border);' }}">
                            {{ $currentIndividualUpload ? 'Archivo Subido' : 'Sin Subir' }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border"
                            style="background-color: var(--period-detail-status-approved-bg); color: var(--period-detail-status-approved-icon-color); border-color: rgba(16,185,129,0.3);">
                            Documento Base
                        </span>
                    @endif
                @else
                    @if($quickReviewDoc->student_file_path)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border"
                            style="
                                @if($quickReviewDoc->status === 'revisado')
                                    background-color: var(--period-detail-status-approved-bg); color: var(--period-detail-status-approved-icon-color); border-color: rgba(16,185,129,0.3);
                                @elseif($quickReviewDoc->status === 'rechazado')
                                    background-color: rgba(239,68,68,0.15); color: var(--period-detail-status-rejected-text); border-color: rgba(239,68,68,0.3);
                                @else
                                    background-color: rgba(232,210,50,0.15); color: var(--period-detail-status-pending); border-color: rgba(232,210,50,0.3);
                                @endif
                            ">
                            @if($quickReviewDoc->status === 'revisado') Aprobado
                            @elseif($quickReviewDoc->status === 'rechazado') Rechazado
                            @else En revisión
                            @endif
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold border"
                            style="background-color: var(--period-detail-bg); color: var(--period-detail-text-secondary); border-color: var(--period-detail-border);">
                            Pendiente
                        </span>
                    @endif
                @endif
            </div>
        </div>

        {{-- ── Layout 2 columnas ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] xl:grid-cols-[1fr_360px] gap-0 flex-1 overflow-hidden">

            {{-- ── Columna izquierda: Preview ── --}}
            <div class="flex flex-col min-h-0 overflow-hidden"
                style="border-right: 1px solid var(--period-detail-border);">

                <div class="flex-1 flex flex-col min-h-0 overflow-hidden"
                    style="background-color: var(--period-detail-bg);">

                    @if($quickReviewDoc->file->upload_mode === 'admin_only' && !$quickReviewDoc->file->is_individual)

                        @if($adminExamplePdf)
                            <div class="flex-1 min-h-0">
                                <iframe src="{{ $adminExamplePdf['url'] }}" class="w-full h-full" style="background-color: var(--period-detail-bg);"></iframe>
                            </div>
                        @endif

                        @if($adminBaseWord)
                            <div class="flex items-center justify-between gap-4 px-4 py-3 flex-shrink-0"
                                style="border-top: 1px solid var(--period-detail-border); background-color: var(--period-detail-card-bg);">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                        style="background-color: var(--period-detail-btn-word-bg);">
                                        <i class="fas fa-file-word text-xs" style="color: var(--period-detail-btn-word-text);"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold truncate" style="color: var(--period-detail-text-primary);">Documento base (Word)</p>
                                        <p class="text-xs truncate" style="color: var(--period-detail-text-secondary);">{{ $adminBaseWord['name'] }}</p>
                                    </div>
                                </div>
                                <a href="{{ $adminBaseWord['url'] }}" download
                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 hover:-translate-y-0.5 flex-shrink-0"
                                    style="background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%); color: var(--period-detail-text-icon);">
                                    Descargar
                                </a>
                            </div>
                        @endif

                        @if(!$adminExamplePdf && !$adminBaseWord)
                            <div class="flex flex-col items-center justify-center flex-1 text-center px-4 py-8">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                                    style="background-color: var(--period-detail-card-bg);">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="color: var(--period-detail-text-secondary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium" style="color: var(--period-detail-text-secondary);">No hay documentos base disponibles</p>
                            </div>
                        @endif

                    @else

                        @if($quickReviewPreviewUrl)
                            @if(pathinfo($quickReviewPreviewUrl, PATHINFO_EXTENSION) === 'pdf')
                                <iframe src="{{ $quickReviewPreviewUrl }}" class="w-full h-full" style="background-color: var(--period-detail-bg);"></iframe>
                            @else
                                <div class="flex flex-col items-center justify-center flex-1 text-center px-6 py-8">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                                        style="background-color: var(--period-detail-card-bg);">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: var(--period-detail-text-secondary);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium mb-1" style="color: var(--period-detail-text-primary);">Vista previa no disponible</p>
                                    <p class="text-xs mb-3" style="color: var(--period-detail-text-secondary);">Este formato no puede visualizarse en el navegador</p>
                                    <a href="{{ $quickReviewPreviewUrl }}" download
                                        class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg"
                                        style="background: linear-gradient(135deg, var(--period-detail-brand-primary) 0%, var(--period-detail-brand-secondary) 100%); color: var(--period-detail-text-icon);">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Descargar para ver
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="flex flex-col items-center justify-center flex-1 text-center px-6 py-8">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                                    style="background-color: var(--period-detail-card-bg);">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="color: var(--period-detail-text-secondary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium" style="color: var(--period-detail-text-secondary);">No se ha cargado ningún documento</p>
                            </div>
                        @endif

                    @endif
                </div>
            </div>

            {{-- ── Columna derecha: Acciones ── --}}
            <div class="flex flex-col overflow-y-auto"
                style="background-color: var(--period-detail-card-bg);">

                <div class="flex flex-col gap-3 p-3 sm:p-4 flex-1">

                    {{-- Subir archivo individual --}}
                    @if($quickReviewDoc->file->upload_mode === 'admin_only' && $quickReviewDoc->file->is_individual)
                        <div class="p-3 rounded-lg border"
                            style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border);">

                            <p class="text-xs font-semibold mb-2 flex items-center gap-1.5"
                                style="color: var(--period-detail-text-primary);">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Subir Documento
                            </p>

                            @if($currentIndividualUpload)
                                <div class="mb-2 p-2 rounded-lg border flex items-center justify-between gap-2"
                                    style="background-color: var(--period-detail-card-bg); border-color: var(--period-detail-border);">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium truncate" style="color: var(--period-detail-text-primary);">{{ $currentIndividualUpload->name_file }}</p>
                                        <p class="text-xs" style="color: var(--period-detail-text-secondary);">{{ $currentIndividualUpload->created_at->diffForHumans() }}</p>
                                    </div>
                                    <button wire:click="deleteIndividualFile" wire:confirm="¿Eliminar este archivo?"
                                        class="w-6 h-6 flex items-center justify-center rounded-lg transition-all duration-200 flex-shrink-0"
                                        style="background-color: var(--period-detail-btn-delete-bg); color: var(--period-detail-btn-delete-text);"
                                        onmouseover="this.style.backgroundColor='var(--period-detail-btn-delete-hover)'"
                                        onmouseout="this.style.backgroundColor='var(--period-detail-btn-delete-bg)'">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            <input type="file" wire:model="individualUploadFile" accept=".pdf,.doc,.docx"
                                class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:cursor-pointer cursor-pointer"
                                style="color: var(--period-detail-text-secondary);">

                            @error('individualUploadFile')
                                <p class="mt-1.5 text-xs flex items-center gap-1.5" style="color: #ee6e6c;">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror

                            @if($individualUploadFile)
                                <button wire:click="uploadIndividualFile" wire:loading.attr="disabled"
                                    class="w-full mt-2 px-4 py-2 text-xs font-semibold rounded-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-50 flex items-center justify-center gap-2"
                                    style="background-color: var(--period-detail-btn-approve-bg); color: var(--period-detail-btn-approve-text);">
                                    <span wire:loading.remove wire:target="uploadIndividualFile">Subir Archivo</span>
                                    <span wire:loading wire:target="uploadIndividualFile">Subiendo...</span>
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- Observaciones --}}
                    <div class="p-3 rounded-lg border flex flex-col"
                        style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border);">

                        <p class="text-xs font-semibold mb-1 flex items-center gap-1.5"
                            style="color: var(--period-detail-text-primary);">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Observaciones
                        </p>
                        <p class="text-xs mb-2" style="color: var(--period-detail-text-secondary);">Visible para el estudiante.</p>

                        <textarea wire:model.defer="quickReviewComments"
                            placeholder="Escribe aquí..."
                            rows="4"
                            class="w-full px-3 py-2 rounded-lg border text-xs resize-none transition-all focus:outline-none focus:ring-1"
                            style="background-color: var(--period-detail-card-bg);
                                   border-color: var(--period-detail-border);
                                   color: var(--period-detail-text-primary);
                                   --tw-ring-color: var(--period-detail-accent);">
                        </textarea>

                        @error('quickReviewComments')
                            <p class="mt-1.5 text-xs flex items-center gap-1.5" style="color: #ee6e6c;">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="#ee6e6c">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.59c.75 1.335-.213 2.971-1.742 2.971H3.48c-1.529 0-2.492-1.636-1.742-2.971L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        <button wire:click="saveComments"
                            class="mt-2 px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md self-end"
                            style="color: var(--period-detail-text-secondary); background-color: transparent;"
                                onmouseover="if(!this.disabled) { this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'; }"
                                onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent';">
                            Guardar
                        </button>
                    </div>

                    {{-- Fecha límite personalizada --}}
                    @if($quickReviewDoc->file->upload_mode !== 'admin_only')
                        @php
                            $periodStart = optional($quickReviewDoc->file->period)->start_date;
                            $periodEnd   = optional($quickReviewDoc->file->period)->end_date;
                        @endphp
                        <div class="p-3 rounded-lg border"
                            style="background-color: var(--period-detail-bg); border-color: var(--period-detail-border);">

                            <p class="text-xs font-semibold mb-2 flex items-center gap-1.5"
                                style="color: var(--period-detail-text-primary);">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" style="color: var(--period-detail-accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Fecha Límite Personalizada
                            </p>

                            <input
                                type="date"
                                wire:model.defer="editingDates.{{ $quickReviewDoc->id }}"
                                min="{{ $periodStart ? \Carbon\Carbon::parse($periodStart)->format('Y-m-d') : '' }}"
                                max="{{ $periodEnd ? \Carbon\Carbon::parse($periodEnd)->format('Y-m-d') : '' }}"
                                class="w-full px-3 py-2 rounded-lg border text-xs transition-all focus:outline-none focus:ring-1 mb-2"
                                style="background-color: var(--period-detail-card-bg);
                                       border-color: var(--period-detail-border);
                                       color: var(--period-detail-text-primary);
                                       --tw-ring-color: var(--period-detail-accent);">

                            <button wire:click="updateDocumentDate({{ $quickReviewDoc->id }})"
                                class="mt-2 px-4 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md self-end"
                                style="color: var(--period-detail-text-secondary); background-color: transparent;"
                                onmouseover="if(!this.disabled) { this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'; }"
                                onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent';">
                                Actualizar
                            </button>
                        </div>
                    @endif

                    {{-- Botones aprobar / rechazar --}}
                    @if($quickReviewDoc->file->upload_mode !== 'admin_only' && $quickReviewDoc->student_file_path)
                        <div class="grid grid-cols-2 gap-2 flex-shrink-0">
                            <button wire:click="quickApproveDocument"
                                class="px-3 py-2 text-xs font-semibold rounded-lg border border-transparent shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-1.5"
                                style="background-color: var(--period-detail-btn-approve-bg); color: var(--period-detail-btn-approve-text);"
                                onmouseover="this.style.opacity='0.85'"
                                onmouseout="this.style.opacity='1'">
                                Aprobar
                            </button>
                            <button wire:click="quickRejectDocument"
                                class="px-3 py-2 text-xs font-semibold rounded-lg border border-transparent shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-1.5"
                                style="background-color: var(--period-detail-btn-reject-bg); color: var(--period-detail-btn-reject-text);"
                                onmouseover="this.style.opacity='0.85'"
                                onmouseout="this.style.opacity='1'">
                                Rechazar
                            </button>
                        </div>
                    @endif

                </div>

                {{-- ── Footer col derecha: Navegación + Cerrar ── --}}
                <div class="flex-shrink-0 px-3 sm:px-4 py-3"
                    style="border-top: 1px solid var(--period-detail-border);">

                    @if($nextPendingDoc || $previousPendingDoc)
                        <div class="flex gap-2 mb-2">
                            <button wire:click="navigateToPreviousDoc"
                                @disabled(!$previousPendingDoc)
                                class="flex-1 px-3 py-1.5 text-xs rounded-lg transition-all duration-200 hover:-translate-y-0.5 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:translate-y-0 text-center"
                                style="color: var(--period-detail-text-secondary); background-color: transparent;"
                                onmouseover="if(!this.disabled) { this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'; }"
                                onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent';">
                                ← Anterior
                            </button>
                            <button wire:click="navigateToNextDoc"
                                @disabled(!$nextPendingDoc)
                                class="flex-1 px-3 py-1.5 text-xs rounded-lg transition-all duration-200 hover:-translate-y-0.5 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:translate-y-0 text-center"
                                style="color: var(--period-detail-text-secondary); background-color: transparent;"
                                onmouseover="if(!this.disabled) { this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'; }"
                                onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent';">
                                Siguiente →
                            </button>
                        </div>
                    @endif

                    <button wire:click="closeQuickReview"
                        class="w-full px-4 py-2 text-xs font-medium rounded-lg transition-all duration-200 hover:-translate-y-0.5 text-center"
                        style="color: var(--period-detail-text-secondary); background-color: transparent;"
                        onmouseover="this.style.color='var(--period-detail-text-primary)'; this.style.backgroundColor='var(--period-detail-border)'"
                        onmouseout="this.style.color='var(--period-detail-text-secondary)'; this.style.backgroundColor='transparent'">
                        Cerrar
                    </button>

                </div>
            </div>
        </div>

    </div>
    @endif
</flux:modal>