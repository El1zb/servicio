<?php

namespace App\Livewire\Dashboard\Period\Concerns;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use App\Models\File;
use App\Models\Document;

trait ManagesDocuments
{
    // ========================= Propiedades =========================

    public ?int    $documentId           = null;
    public ?string $documentName         = null;
    public ?string $documentDeadline     = null;
    public         $documentFile         = null;
    public         $documentExample      = null;
    public int     $maxSize              = 10240;
    public ?int    $editingDocumentId    = null;
    public ?string $previewPath          = null;
    public ?string $previewName          = null;

    public ?string $documentFirman         = null;
    public ?string $documentObservations   = null;
    public string  $documentUploadMode     = 'bidirectional';
    public bool    $isIndividual           = false;

    public bool    $isUploadModeChangeModalOpen  = false;
    public         $pendingUploadModeChangeFile  = null;

    public bool    $removeDocumentFile  = false;
    public bool    $removeExampleFile   = false;

    public string  $searchDocuments     = '';

    public ?int    $deleteDocumentId              = null;
    public bool    $isDeleteDocumentModalOpen     = false;

    // ========================= Watchers =========================

    public function updatedSearchDocuments(): void
    {
        $this->resetPage('filesPage');
    }

    public function updatedDocumentUploadMode(string $value): void
    {
        if ($this->editingDocumentId) return;

        $this->documentFile    = null;
        $this->documentExample = null;

        if ($value === 'admin_only') {
            $this->documentDeadline = null;
        }

        if ($value !== 'admin_only') {
            $this->isIndividual = false;
        }
    }

    public function updatedIsIndividual(bool $value): void
    {
        if ($this->editingDocumentId) return;

        if (! $value) {
            $this->documentFile    = null;
            $this->documentExample = null;
        }
    }

    // ========================= Query =========================

    public function getFilesPaginated()
    {
        return $this->period->files()
            ->when(
                $this->searchDocuments,
                fn ($q) => $q->where('name', 'like', '%' . $this->searchDocuments . '%')
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'filesPage');
    }

    // ========================= CRUD =========================

    public function createDocument(): void
    {
        [$rules, $messages] = $this->documentValidationRules();
        $this->validate($rules, $messages);

        $isIndividualAdmin = $this->documentUploadMode === 'admin_only' && $this->isIndividual;

        $filePath    = (! $isIndividualAdmin && $this->documentFile)    ? $this->documentFile->store('files', 'public')            : null;
        $examplePath = (! $isIndividualAdmin && $this->documentExample) ? $this->documentExample->store('files/examples', 'public') : null;

        File::create([
            'period_id'          => $this->periodId,
            'name'               => $this->documentName,
            'limit_date'         => $this->documentUploadMode !== 'admin_only' ? $this->documentDeadline : null,
            'file_path'          => $filePath,
            'name_file'          => $this->documentFile?->getClientOriginalName(),
            'example_path'       => $examplePath,
            'example_name_file'  => $this->documentExample?->getClientOriginalName(),
            'max_size'           => $this->maxSize,
            'firman'             => $this->documentFirman,
            'observations'       => $this->documentObservations,
            'upload_mode'        => $this->documentUploadMode ?? 'bidirectional',
            'is_individual'      => $this->isIndividual ?? false,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Documento creado correctamente');
        $this->resetDocumentFields();
        $this->loadPeriod();
    }

    public function editDocument(int $id): void
    {
        $file = File::findOrFail($id);

        $this->documentId           = $file->id;
        $this->documentName         = $file->name;
        $this->documentDeadline     = $file->limit_date;
        $this->maxSize              = $file->max_size;
        $this->documentFirman       = $file->firman;
        $this->documentObservations = $file->observations;
        $this->documentUploadMode   = $file->upload_mode;
        $this->isIndividual         = $file->is_individual;
        $this->documentFile         = null;
        $this->documentExample      = null;
        $this->removeDocumentFile   = false;
        $this->removeExampleFile    = false;
        $this->editingDocumentId    = $id;
    }

    public function saveDocument(): void
    {
        [$rules, $messages] = $this->documentValidationRules(editing: true);
        $this->validate($rules, $messages);

        if (! $this->documentId) {
            $this->createDocument();
            return;
        }

        $file = File::findOrFail($this->documentId);

        // Detectar cambio de upload_mode → pedir confirmación
        if ($file->upload_mode !== $this->documentUploadMode) {
            $this->isUploadModeChangeModalOpen = true;
            $this->pendingUploadModeChangeFile = $file;
            return;
        }

        $this->applyDocumentUpdate($file);
        $this->dispatch('notify', type: 'info', message: 'Documento actualizado correctamente');
        $this->cancelEditDocument();
        $this->loadPeriod();
    }

    public function cancelEditDocument(): void
    {
        $this->reset([
            'documentId', 'documentName', 'documentDeadline',
            'documentFile', 'documentExample', 'maxSize',
            'documentFirman', 'documentObservations',
            'documentUploadMode', 'isIndividual',
            'removeDocumentFile', 'removeExampleFile', 'editingDocumentId',
        ]);
        $this->documentUploadMode = 'bidirectional';
    }

    public function confirmUploadModeChange(): void
    {
        if (! $this->pendingUploadModeChangeFile) {
            $this->cancelEditDocument();
            return;
        }

        $file = $this->pendingUploadModeChangeFile;

        // Actualizar campos
        $file->name          = $this->documentName;
        $file->limit_date    = $this->documentUploadMode !== 'admin_only' ? $this->documentDeadline : null;
        $file->max_size      = $this->maxSize;
        $file->firman        = $this->documentFirman;
        $file->observations  = $this->documentObservations;
        $file->upload_mode   = $this->documentUploadMode;
        $file->is_individual = $this->isIndividual ?? false;

        // Eliminar archivos previos
        $this->deleteFileFromStorage($file->file_path);
        $this->deleteFileFromStorage($file->example_path);
        $file->file_path = $file->name_file = $file->example_path = $file->example_name_file = null;

        // Subir nuevos si corresponde
        if (! ($this->documentUploadMode === 'admin_only' && $this->isIndividual)) {
            if ($this->documentFile) {
                $file->file_path = $this->documentFile->store('files', 'public');
                $file->name_file = $this->documentFile->getClientOriginalName();
            }
            if ($this->documentExample) {
                $file->example_path      = $this->documentExample->store('files/examples', 'public');
                $file->example_name_file = $this->documentExample->getClientOriginalName();
            }
        }

        $file->save();

        // Sincronizar documentos de estudiantes
        Document::where('file_id', $file->id)->get()->each(function ($doc) use ($file) {
            $doc->student_file_path = null;
            $doc->student_file_name = null;
            $doc->status = $file->upload_mode === 'admin_only' ? 'revisado' : 'en_revision';
            $doc->save();
        });

        $this->dispatch('notify', type: 'info', message: 'Modo de carga actualizado correctamente.');
        $this->isUploadModeChangeModalOpen = false;
        $this->pendingUploadModeChangeFile = null;
        $this->cancelEditDocument();
        $this->loadPeriod();
    }

    public function deleteDocument(int $id): void
    {
        $this->deleteDocumentId          = $id;
        $this->isDeleteDocumentModalOpen = true;
    }

    public function confirmDeleteDocument(): void
    {
        if ($this->deleteDocumentId) {
            $file = File::findOrFail($this->deleteDocumentId);
            $this->deleteFileFromStorage($file->file_path);
            $this->deleteFileFromStorage($file->example_path);
            $file->delete();
            $this->loadPeriod();
            $this->dispatch('notify', type: 'error', message: 'Documento eliminado correctamente');
        }

        $this->isDeleteDocumentModalOpen = false;
        $this->deleteDocumentId          = null;
    }

    // ========================= Vista Previa =========================

    public function previewFile(string $path, string $name): void
    {
        $this->previewPath = $path;
        $this->previewName = $name;
    }

    // ========================= Manejo de archivos existentes =========================

    public function removeExistingDocumentFile(): void { $this->removeDocumentFile = true; }
    public function removeExistingExampleFile(): void  { $this->removeExampleFile  = true; }
    public function cancelRemoveDocumentFile(): void   { $this->removeDocumentFile = false; }
    public function cancelRemoveExampleFile(): void    { $this->removeExampleFile  = false; }

    // ========================= Helpers privados =========================

    private function applyDocumentUpdate(File $file): void
    {
        $file->name          = $this->documentName;
        $file->limit_date    = $this->documentUploadMode !== 'admin_only' ? $this->documentDeadline : null;
        $file->max_size      = $this->maxSize;
        $file->firman        = $this->documentFirman;
        $file->observations  = $this->documentObservations;
        $file->upload_mode   = $this->documentUploadMode ?? 'bidirectional';
        $file->is_individual = $this->isIndividual ?? false;

        $isIndividualAdmin = $this->documentUploadMode === 'admin_only' && $this->isIndividual;

        if ($isIndividualAdmin) {
            $this->deleteFileFromStorage($file->file_path);
            $this->deleteFileFromStorage($file->example_path);
            $file->file_path = $file->name_file = $file->example_path = $file->example_name_file = null;
        } else {
            if ($this->removeDocumentFile && $file->file_path) {
                $this->deleteFileFromStorage($file->file_path);
                $file->file_path = $file->name_file = null;
            }
            if ($this->removeExampleFile && $file->example_path) {
                $this->deleteFileFromStorage($file->example_path);
                $file->example_path = $file->example_name_file = null;
            }
            if ($this->documentFile) {
                $this->deleteFileFromStorage($file->file_path);
                $file->file_path = $this->documentFile->store('files', 'public');
                $file->name_file = $this->documentFile->getClientOriginalName();
            }
            if ($this->documentExample) {
                $this->deleteFileFromStorage($file->example_path);
                $file->example_path      = $this->documentExample->store('files/examples', 'public');
                $file->example_name_file = $this->documentExample->getClientOriginalName();
            }
        }

        $file->save();
    }

    private function deleteFileFromStorage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function resetDocumentFields(): void
    {
        $this->reset([
            'documentName', 'documentDeadline', 'documentFile',
            'documentExample', 'maxSize', 'documentFirman',
            'documentObservations', 'documentUploadMode', 'isIndividual',
        ]);
        $this->documentUploadMode = 'bidirectional';
    }

    private function documentValidationRules(bool $editing = false): array
    {
        $uniqueRule = Rule::unique('files', 'name')
            ->where(fn ($q) => $q->where('period_id', $this->periodId));

        if ($editing && $this->documentId) {
            $uniqueRule = $uniqueRule->ignore($this->documentId);
        }

        $rules = [
            'documentName'         => ['required', 'string', $uniqueRule],
            'documentFile'         => 'nullable|file|mimes:doc,docx',
            'documentExample'      => 'nullable|file|mimes:pdf',
            'maxSize'              => 'required|integer|min:1|max:20480',
            'documentUploadMode'   => 'required|in:user_only,admin_only,bidirectional',
            'documentFirman'       => 'nullable|string',
            'documentObservations' => 'nullable|string',
            'isIndividual'         => 'required_if:documentUploadMode,admin_only|boolean',
        ];

        if ($this->documentUploadMode !== 'admin_only') {
            $rules['documentDeadline'] = "required|date|after_or_equal:{$this->start_date}|before_or_equal:{$this->end_date}";
        }

        $messages = [
            'documentName.required'      => 'Debes escribir el nombre del documento',
            'documentName.unique'        => 'Ya existe un documento con este nombre en el periodo',
            'documentDeadline.required'  => 'Debes establecer una fecha límite',
            'documentDeadline.date'      => 'La fecha límite no es válida',
            'documentDeadline.after_or_equal'  => 'La fecha límite no puede ser anterior al inicio del periodo',
            'documentDeadline.before_or_equal' => 'La fecha límite no puede ser posterior al fin del periodo',
            'documentUploadMode.in'      => 'El modo de carga seleccionado no es válido',
            'maxSize.required'           => 'Debes establecer un tamaño máximo',
            'maxSize.integer'            => 'El tamaño máximo debe ser un número entero',
            'maxSize.min'                => 'El tamaño máximo debe ser al menos 1 KB',
            'maxSize.max'                => 'El tamaño máximo no puede exceder 20 MB',
            'isIndividual.required_if'   => 'Debes indicar si el documento es individual',
        ];

        return [$rules, $messages];
    }
}