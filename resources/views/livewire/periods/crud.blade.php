<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Periodos</h1>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-4 gap-4">
        <flux:input 
            type="text" 
            wire:model.live="search" 
            placeholder="Buscar periodos..." 
        />
        <!-- <flux:modal.trigger name="create"> -->
            <flux:button variant="primary" wire:click="create">Nuevo</flux:button>
        <!-- </flux:modal.trigger> -->
    </div>

    <div class="flex flex-col mt-5">
        <div class="overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 dark:border-gray-600 shadow sm:rounded-lg">
                <table class="w-full">
                    <thead>
                        <tr>
                            <x-th-table>Nombre</x-th-table>
                            <x-th-table>Inicio</x-th-table>
                            <x-th-table>Fin</x-th-table>
                            <x-th-table>Acciones</x-th-table>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($periods as $period)
                            <tr>
                                <x-td-table>{{ $period->name }}</x-td-table>
                                <x-td-table>{{ $period->start_date }}</x-td-table>
                                <x-td-table>{{ $period->end_date }}</x-td-table>
                                <x-td-table>
                                    <flux:button size="xs" wire:click="edit({{ $period->id }})" color="emerald" variant="primary">Editar</flux:button>
                                    <flux:button size="xs" wire:click="delete({{ $period->id }})" color="red" variant="primary">Eliminar</flux:button>
                                </x-td-table>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center">No hay periodos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $periods->links() }}
    </div>

    @if($isOpen)
    <flux:modal wire:model.self="isOpen" class="md:w-96">
        <div class="space-y-6">
            <flux:heading size="lg">{{ $periodId ? 'Editar Periodo' : 'Crear Periodo' }}</flux:heading>

            <flux:field>
                <flux:label>Nombre</flux:label>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Fecha Inicio</flux:label>
                <flux:input wire:model="start_date" type="date" />
                <flux:error name="start_date" />
            </flux:field>

            <flux:field>
                <flux:label>Fecha Fin</flux:label>
                <flux:input wire:model="end_date" type="date" />
                <flux:error name="end_date" />
            </flux:field>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button
                        wire:click="closeModal"
                        class="
                            group relative
                            h-12
                            px-5
                            rounded-[var(--radius-md)]
                            bg-[var(--modal-btn-cancel)]!
                            text-[var(--modal-btn-cancel-text)]!
                            shadow-md shadow-[var(--modal-btn-cancel-shadow)]
                            hover:bg-[var(--modal-btn-cancel-hover)]!
                            hover:shadow-lg hover:-translate-y-0.5
                            transition-all duration-300
                            disabled:opacity-60 disabled:cursor-not-allowed
                        "
                    >
                        Cancelar
                    </flux:button>

                </flux:modal.close>
                <flux:button
                    wire:click="save"
                    class="
                        group relative
                        h-12
                        px-6
                        rounded-[var(--radius-md)]
                        bg-[var(--modal-btn-primary)]!
                        text-[var(--modal-btn-primary-text)]!
                        shadow-lg shadow-[var(--modal-btn-primary-shadow)]
                        hover:bg-[var(--modal-btn-primary-hover)]!
                        hover:shadow-xl hover:-translate-y-0.5
                        transition-all duration-300
                    "
                >
                    Guardar
                </flux:button>

            </div>
        </div>
    </flux:modal>
    @endif
    <!-- Modal -->
    
</div>
