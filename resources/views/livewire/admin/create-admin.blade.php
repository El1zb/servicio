<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout>
        <div class="space-y-6">

            {{-- Header + Botón Nuevo Admin --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6"
                 style="background-color: var(--color-card-bg);">
                <div>
                    <x-auth-header
                        title="Administradores"
                        description="Gestión completa de cuentas administrativas."
                        :center="false"
                    />
                </div>
                <flux:button
                    variant="primary"
                    wire:click="create"
                    icon="plus"
                    class="group relative inline-flex items-center justify-center px-4 py-2
                        rounded-[var(--radius-md)]
                        bg-[var(--color-primary)]! text-[var(--color-bg)]!
                        shadow-lg shadow-[var(--color-border-hover)]
                        hover:bg-[var(--color-primary-2)]! hover:shadow-xl hover:-translate-y-0.5
                        transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed gap-2">
                    Nuevo Administrador
                </flux:button>
            </div>

            {{-- Lista de Administradores --}}
            <div class="rounded-xl p-5 shadow-lg" style="background-color: var(--color-card-bg);">
                @if($admins->count())
                    <div class="space-y-3 rounded-xl p-4">
                        @foreach($admins as $admin)
                            <div class="group relative rounded-xl p-4 transition-all duration-200 hover:shadow-lg border"
                                 style="background-color: var(--color-icon-bg);
                                        border-color: var(--color-border-hover);">
                                <div class="flex items-center gap-4">
                                    {{-- Contenido --}}
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-bold truncate"
                                            style="color: var(--color-primary-2);">
                                            {{ $admin->name }}
                                        </h3>
                                        <p class="text-sm truncate" style="color: var(--color-secondary);">
                                            {{ $admin->email }}
                                        </p>
                                    </div>

                                    {{-- Acciones --}}
                                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <button
                                            wire:click="edit({{ $admin->id }})"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg transition-all hover:scale-110"
                                            style="color: var(--color-primary-2);
                                                   background-color: var(--color-card-bg);
                                                   border: 1px solid var(--color-border-hover);"
                                            title="Editar administrador">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        <button
                                            wire:click="confirmDelete({{ $admin->id }})"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg transition-all hover:scale-110"
                                            style="color: white;
                                                   background-color: rgba(239, 68, 68, 0.9);
                                                   border: 1px solid rgba(239, 68, 68, 0.9);"
                                            title="Eliminar administrador">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-6">
                        {{ $admins->links() }}
                    </div>

                @else
                    {{-- Estado vacío --}}
                    <div class="text-center py-20 rounded-2xl border border-dashed"
                         style="background-color: var(--color-icon-bg);
                                border-color: var(--color-border-hover);">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
                             style="background-color: var(--color-card-bg);">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color: var(--color-secondary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold mb-2" style="color: var(--color-primary-2);">
                            No hay administradores registrados
                        </h3>
                        <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--color-secondary);">
                            Crea tu primer administrador para comenzar.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Modal Crear/Editar Administrador --}}
            @if($isModalOpen)
                <flux:modal wire:model="isModalOpen" :dismissible="false" class="w-[95vw] sm:w-[450px] max-w-[95vw]">
                    <div class="flex flex-col max-h-[85vh]">

                        {{-- Header --}}
                        <div class="relative px-6 py-5 flex items-center gap-4 overflow-hidden flex-shrink-0 rounded-t-xl border-b"
                             style="
                                    border-bottom: 1px solid var(--color-border-hover);">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold" style="color: var(--color-primary-2);">
                                    {{ $editingId ? 'Editar Administrador' : 'Nuevo Administrador' }}
                                </h3>
                                <p class="text-sm truncate" style="color: var(--color-secondary);">
                                    {{ $editingId ? 'Modifique los datos del administrador' : 'Complete la información requerida' }}
                                </p>
                            </div>
                        </div>

                        {{-- Contenido --}}
                        <div class="flex-1 overflow-y-auto p-6" style="background-color: var(--color-card-bg);">
                            <div class="space-y-4">
                                <flux:input wire:model="name" type="text" placeholder="Nombre completo" class="w-full" style="background-color: var(--color-icon-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                        />
                                <flux:input wire:model="email" type="email" placeholder="Correo electrónico" class="w-full" style="background-color: var(--color-icon-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                        />
                                <flux:input wire:model="password" type="password" placeholder="Contraseña" viewable class="w-full" style="background-color: var(--color-icon-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                        />
                                <flux:input wire:model="password_confirmation" type="password" placeholder="Confirmar contraseña" viewable class="w-full" style="background-color: var(--color-icon-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                        />
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t flex-shrink-0 rounded-b-xl"
                             style=" border-color: var(--color-border-hover);">
                            <flux:modal.close>
                                <button wire:click="$set('isModalOpen', false)"
                                        class="w-full sm:w-auto px-5 py-2.5 rounded-lg font-medium transition-all duration-200"
                                        style="background-color: var(--color-card-bg); color: var(--color-secondary); border: 1px solid var(--color-border-hover);"
                                        onmouseover="this.style.color='var(--color-primary-2)'"
                                        onmouseout="this.style.color='var(--color-secondary)'">
                                    Cancelar
                                </button>
                            </flux:modal.close>
                            <button wire:click="save"
                                    class="w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:-translate-y-0.5"
                                    style="background-color: var(--color-primary); color: var(--color-bg);"
                                    onmouseover="this.style.backgroundColor='var(--color-primary-2)'"
                                    onmouseout="this.style.backgroundColor='var(--color-primary)'">
                                {{ $editingId ? 'Guardar' : 'Crear' }}
                            </button>
                        </div>

                    </div>
                </flux:modal>
            @endif

            {{-- Modal Confirmación Eliminar --}}
            @if($isDeleteModalOpen && $adminToDelete)
                <flux:modal wire:model="isDeleteModalOpen" :dismissible="false" class="w-[95vw] sm:w-[450px] max-w-[95vw]">
                    <div class="flex flex-col max-h-[85vh]">
                        <div class="flex-1 overflow-y-auto p-6" style="background-color: var(--color-card-bg);">
                            <h3 class="text-lg font-bold" style="color: var(--color-primary-2);">
                                Confirmar Eliminación
                            </h3>
                            <p class="text-sm mt-1" style="color: var(--color-secondary);">
                                {{ $adminToDelete->name ?? '' }}
                            </p>
                        </div>

                        <div class="px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 border-t flex-shrink-0 rounded-b-xl"
                             style="background-color: var(--color-icon-bg); border-color: var(--color-border-hover);">
                            <button wire:click="$set('isDeleteModalOpen', false)"
                                    class="w-full sm:w-auto px-5 py-2.5 rounded-lg font-medium transition-all duration-200"
                                    style="background-color: var(--color-card-bg); color: var(--color-secondary); border: 1px solid var(--color-border-hover);"
                                    onmouseover="this.style.color='var(--color-primary-2)'"
                                    onmouseout="this.style.color='var(--color-secondary)'">
                                Cancelar
                            </button>
                            <button wire:click="delete"
                                    class="w-full sm:w-auto px-6 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:-translate-y-0.5"
                                    style="background-color: rgba(239, 68, 68, 0.9); color: white;"
                                    onmouseover="this.style.opacity='0.85'"
                                    onmouseout="this.style.opacity='1'">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </flux:modal>
            @endif

        </div>
    </x-settings.layout>
</section>