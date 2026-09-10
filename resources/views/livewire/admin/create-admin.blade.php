<section class="w-full">
    @push('topbar-actions')
        <button type="button" onclick="topbarAction('create')" class="btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            Nuevo Administrador
        </button>
    @endpush

    <x-settings.layout heading="Administradores" subheading="Gestión completa de cuentas administrativas.">
        <x-slot:mobileAction>
            {{-- Mobile: mismo botón "Nuevo Admin" del topbar de escritorio,
                 pero solo con "+", junto al nombre/descripción de la sección. --}}
            <button type="button" wire:click="create" class="btn-primary !w-10 !h-10 !p-0" aria-label="Nuevo Administrador">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            </button>
        </x-slot:mobileAction>

        <div class="space-y-6 max-w-3xl">

            {{-- Lista de Administradores --}}
            @if($admins->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach($admins as $admin)
                        {{-- Toda la card abre "editar" de un clic (el botón detiene la
                             propagación para no disparar los dos), igual que en
                             campus/career/semester-cards.blade.php. --}}
                        <div wire:click="edit({{ $admin->id }})" class="period-card period-card--clickable group">
                            <div class="stat-card-top">
                                <div class="min-w-0 flex-1 flex flex-col gap-1">
                                    <p class="stat-card-label truncate">{{ $admin->name }}</p>
                                    <p class="text-xs truncate" style="color: var(--color-secondary); margin:0;">{{ $admin->email }}</p>
                                </div>

                                <div class="stat-card-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M20 21a8 8 0 10-16 0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="7" r="4" stroke-width="2"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="period-card-footer">
                                <p class="stat-card-description" style="margin:0;">
                                    <span class="stat-card-dot"></span>
                                    Creado el {{ $admin->created_at->format('d/m/Y') }}
                                </p>

                                {{-- Editar por botón: solo escritorio (mobile ya abre
                                     la edición con un toque en cualquier parte de la card). --}}
                                <button wire:click.stop="edit({{ $admin->id }})"
                                        class="hidden lg:flex w-7 h-7 items-center justify-center rounded-full flex-shrink-0
                                               text-[var(--color-icon)] bg-transparent cursor-pointer
                                               opacity-0 group-hover:opacity-100
                                               transition-all duration-150
                                               hover:text-[var(--color-icon-hover)] hover:bg-[var(--sidebar-color-hover)]">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                        <path d="M20.1497 7.93997L8.27971 19.81C7.21971 20.88 4.04971 21.3699 3.27971 20.6599C2.50971 19.9499 3.06969 16.78 4.12969 15.71L15.9997 3.84C16.5478 3.31801 17.2783 3.03097 18.0351 3.04019C18.7919 3.04942 19.5151 3.35418 20.0503 3.88938C20.5855 4.42457 20.8903 5.14781 20.8995 5.90463C20.9088 6.66146 20.6217 7.39189 20.0997 7.93997H20.1497Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M21 21H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
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
                <div class="text-center py-20">
                    <svg class="w-14 h-14 mx-auto mb-4" style="color: var(--color-secondary);" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M20 21a8 8 0 10-16 0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="7" r="4" stroke-width="2"/>
                    </svg>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--color-primary-2);">
                        {{ $search ? 'Sin resultados' : 'No hay administradores registrados' }}
                    </h3>
                    <p class="mb-6 max-w-sm mx-auto text-sm" style="color: var(--color-secondary);">
                        {{ $search ? 'No se encontraron administradores con esos términos.' : 'Crea tu primer administrador para comenzar.' }}
                    </p>
                    @if(!$search)
                        <button type="button" wire:click="create" class="btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                            Nuevo Administrador
                        </button>
                    @endif
                </div>
            @endif

            {{-- =================== MODAL CREAR / EDITAR ADMINISTRADOR =================== --}}
            @if($isModalOpen)
                <flux:modal
                    wire:model="isModalOpen"
                    :dismissible="false"
                    :closable="false"
                    class="w-[95vw] sm:w-[90vw] md:w-[480px] max-w-[95vw]"
                    style="border-color: var(--color-border);">

                    <div class="flex flex-col" style="max-height: 90dvh;">

                        {{-- ── Header ── --}}
                        <div class="flex items-center justify-between gap-4 px-6 py-5 flex-shrink-0"
                            style="border-bottom: 1px solid var(--color-border);">
                            <div class="min-w-0">
                                <h2 class="text-lg font-semibold leading-tight" style="color: var(--color-primary-2);">
                                    {{ $editingId ? 'Editar Administrador' : 'Nuevo Administrador' }}
                                </h2>
                                <p class="text-sm mt-0.5" style="color: var(--color-secondary);">
                                    {{ $editingId ? 'Actualiza la información del administrador' : 'Se creará una nueva cuenta administrativa' }}
                                </p>
                            </div>
                        </div>

                        {{-- ── Body ── --}}
                        <div class="flex-1 overflow-y-auto px-6 py-5" style="background-color: var(--color-modal-bg);">
                            <div class="space-y-5">

                                <div class="app-field">
                                    <label class="app-field-label">Nombre completo <span style="color: #DC2626;">*</span></label>
                                    <input type="text" wire:model.live.debounce.500ms="name" placeholder="Ej: Juan Pérez" class="app-input w-full">
                                    @error('name') <p class="app-field-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="app-field">
                                    <label class="app-field-label">Correo electrónico <span style="color: #DC2626;">*</span></label>
                                    <input type="email" wire:model.live.debounce.500ms="email" placeholder="Ej: admin@itsco.edu.mx" class="app-input w-full">
                                    @error('email') <p class="app-field-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="app-field">
                                    <label class="app-field-label">
                                        Contraseña
                                        @if(! $editingId) <span style="color: #DC2626;">*</span> @endif
                                    </label>
                                    <input type="password" wire:model.live.debounce.500ms="password" placeholder="{{ $editingId ? 'Dejar en blanco para no cambiarla' : '' }}" autocomplete="new-password" class="app-input w-full">
                                    @error('password') <p class="app-field-error">{{ $message }}</p> @enderror
                                </div>

                                <div class="app-field">
                                    <label class="app-field-label">
                                        Confirmar contraseña
                                        @if(! $editingId) <span style="color: #DC2626;">*</span> @endif
                                    </label>
                                    <input type="password" wire:model.live.debounce.500ms="password_confirmation" autocomplete="new-password" class="app-input w-full">
                                    @error('password_confirmation') <p class="app-field-error">{{ $message }}</p> @enderror
                                </div>

                            </div>
                        </div>

                        {{-- ── Footer ── --}}
                        <div class="flex items-center justify-between gap-2 px-6 py-4 flex-shrink-0"
                            style="border-top: 1px solid var(--color-border);">

                            <div>
                                @if($editingId)
                                    <button type="button" wire:click="confirmDelete({{ $editingId }})" class="btn-danger">
                                        Eliminar
                                    </button>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <button wire:click="$set('isModalOpen', false)"
                                        class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                                    Cancelar
                                </button>

                                <button wire:click="save"
                                        wire:loading.attr="disabled"
                                        class="btn-primary disabled:opacity-60 disabled:cursor-not-allowed">
                                    <span wire:loading.remove wire:target="save">
                                        {{ $editingId ? 'Guardar' : 'Crear administrador' }}
                                    </span>
                                    <span wire:loading wire:target="save">Procesando...</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </flux:modal>
            @endif

            {{-- =================== MODAL ELIMINAR ADMINISTRADOR =================== --}}
            @if($isDeleteModalOpen && $adminToDelete)
                <flux:modal
                    wire:model="isDeleteModalOpen"
                    :dismissible="false"
                    class="w-[95vw] sm:w-[420px] max-w-[95vw]"
                    style="border-color: var(--color-border);">

                    <div class="flex flex-col">

                        {{-- ── Body ── --}}
                        <div class="px-6 py-6" style="background-color: var(--color-modal-bg);">
                            <p class="text-sm font-semibold mb-1" style="color: var(--color-primary-2);">
                                ¿Eliminar este administrador?
                            </p>
                            <p class="text-sm" style="color: var(--color-secondary);">
                                {{ $adminToDelete->name ?? '' }}
                                — esta acción es permanente y no se puede deshacer.
                            </p>
                        </div>

                        {{-- ── Footer ── --}}
                        <div class="flex items-center justify-end gap-2 px-6 py-4 flex-shrink-0"
                            style="border-top: 1px solid var(--color-border);">

                            <button wire:click="$set('isDeleteModalOpen', false)"
                                    class="px-4 py-2 rounded-full text-sm transition-colors duration-150 text-[var(--color-secondary)] hover:text-[var(--color-primary-2)] hover:bg-[var(--sidebar-color-hover)]">
                                Cancelar
                            </button>

                            <button wire:click="delete" class="btn-danger">
                                Eliminar
                            </button>
                        </div>

                    </div>
                </flux:modal>
            @endif

        </div>
    </x-settings.layout>
</section>