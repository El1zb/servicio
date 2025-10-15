<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Aprobación de Estudiantes</h1>

    <!-- Mensajes de sesión -->
    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('message') }}</div>
    @endif
    @if (session()->has('info'))
        <div class="bg-blue-100 text-blue-800 p-2 rounded mb-4">{{ session('info') }}</div>
    @endif

    <!-- Barra de búsqueda -->
    <div class="flex justify-between items-center mb-4 gap-4">
        <flux:input type="text" wire:model.live="search" placeholder="Buscar estudiantes..." />
    </div>

    <!-- Tabla de estudiantes -->
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
                                    <!-- Ver Detalles -->
                                    <flux:button size="xs" color="blue" variant="primary" 
                                                 wire:click="viewDetails({{ $student->id }})">
                                        Ver Detalles
                                    </flux:button>

                                    <!-- Aprobar -->
                                    <flux:button size="xs" color="green" variant="primary"
                                                 wire:click="approve({{ $student->id }})">
                                        Aprobar
                                    </flux:button>

                                    <!-- Rechazar -->
                                    <flux:button size="xs" color="red" variant="primary"
                                                 wire:click="reject({{ $student->id }})">
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

    <!-- Paginación -->
    <div class="mt-4">
        {{ $students->links() }}
    </div>

    <!-- Modal Detalles -->
    @if($showModal && $selectedStudent)
        <flux:modal wire:model="showModal" class="md:w-3/4 lg:w-2/3">
            <div class="space-y-6">
                <flux:heading size="lg ">Detalles de {{ $selectedStudent->name }}</flux:heading>

                <!-- DATOS PERSONALES -->
                <div class="space-y-2">
                    <h3 class="text-md font-semibold text-gray-300 border-b pb-1">Datos Personales</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <p><strong>Apellido Paterno:</strong> {{ $selectedStudent->last_name_paterno }}</p>
                        <p><strong>Apellido Materno:</strong> {{ $selectedStudent->last_name_materno }}</p>
                        <p><strong>CURP:</strong> {{ $selectedStudent->curp }}</p>
                        <p><strong>RFC:</strong> {{ $selectedStudent->rfc }}</p>
                        <p><strong>Teléfono:</strong> {{ $selectedStudent->phone }}</p>
                        <p><strong>Correo Personal:</strong> {{ $selectedStudent->personal_email }}</p>
                    </div>
                </div>

                <!-- DATOS ACADÉMICOS -->
                <div class="space-y-2">
                    <h3 class="text-md font-semibold text-gray-300 border-b pb-1">Datos Académicos</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <p><strong>Correo Institucional:</strong> {{ $selectedStudent->institutional_email }}</p>
                        <p><strong>Número de Control:</strong> {{ $selectedStudent->control_number }}</p>
                        <p><strong>Sistema:</strong> {{ $selectedStudent->system }}</p>
                        <p><strong>Semestre:</strong> {{ $selectedStudent->semester?->name }}</p>
                        <p><strong>Campus:</strong> {{ $selectedStudent->campus?->name }}</p>
                        <p><strong>Carrera:</strong> {{ $selectedStudent->career?->name }}</p>
                        <p><strong>Periodo:</strong> {{ $selectedStudent->period?->name }}</p>
                        <p><strong>Avance Reticular:</strong> {{ $selectedStudent->reticular_progress }}%</p>
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="flex gap-2 justify-end">
                    <flux:button variant="ghost" wire:click="closeModal">Cerrar</flux:button>
                    <flux:button color="green" variant="primary" wire:click="approve({{ $selectedStudent->id }})">
                        Aprobar
                    </flux:button>
                    <flux:button color="red" variant="primary" wire:click="reject({{ $selectedStudent->id }})">
                        Rechazar
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

</div>
