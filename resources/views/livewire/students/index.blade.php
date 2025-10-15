<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">Estudiantes y Documentos</h1>

    <div class="flex flex-col md:flex-row gap-4 mb-4">
        <!-- Buscador en tiempo real -->
        <flux:input 
            type="text" 
            wire:model.live="search" 
            placeholder="Buscar por nombre o número de control..." 
        />

        <!-- Combobox Periodo en tiempo real -->
        <flux:select wire:model.live="periodId">
            <option value="">Todos los periodos</option>
            @foreach($periods as $period)
                <option value="{{ $period->id }}">{{ $period->name }}</option>
            @endforeach
        </flux:select>

        <!-- Combobox Carrera en tiempo real -->
        <flux:select wire:model.live="careerId">
            <option value="">Todas las carreras</option>
            @foreach($careers as $career)
                <option value="{{ $career->id }}">{{ $career->name }}</option>
            @endforeach
        </flux:select>
    </div>

    <div class="flex flex-col mt-5">
        <div class="overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-zinc-700">
                            <x-th-table>Nombre</x-th-table>
                            <x-th-table>Número de control</x-th-table>
                            <x-th-table>Documentos entregados</x-th-table>
                            <x-th-table>Total de documentos</x-th-table>
                            <x-th-table>Opciones</x-th-table>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <x-td-table>{{ $student->name }} {{ $student->last_name_paterno }} {{ $student->last_name_materno }}</x-td-table>
                                <x-td-table>{{ $student['control_number'] }}</x-td-table>
                                <x-td-table>{{ $student['delivered'] }}</x-td-table>
                                <x-td-table>{{ $student['total'] }}</x-td-table>
                                <x-td-table>
                                    <a href="{{ route('students.details', $student['id']) }}" 
                                    class="px-3 py-1 text-sm rounded bg-blue-500 text-white hover:bg-blue-600">
                                    Detalles
                                    </a>
                                </x-td-table>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center">No se encontraron estudiantes</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $pagination->links() }}
    </div>

</div>
