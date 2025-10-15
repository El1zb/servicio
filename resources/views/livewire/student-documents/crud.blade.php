<div class="p-6 space-y-6">
    <h1 class="text-2xl font-bold mb-4">Mis Documentos</h1>

    <!-- Mensajes generales de sesión -->
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 text-red-800 p-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif


    @forelse ($documents as $limitDate => $docs)
        @php 
            $isExpired = $limitDate !== 'Sin fecha' && now()->gt(Carbon\Carbon::parse($limitDate)) || collect($docs)->contains(fn($doc) => $doc->status === 'revisado');
        @endphp

        <div class="rounded-xl shadow-md p-5 {{ $isExpired ? 'bg-gray-100 border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700' : 'bg-white border border-gray-200 dark:border-zinc-500 dark:bg-zinc-800' }}">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    Fecha límite: 
                    <span class="font-bold">{{ $limitDate !== 'Sin fecha' ? Carbon\Carbon::parse($limitDate)->format('d/m/Y') : 'Sin fecha' }}</span>
                </h2>

                @if($isExpired)
                    <span class="bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-200 text-sm px-3 py-1 rounded-full font-semibold">Plazo vencido</span>
                @else
                    <span class="bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100 text-sm px-3 py-1 rounded-full font-semibold">Activo</span>
                @endif
            </div>

            <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                @foreach($docs as $document)
                    <li class="flex justify-between items-center py-3">
                        <div class="flex flex-col gap-1">
                            <span class="font-medium text-gray-800 dark:text-gray-100">{{ $document->name }}</span>
                            <span class="text-sm text-blue-600 dark:text-blue-400">
                                @if($document->student_file_name)
                                    @php
                                        $nameParts = explode('_'.$student->control_number, $document->student_file_name);
                                        $baseName = $nameParts[0];
                                        $extension = pathinfo($document->student_file_name, PATHINFO_EXTENSION);
                                    @endphp
                                    {{ $baseName }}.{{ $extension }}
                                @endif
                            </span>

                            <!-- Estado y botón de comentarios -->
                            <div class="flex items-center gap-2 mt-1">
                                @if($document->student_file_name)
                                    <span class="px-2 py-1 rounded text-xs font-medium w-fit
                                        @if($document->status === 'revisado') bg-green-100 text-green-700 
                                        @elseif($document->status === 'rechazado') bg-red-100 text-red-700 
                                        @else bg-yellow-100 text-yellow-700 @endif">
                                        {{ ucfirst($document->status) }}
                                    </span>
                                @endif


                                @if(!empty($document->comments))
                                    <div class="relative group">
                                        <button wire:click="openComments({{ $document->id }})"
                                                class="px-2 py-1 text-xs bg-gray-200 dark:bg-zinc-600 text-gray-800 dark:text-gray-200 rounded hover:brightness-95 transition flex items-center gap-1">
                                            <i class="fas fa-comments"></i>
                                        </button>
                                        <!-- Tooltip con mismo color que el botón -->
                                        <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-gray-800 dark:text-gray-200 bg-gray-200 dark:bg-zinc-600 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                            Ver comentarios
                                        </div>
                                    </div>
                                @endif


                            </div>
                        </div>


                        

                        <div class="flex flex-col gap-1 items-end">
                            <div class="flex gap-2">

                               <!-- Botón Word -->
                                @if($document->file?->file_path)
                                    <div class="relative group">
                                        <button wire:click="previewFile('{{ $document->file->file_path }}','{{ $document->file->name_file }}')"
                                                class="ml-2 flex items-center gap-1 px-2 py-1 text-xs rounded transition
                                                    {{ $isExpired 
                                                            ? 'bg-gray-300 text-gray-600 dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed'
                                                            : 'bg-sky-600 text-white hover:brightness-110' }}"
                                                {{ $isExpired ? 'disabled' : '' }}>
                                            <i class="fas fa-file-word"></i>
                                        </button>
                                        <!-- Tooltip -->
                                        <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs 
                                                    {{ $isExpired ? 'text-gray-600 dark:text-gray-400 bg-gray-300 dark:bg-gray-700' : 'text-white bg-sky-600' }}
                                                    rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                            Ver Word
                                        </div>
                                    </div>
                                @endif

                                <!-- Botón PDF -->
                                @if($document->file?->example_path)
                                    <div class="relative group">
                                        <button wire:click="previewFile('{{ $document->file->example_path }}','{{ $document->file->example_name_file }}')"
                                                class="ml-1 flex items-center gap-1 px-2 py-1 text-xs rounded transition
                                                    {{ $isExpired 
                                                            ? 'bg-gray-300 text-gray-600 dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed'
                                                            : 'bg-red-800 text-white hover:brightness-110' }}"
                                                {{ $isExpired ? 'disabled' : '' }}>
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                        <!-- Tooltip -->
                                        <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs 
                                                    {{ $isExpired ? 'text-gray-600 dark:text-gray-400 bg-gray-300 dark:bg-gray-700' : 'text-white bg-red-800' }}
                                                    rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                            Ver PDF
                                        </div>
                                    </div>
                                @endif



                                

                                <!-- Botón subir/reemplazar -->
                                <button type="button" 
                                        onclick="document.getElementById('fileInput-{{ $document->id }}').click()" 
                                        class="px-3 py-1 text-sm rounded {{ $isExpired ? 'bg-gray-300 text-gray-600 dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed' : 'bg-blue-500 text-white' }}"
                                        {{ $isExpired ? 'disabled' : '' }}>
                                    {{ $document->student_file_name ? 'Reemplazar' : 'Subir archivo' }}
                                </button>

                                @if($document->student_file_path)
                                    <button wire:click="previewFile('{{ $document->student_file_path }}','{{ $document->student_file_name }}')"
                                            class="px-3 py-1 text-sm bg-gray-300 dark:bg-indigo-500 dark:text-gray-200 rounded">Ver</button>
                                @endif
                            </div>

                            @if($document->file)
                                <span class="text-xs text-gray-400 dark:text-gray-500 ">
                                    Tamaño máximo: {{ $this->formatSize($document->file->max_size*1024) }}
                                </span>
                            @endif

                            <input type="file" id="fileInput-{{ $document->id }}" class="hidden" 
                         wire:model="fileUpload.{{ $document->id }}" accept=".pdf">

                        </div>
                        
                    </li>
                @endforeach
            </ul>
        </div>
    @empty
        <div class="text-center text-gray-500 dark:text-gray-400 py-10">
            No tienes documentos asignados.
        </div>
    @endforelse

    @if($previewPath)
    <flux:modal wire:model="previewPath" class="md:w-3/4 lg:w-2/3">
        <div class="flex flex-col space-y-4">
            @php
                // Nombre limpio del archivo
                $cleanName = $previewName;
                $pos = strpos($cleanName, '_');
                if ($pos !== false) {
                    $cleanName = substr($cleanName, 0, $pos);
                }
                $ext = strtolower(pathinfo($previewPath, PATHINFO_EXTENSION));
            @endphp

            <flux:heading size="lg">Vista previa: {{ $cleanName }}</flux:heading>

            @if($ext === 'pdf')
                <iframe src="{{ asset('storage/'.$previewPath) }}" class="w-full h-96 border dark:border-gray-600"></iframe>
            @else
                <p class="text-center text-gray-700 dark:text-gray-300">
                    No se puede previsualizar este tipo de archivo. Descárgalo:
                </p>
                <p class="text-center text-blue-600 dark:text-blue-400">
                    <a href="{{ asset('storage/'.$previewPath) }}" target="_blank" class="underline">
                        {{ $cleanName }}
                    </a>
                </p>
            @endif

            <div class="flex justify-end">
                <flux:button variant="ghost" wire:click="$set('previewPath', null)">
                    Cerrar
                </flux:button>
            </div>
        </div>
    </flux:modal>
@endif

@if($isCommentsModalOpen && $selectedDocument)
    <flux:modal wire:model="isCommentsModalOpen" class="md:w-1/2 lg:w-1/3">
        <div class="flex flex-col space-y-4 p-4">

            <!-- Título -->
            <flux:heading size="lg" class="text-center">Observaciones/Comentarios</flux:heading>

            <!-- Contenedor de comentario estilo card con scroll si es largo -->
            <div class="flex flex-col gap-4 max-h-96 overflow-y-auto">
                @if($selectedDocument->comments)
                    <div class="flex justify-start">
                        <div class="flex bg-gray-100 dark:bg-zinc-700 text-gray-800 dark:text-gray-200 p-4 rounded-xl shadow-md border border-gray-300 dark:border-zinc-600 w-full break-words">
                            <div class="flex flex-col w-full">
                                <div class="whitespace-pre-line">{{ $selectedDocument->comments }}</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-gray-400 dark:text-gray-500">
                        No hay comentarios para este documento.
                    </div>
                @endif
            </div>

            <div class="flex justify-end mt-2">
                <flux:button variant="ghost" wire:click="closeComments">Cerrar</flux:button>
            </div>

        </div>
    </flux:modal>
@endif










</div>



