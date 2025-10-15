<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Carreras</h1>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-4 gap-4">
        <flux:input 
            type="text" 
            wire:model.live="search" 
            placeholder="Buscar carreras..." 
        />
        <flux:button variant="primary" wire:click="create">Nuevo</flux:button>
    </div>

    <div class="flex flex-col mt-5">
        <div class="overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">
                <table class="w-full">
                    <thead>
                        <tr>
                            <x-th-table>Nombre</x-th-table>
                            <x-th-table>Acciones</x-th-table>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($careers as $career)
                            <tr>
                                <x-td-table>{{ $career->name }}</x-td-table>
                                <x-td-table>
                                    <flux:button size="xs" wire:click="edit({{ $career->id }})" color="emerald" variant="primary">Editar</flux:button>
                                    <flux:button size="xs" wire:click="delete({{ $career->id }})" color="red" variant="primary">Eliminar</flux:button>
                                </x-td-table>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center">No hay carreras</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $careers->links() }}
    </div>

    @if($isOpen)
    <flux:modal wire:model.self="isOpen" class="md:w-96">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $careerId ? 'Editar Carrera' : 'Crear Carrera' }}</flux:heading>

            <flux:field>
                <flux:label>Nombre</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost" wire:click="closeModal">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button wire:click="save" variant="primary">Guardar</flux:button>
            </div>
        </div>
    </flux:modal>
    @endif
</div>
