{{-- livewire/dashboard/period/partials/documents-grid.blade.php --}}

<style>
    .doc-item:hover {
        border-color: var(--period-detail-brand-primary) !important;
    }
</style>

<div class="px-4 sm:px-6 py-5"
    style="background-color: var(--period-detail-expanded-bg);">

    <h4 class="text-xs font-semibold uppercase tracking-wider flex items-center gap-2 mb-3"
        style="color: var(--period-detail-text-secondary);">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            style="color: var(--period-detail-brand-primary);">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Documentos del estudiante
    </h4>

    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-1.5">
        @forelse($this->getStudentDocuments($student->id) as $doc)
            @php
                $hasFile  = false;
                $fileName = '';

                if ($doc->file->upload_mode === 'admin_only') {
                    if ($doc->file->is_individual) {
                        $individualUpload = \App\Models\FileStudentUpload::where('file_id', $doc->file->id)
                            ->where('student_id', $student->id)
                            ->first();
                        if ($individualUpload) {
                            $hasFile  = true;
                            $fileName = Str::before(pathinfo($individualUpload->name_file, PATHINFO_FILENAME), '_');
                        }
                    }
                } else {
                    if ($doc->student_file_path) {
                        $hasFile  = true;
                        $fileName = Str::before(pathinfo($doc->student_file_name, PATHINFO_FILENAME), '_');
                    }
                }
            @endphp

            <div class="doc-item flex items-center gap-3 rounded-lg px-3 py-2.5 cursor-pointer transition-colors"
                wire:click="quickReviewDocument({{ $doc->id }})"
                style="background-color: var(--period-detail-bg); border: 1px solid var(--period-detail-border);">

                {{-- Ícono de estado --}}
                @if($doc->file->upload_mode === 'admin_only')
                    @if($doc->file->is_individual)
                        @php $iu = \App\Models\FileStudentUpload::where('file_id', $doc->file->id)->where('student_id', $student->id)->first(); @endphp
                        @if($iu)
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-md flex-shrink-0"
                                style="background-color: rgba(16, 185, 129, 0.15);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--period-detail-status-approved-icon-color);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                        @else
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-md flex-shrink-0"
                                style="background-color: var(--period-detail-card-bg); border: 1px solid var(--period-detail-border); opacity: 0.5;">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--period-detail-text-secondary);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                </svg>
                            </span>
                        @endif
                    @else
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md flex-shrink-0"
                            style="background-color: rgba(16, 185, 129, 0.15);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="color: var(--period-detail-status-approved-icon-color);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                    @endif
                @else
                    @if($doc->student_file_path)
                        @if($doc->status === 'revisado')
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-md flex-shrink-0"
                                style="background-color: rgba(16, 185, 129, 0.15);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--period-detail-status-approved-icon-color);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                        @elseif($doc->status === 'rechazado')
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-md flex-shrink-0"
                                style="background-color: rgba(239, 68, 68, 0.15);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--period-detail-status-rejected-text);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </span>
                        @else
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-md flex-shrink-0"
                                style="background-color: rgba(232, 210, 50, 0.15);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--period-detail-status-pending);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                        @endif
                    @else
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md flex-shrink-0"
                            style="background-color: var(--period-detail-card-bg); border: 1px solid var(--period-detail-border); opacity: 0.4;">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="color: var(--period-detail-text-secondary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </span>
                    @endif
                @endif

                {{-- Nombre del documento --}}
                <span class="flex-1 text-xs font-medium truncate"
                    style="color: var(--period-detail-text-primary);">
                    {{ $doc->file->name ?? $doc->name }}
                </span>
            </div>
        @empty
            <div class="col-span-3 text-center py-8" style="color: var(--period-detail-text-secondary);">
                <svg class="w-10 h-10 mx-auto mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm">No hay documentos asignados</p>
            </div>
        @endforelse
    </div>
</div>