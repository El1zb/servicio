<?php

namespace App\Livewire\Students\Documents\Concerns;

use App\Models\Document;

trait ManagesModals
{
    // ─── Preview ────────────────────────────────────────────────────────────────

    public ?string $previewPath = null;
    public ?string $previewName = null;

    public function previewFile(string $path, string $name): void
    {
        $this->previewPath = $path;
        $this->previewName = $name;
    }

    public function closePreview(): void
    {
        $this->previewPath = null;
        $this->previewName = null;
    }

    // ─── Comments ───────────────────────────────────────────────────────────────

    public bool   $isCommentsModalOpen = false;
    public        $selectedDocument    = null;

    public function openComments(int $documentId): void
    {
        $this->selectedDocument    = Document::findOrFail($documentId);
        $this->isCommentsModalOpen = true;
    }

    public function closeComments(): void
    {
        $this->isCommentsModalOpen = false;
        $this->selectedDocument    = null;
    }
}