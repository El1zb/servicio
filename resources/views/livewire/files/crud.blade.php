<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Archivos</h1>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-4 gap-4">
        <flux:input type="text" wire:model.live="search" placeholder="Buscar archivos..." />
        <flux:button variant="primary" wire:click="create">Nuevo</flux:button>
    </div>

    <div class="flex flex-col mt-5">
        <div class="overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">
                <table class="w-full">
                    <thead>
                        <tr>
                            <x-th-table>Nombre</x-th-table>
                            <x-th-table>Periodo</x-th-table>
                            <x-th-table>Fecha límite</x-th-table>
                            <x-th-table>Archivo</x-th-table>
                            <x-th-table>Ejemplo</x-th-table>
                            <x-th-table>Acciones</x-th-table>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $file)
                            <tr>
                                <x-td-table>{{ $file->name }}</x-td-table>
                                <x-td-table>{{ $file->period->name ?? 'N/A' }}</x-td-table>
                                <x-td-table>{{ $file->limit_date }}</x-td-table>
                                <x-td-table>
    @if($file->file_path)
        <flux:button 
            size="xs" 
            color="secondary" 
            wire:click="previewFile('{{ $file->file_path }}', '{{ $file->name_file }}')" 
            class="flex justify-start truncate max-w-[150px]" 
            title="{{ $file->name_file }}">
            {{ $file->name_file }}
        </flux:button>
    @else
        <span class="text-gray-500">No subido</span>
    @endif
</x-td-table>

<x-td-table>
    @if($file->example_path)
        <flux:button 
            size="xs" 
            color="secondary" 
            wire:click="previewFile('{{ $file->example_path }}', '{{ $file->example_name_file ?? 'Sin nombre' }}')" 
            class="flex justify-start truncate max-w-[150px]" 
            title="{{ $file->example_name_file ?? 'Sin nombre' }}">
            {{ $file->example_name_file ?? 'Sin nombre' }}
        </flux:button>
    @else
        <span class="text-gray-500">No subido</span>
    @endif
</x-td-table>

                                <x-td-table>
                                    <flux:button size="xs" wire:click="edit({{ $file->id }})" color="emerald" variant="primary">Editar</flux:button>
                                    <flux:button size="xs" wire:click="delete({{ $file->id }})" color="red" variant="primary">Eliminar</flux:button>
                                </x-td-table>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center">No hay archivos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $files->links() }}
    </div>

    <!-- Modal crear / editar -->
    @if($isOpen)
        <flux:modal wire:model="isOpen" class="md:w-96">
            <div class="space-y-6">
                <flux:heading size="lg">{{ $fileId ? 'Editar Archivo' : 'Crear Archivo' }}</flux:heading>

                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input wire:model="name" type="text" />
                    <flux:error name="name" />
                </flux:field>

                

                <flux:field>
                    <flux:label>Periodo</flux:label>
                    <flux:select wire:model="period_id" required>
                        <option value="">Seleccione un periodo</option>
                        @foreach($periods as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->start_date }} - {{ $p->end_date }})</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="period_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Fecha límite</flux:label>
                    <flux:input wire:model="limit_date" type="date" />
                    <flux:error name="limit_date" />
                </flux:field>

                <flux:field>
                    <flux:label>Archivo</flux:label>
                    <flux:input type="file" wire:model="file_path" class="custom-file" accept=".doc,.docx" />
                    <flux:error name="file_path" />

                    {{-- Mostrar archivo actual solo si estamos editando y no hay uno nuevo seleccionado --}}
                    @if($fileId && $name_file && !$file_path)
                        <p class="text-sm text-gray-600 mt-2">Archivo actual: {{ $name_file }}</p>
                    @endif
                </flux:field>

                <flux:field>
                    <flux:label>Ejemplo (opcional)</flux:label>
                    <flux:input type="file" wire:model="example_path" class="custom-file" accept=".pdf"/>
                    <flux:error name="example_path" />

                    {{-- Mostrar ejemplo actual solo si estamos editando y no hay uno nuevo seleccionado --}}
                    @if($fileId && $example_name_file && !$example_path)
                        <p class="text-sm text-gray-600 mt-2">Ejemplo actual: {{ $example_name_file }}</p>
                    @endif
                </flux:field>


                <flux:field>
                    <flux:label>Tamaño máximo (KB)</flux:label>
                    <flux:input wire:model="max_size" type="number" min="1" max="10240" />
                    <flux:error name="max_size" />
                </flux:field>

                <div class="flex gap-2 justify-end">
                    <flux:button variant="ghost" wire:click="closeModal">Cancelar</flux:button>
                    <flux:button wire:click="save" variant="primary">Guardar</flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

    <!-- Modal vista previa -->
@if($previewPath)
<flux:modal wire:model="previewPath" class="md:w-3/4 lg:w-2/3">
    <div class="flex flex-col space-y-4">

        @php
            $ext = strtolower(pathinfo($previewPath, PATHINFO_EXTENSION));
        @endphp

        @if($ext === 'pdf')
            <!-- Solo se muestra el encabezado si es PDF -->
            <flux:heading size="lg">Vista previa: {{ $previewName }}</flux:heading>
            <iframe src="{{ asset($previewPath) }}" class="w-full h-96 border"></iframe>
        @else
            <!-- Para otros tipos, solo el botón de descarga -->
            <p class="text-center text-gray-400">
                No se puede previsualizar este tipo de archivo. Descargalo:
            </p>
            <div class="text-center">
                <flux:button size="xs" color="secondary" class="inline-block px-3 py-1 whitespace-normal break-words max-w-full">
                    <a href="{{ asset($previewPath) }}" target="_blank">
                        {{ $previewName }}
                    </a>
                </flux:button>
            </div>
        @endif

        <div class="flex justify-end">
            <flux:button variant="ghost" wire:click="$set('previewPath', null)">Cerrar</flux:button>
        </div>

    </div>
</flux:modal>
@endif



</div>
