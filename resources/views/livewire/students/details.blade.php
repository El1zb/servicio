<div class="p-6">

    <!-- Encabezado con título a la izquierda y Volver a la derecha -->
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Documentos del Estudiante</h1>

        <a href="{{ route('students.index') }}" 
            class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow hover:bg-blue-600 transition-colors duration-200 inline-flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver
        </a>

    </div>

    <!-- 🔹 Mensajes generales de sesión -->
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

    <div class="mb-4">
        <h2 class="text-xl font-bold mb-2">
            {{ $student->name }} {{ $student->last_name_paterno ?? '' }} {{ $student->last_name_materno }}
        </h2>
        <div class="flex flex-wrap items-center gap-3 text-gray-400">
            <p>
                <strong>Carrera:</strong> {{ $student->career->name }} |
                <strong>Número de control:</strong> {{ $student->control_number }} |
                <strong>Periodo:</strong> {{ $student->period->name }}
            </p>

            <!-- Botón Imprimir Seguimiento -->
            <button 
                wire:click="exportPDF"
                class="ml-2 px-3 py-1 text-sm bg-emerald-600 text-white font-semibold rounded-md hover:bg-emerald-500 transition-colors duration-200 inline-flex items-center gap-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M6 9V2h12v7m-9 13h6m-6-4h6m-7-8h8a2 2 0 012 2v7H5v-7a2 2 0 012-2z" />
                </svg>
                Imprimir Seguimiento
            </button>
        </div>

    </div>

    <hr class="my-4 border-gray-300 dark:border-gray-600">

    <div class="flex flex-col mt-5">
        <div class="overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-zinc-700">
                            <x-th-table>Documento</x-th-table>
                            <x-th-table>Archivo entregado</x-th-table>
                            <x-th-table>Estatus</x-th-table>
                            <x-th-table>Fecha entrega</x-th-table>
                            <x-th-table>Acciones</x-th-table>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse ($documents as $doc)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800">
                                <x-td-table>{{ $doc->file->name ?? $doc->name }}</x-td-table>

                                <x-td-table>
                                    @if ($doc->student_file_path)
                                        <flux:button size="xs" color="secondary" wire:click="openModal({{ $doc->id }})">
                                            {{ $doc->student_file_name }}
                                        </flux:button>
                                    @else
                                        <span class="text-gray-500">No entregado</span>
                                    @endif
                                </x-td-table>

                                <x-td-table>
                                    @if ($doc->student_file_path)
                                        <span class="px-2 py-1 rounded text-xs font-medium
                                            @if($doc->status === 'revisado') bg-green-100 text-green-700 
                                            @elseif($doc->status === 'rechazado') bg-red-100 text-red-700 
                                            @else bg-yellow-100 text-yellow-700 @endif">
                                            {{ ucfirst($doc->status ?? 'en_revision') }}
                                        </span>
                                    @endif
                                </x-td-table>

                                <x-td-table>
                                    <flux:input 
                                        type="date"
                                        size="xs"
                                        wire:model="editingDates.{{ $doc->id }}"
                                        wire:change="updateDate({{ $doc->id }})"
                                        min="{{ $doc->file?->limit_date ? \Carbon\Carbon::parse($doc->file->limit_date)->format('Y-m-d') : '' }}"
                                    />
                                </x-td-table>







                                <x-td-table>
                                    <div class="flex gap-2">

                                        <!-- Botón Revisado -->
                                        <a href="#"
                                        @if(!$doc->student_file_path)
                                            class="px-3 py-1 text-xs rounded bg-gray-300 text-gray-600 dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed"
                                        @else
                                            wire:click.prevent="updateStatus({{ $doc->id }}, 'revisado')"
                                            class="px-3 py-1 text-xs rounded bg-emerald-500 text-white hover:bg-emerald-600 transition"
                                        @endif>
                                            Revisado
                                        </a>

                                        <!-- Botón Rechazado -->
                                        <a href="#"
                                        @if(!$doc->student_file_path)
                                            class="px-3 py-1 text-xs rounded bg-gray-300 text-gray-600 dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed"
                                        @else
                                            wire:click.prevent="openRejectModal({{ $doc->id }})"
                                            class="px-3 py-1 text-xs rounded bg-red-500 text-white hover:bg-red-600 transition"
                                        @endif>
                                            Rechazado
                                        </a>


                                    </div>
                                </x-td-table>




                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No hay documentos para este estudiante.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($isModalOpen && $selectedDocument)
    <flux:modal wire:model="isModalOpen" class="md:w-3/4 lg:w-2/3">
        <div class="flex flex-col space-y-4">

            <!-- Título -->
            <flux:heading size="lg">{{ $selectedDocument->file->name ?? $selectedDocument->name }}</flux:heading>

            <!-- Previsualización -->
            @if($previewUrl)
                @if($previewExtension === 'pdf')
                    <iframe src="{{ $previewUrl }}" class="w-full h-96 border"></iframe>
                @else
                    <p class="text-center text-gray-600">
                        No se puede previsualizar este tipo de archivo.
                    </p>
                @endif
            @endif

            <!-- Comentarios -->
            <flux:field>
                <flux:label>Observaciones / Comentarios</flux:label>
                <textarea 
                    wire:model.defer="editingComments.{{ $selectedDocument->id }}" 
                    class="w-full border-2 border-gray-300 rounded focus:ring focus:ring-blue-200 focus:border-blue-500 bg-gray-50 dark:bg-zinc-800 resize-none p-3 placeholder-gray-400"
                    rows="5"
                    placeholder="Escribe aquí tus observaciones...">
                </textarea>
            </flux:field>

            <!-- Botones: Descargar a la izquierda, Cerrar y Guardar a la derecha -->
            <div class="flex justify-between items-center">
                @if($previewUrl)
                    <a href="{{ $previewUrl }}" download="{{ $selectedDocument->student_file_name }}" 
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-500 transition text-sm">
                        Descargar
                    </a>
                @endif

                <div class="flex gap-2">
                    <flux:button variant="ghost" wire:click="$set('isModalOpen', false)">Cerrar</flux:button>
                    <flux:button wire:click="saveComments" variant="primary">Guardar</flux:button>
                </div>
            </div>

        </div>
    </flux:modal>
    @endif

    @if($isRejectModalOpen && $rejectingDocument)
    <flux:modal wire:model="isRejectModalOpen" class="md:w-1/2">
        <div class="space-y-4">
            <flux:heading size="lg">Rechazar documento</flux:heading>

            <p class="text-gray-600">
                Escribe el motivo o comentario para el rechazo de <strong>{{ $rejectingDocument->file->name ?? $rejectingDocument->name }}</strong>.
            </p>

            <flux:field>
                <flux:label>Comentario</flux:label>
                <textarea 
                    wire:model.defer="editingComments.{{ $rejectingDocument->id }}"
                    class="w-full border-2 border-gray-300 rounded focus:ring focus:ring-red-200 focus:border-red-500 bg-gray-50 dark:bg-zinc-800 resize-none p-3 placeholder-gray-400"
                    rows="5"
                    placeholder="Escribe aquí tus observaciones...">
                </textarea>
            </flux:field>

            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" wire:click="$set('isRejectModalOpen', false)">Cancelar</flux:button>
                <flux:button wire:click="saveRejection" variant="danger">Rechazar</flux:button>
            </div>
        </div>
    </flux:modal>
    @endif





</div>
