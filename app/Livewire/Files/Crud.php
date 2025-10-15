<?php

namespace App\Livewire\Files;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\File;
use App\Models\Period;
use Illuminate\Validation\Rule;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class Crud extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $name, $name_file, $example_name_file, $limit_date, $period_id, $file_path, $example_path, $max_size = 10240;
    public $fileId;
    public $isOpen = false;

    public $previewPath = null; // ruta del archivo que se va a mostrar
    public $previewName = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $files = File::with('period')
            ->where('name', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        $periods = Period::all();

        return view('livewire.files.crud', [
            'files' => $files,
            'periods' => $periods,
        ]);
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit(File $file)
    {
        $this->fileId = $file->id;
        $this->name = $file->name;
        $this->limit_date = $file->limit_date;
        $this->period_id = $file->period_id;
        $this->max_size = $file->max_size;

        $this->name_file = $file->name_file;
        $this->example_name_file = $file->example_name_file;

        $this->file_path = null;
        $this->example_path = null;

        $this->isOpen = true;
    }

    public function save()
    {
        // 🔹 Forzar máximo de 10 MB
        if ($this->max_size > 10240) {
            $this->max_size = 10240;
        }

        $this->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('files')->where(function ($query) {
                    return $query->where('period_id', $this->period_id);
                })->ignore($this->fileId),
            ],
            'limit_date' => ['required', 'date', function($attribute, $value, $fail) {
                if($this->period_id) {
                    $period = Period::find($this->period_id);
                    if($period && ($value < $period->start_date || $value > $period->end_date)) {
                        $fail('La fecha límite debe estar dentro del periodo seleccionado.');
                    }
                }
            }],
            'period_id' => 'required|exists:periods,id',
            'file_path' => $this->fileId 
                ? 'nullable|file|max:'.$this->max_size.'|mimes:pdf,doc,docx'
                : 'required|file|max:'.$this->max_size.'|mimes:pdf,doc,docx',
            'example_path' => 'nullable|file|max:'.$this->max_size.'|mimes:pdf,doc,docx',
            'max_size' => 'required|integer|min:1',
        ]);

        // Guardar archivo principal
        if ($this->file_path) {
            $filePath = $this->file_path->store('files', 'public');
            $fileName = $this->file_path->getClientOriginalName();
        } else {
            $filePath = $this->fileId ? File::find($this->fileId)->file_path : null;
            $fileName = $this->fileId ? File::find($this->fileId)->name_file : null;
        }

        // Guardar archivo de ejemplo
        if ($this->example_path) {
            $examplePath = $this->example_path->store('files/examples', 'public');
            $exampleName = $this->example_path->getClientOriginalName();
        } else {
            $examplePath = $this->fileId ? File::find($this->fileId)->example_path : null;
            $exampleName = $this->fileId ? File::find($this->fileId)->example_name_file : null;
        }

        File::updateOrCreate(
            ['id' => $this->fileId],
            [
                'name' => $this->name,
                'name_file' => $fileName,
                'limit_date' => $this->limit_date,
                'period_id' => $this->period_id,
                'file_path' => $filePath,
                'example_path' => $examplePath,
                'example_name_file' => $exampleName,
                'max_size' => $this->max_size,
            ]
        );

        session()->flash('message', $this->fileId ? 'Archivo actualizado correctamente.' : 'Archivo creado correctamente.');

        $this->closeModal();
        $this->resetInput();
    }

    public function delete(File $file)
    {
        if (\Storage::disk('public')->exists($file->file_path)) {
            \Storage::disk('public')->delete($file->file_path);
        }
        if ($file->example_path && \Storage::disk('public')->exists($file->example_path)) {
            \Storage::disk('public')->delete($file->example_path);
        }

        $file->delete();
        session()->flash('message', 'Archivo eliminado correctamente.');
    }

    private function resetInput()
    {
        $this->fileId = null;
        $this->name = '';
        $this->limit_date = '';
        $this->period_id = '';
        $this->file_path = null;
        $this->example_path = null;
        $this->max_size = 10240;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function previewFile($path, $name)
    {
        $this->previewPath = $path;
        $this->previewName = $name;
    }
}
