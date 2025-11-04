<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Aprobación de Estudiantes</h1>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('message') }}</div>
    @endif
    @if (session()->has('info'))
        <div class="bg-blue-100 text-blue-800 p-2 rounded mb-4">{{ session('info') }}</div>
    @endif

    <div class="flex justify-between items-center mb-4 gap-4">
        <flux:input type="text" wire:model.live="search" placeholder="Buscar estudiantes..." />
    </div>

    <div class="flex flex-col mt-5">
        <div class="overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                <table class="w-full">
                    <thead>
                        <tr>
                            <x-th-table>Nombre</x-th-table>
                            <x-th-table>Avance Reticular</x-th-table>
                            <x-th-table>Estatus</x-th-table>
                            <x-th-table>Acciones</x-th-table>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <x-td-table>{{ $student->name }} {{ $student->last_name_paterno }} {{ $student->last_name_materno }}</x-td-table>
                                <x-td-table>{{ $student->reticular_progress }}%</x-td-table>
                                <x-td-table>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $student->status_class }}">
                                        {{ $student->status_label }}
                                    </span>
                                </x-td-table>
                                <x-td-table class="flex gap-2">
                                    <flux:button size="xs" color="blue" variant="primary" wire:click="viewDetails({{ $student->id }})">
                                        Ver Detalles
                                    </flux:button>
                                    <flux:button size="xs" color="green" variant="primary" wire:click="approve({{ $student->id }})">
                                        Aprobar
                                    </flux:button>
                                    <flux:button size="xs" color="red" variant="primary" wire:click="reject({{ $student->id }})">
                                        Rechazar
                                    </flux:button>
                                </x-td-table>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay estudiantes para aprobar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">{{ $students->links() }}</div>

    <!-- Modal Detalles / Edición -->
    @if($showModal && $selectedStudent)
        <flux:modal wire:model="showModal" class="md:w-3/4 lg:w-2/3">
            <div class="space-y-6">
                <flux:heading size="lg">Detalles de {{ $selectedStudent->name }}</flux:heading>

                <!-- Datos Personales -->
                <div class="space-y-2">
                    <h3 class="text-md font-semibold text-gray-300 border-b pb-1">Datos Personales</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        @if($editMode)
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Apellido Paterno</label>
                                <input type="text" class="w-full border rounded p-1" wire:model.defer="studentData.last_name_paterno">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Apellido Materno</label>
                                <input type="text" class="w-full border rounded p-1" wire:model.defer="studentData.last_name_materno">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Nombre</label>
                                <input type="text" class="w-full border rounded p-1" wire:model.defer="studentData.name">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">CURP</label>
                                <input type="text" class="w-full border rounded p-1" wire:model.defer="studentData.curp">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">RFC</label>
                                <input type="text" class="w-full border rounded p-1" wire:model.defer="studentData.rfc">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Teléfono</label>
                                <input type="text" class="w-full border rounded p-1" wire:model.defer="studentData.phone">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Correo Personal</label>
                                <input type="email" class="w-full border rounded p-1" wire:model.defer="studentData.personal_email">
                            </div>
                        @else
                            <p><strong>Apellido Paterno:</strong> {{ $selectedStudent->last_name_paterno }}</p>
                            <p><strong>Apellido Materno:</strong> {{ $selectedStudent->last_name_materno }}</p>
                            <p><strong>CURP:</strong> {{ $selectedStudent->curp }}</p>
                            <p><strong>RFC:</strong> {{ $selectedStudent->rfc }}</p>
                            <p><strong>Teléfono:</strong> {{ $selectedStudent->phone }}</p>
                            <p><strong>Correo Personal:</strong> {{ $selectedStudent->personal_email }}</p>
                        @endif
                    </div>
                </div>

                <!-- Datos Académicos -->
                <div class="space-y-2">
                    <h3 class="text-md font-semibold text-gray-300 border-b pb-1">Datos Académicos</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        @if($editMode)
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Correo Institucional</label>
                                <input type="email" class="w-full border rounded p-1" wire:model.defer="studentData.institutional_email">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Número de Control</label>
                                <input type="text" class="w-full border rounded p-1" wire:model.defer="studentData.control_number">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Sistema</label>
                                <select class="w-full border rounded p-1" wire:model.defer="studentData.system">
                                    <option value="Escolarizado">Escolarizado</option>
                                    <option value="Sabatino">Sabatino</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Semestre</label>
                                <select class="w-full border rounded p-1" wire:model.defer="studentData.semester_id">
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Campus</label>
                                <select class="w-full border rounded p-1" wire:model.defer="studentData.campus_id">
                                    @foreach($campuses as $campus)
                                        <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Carrera</label>
                                <select class="w-full border rounded p-1" wire:model.defer="studentData.career_id">
                                    @foreach($careers as $career)
                                        <option value="{{ $career->id }}">{{ $career->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Periodo</label>
                                <select class="w-full border rounded p-1" wire:model.defer="studentData.period_id">
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}">{{ $period->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400">Avance Reticular (%)</label>
                                <input type="number" class="w-full border rounded p-1" wire:model.defer="studentData.reticular_progress" min="0" max="100">
                            </div>
                        @else
                            <p><strong>Correo Institucional:</strong> {{ $selectedStudent->institutional_email }}</p>
                            <p><strong>Número de Control:</strong> {{ $selectedStudent->control_number }}</p>
                            <p><strong>Sistema:</strong> {{ $selectedStudent->system }}</p>
                            <p><strong>Semestre:</strong> {{ $selectedStudent->semester?->name }}</p>
                            <p><strong>Campus:</strong> {{ $selectedStudent->campus?->name }}</p>
                            <p><strong>Carrera:</strong> {{ $selectedStudent->career?->name }}</p>
                            <p><strong>Periodo:</strong> {{ $selectedStudent->period?->name }}</p>
                            <p><strong>Avance Reticular:</strong> {{ $selectedStudent->reticular_progress }}%</p>
                        @endif
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row sm:justify-end gap-2">
                    <!-- Fila 1: Cerrar, Editar/Cancelar, Guardar -->
                    <div class="flex gap-2 flex-wrap">
                        <flux:button variant="ghost" wire:click="closeModal">Cerrar</flux:button>

                        @if($editMode)
                            <flux:button variant="ghost" wire:click="cancelEdit">Cancelar</flux:button>
                            <flux:button color="blue" variant="primary" wire:click="updateStudent">Guardar cambios</flux:button>
                        @else
                            <flux:button color="blue" variant="primary" wire:click="editStudent({{ $selectedStudent->id }})">Editar</flux:button>
                        @endif
                    </div>

                    <!-- Fila 2: Aprobar / Rechazar solo si NO estamos editando -->
                    @unless($editMode)
                        <div class="flex gap-2 flex-wrap mt-2 sm:mt-0">
                            <flux:button color="green" variant="primary" wire:click="approve({{ $selectedStudent->id }})">Aprobar</flux:button>
                            <flux:button color="red" variant="primary" wire:click="reject({{ $selectedStudent->id }})">Rechazar</flux:button>
                        </div>
                    @endunless
                </div>


            </div>
        </flux:modal>
    @endif

    <!-- Modal Rechazo -->
    @if($showRejectModal && $selectedStudent)
        <flux:modal wire:model="showRejectModal" class="md:w-1/2 lg:w-1/3">
            <div class="space-y-4">
                <flux:heading size="lg">Motivo de Rechazo - {{ $selectedStudent->name }}</flux:heading>

                <textarea
                    class="w-full border rounded p-2"
                    rows="4"
                    placeholder="Ingresa el motivo del rechazo..."
                    wire:model="rejectionReason"
                ></textarea>

                <div class="flex justify-end gap-2">
                    <flux:button variant="ghost" wire:click="$set('showRejectModal', false)">Cancelar</flux:button>
                    <flux:button color="red" variant="primary" wire:click="confirmReject">Rechazar</flux:button>
                </div>
            </div>
        </flux:modal>
    @endif
</div>
