<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout>
        <div class="space-y-6">

            {{-- Header principal --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 rounded-xl shadow-sm p-6"
                 style="background-color: var(--student-document-bg);">
                <div>
                    <x-auth-header
                        title="Configuración del sitio"
                        description="Editar el contenido de la página de inicio."
                        :center="false"
                    />
                </div>
            </div>

            {{-- Formulario Livewire --}}
            <div class="space-y-6">

                {{-- ====== HEADER ====== --}}
                <div class="p-6 rounded-xl shadow-lg" style="background-color: var(--student-document-bg);">
                    <h3 class="font-bold mb-4" style="color: var(--student-document-text-primary);">Cabecera del sitio</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <flux:input wire:model="header_title" :label="__('Título')" type="text" required class="w-full"/>
                        <flux:input wire:model="header_subtitle" :label="__('Subtítulo')" type="text" required class="w-full"/>
                    </div>
                </div>

                {{-- ====== PRINCIPALES ENCABEZADOS ====== --}}
                <div class="p-6 rounded-xl shadow-lg" style="background-color: var(--student-document-bg);">
                    <h3 class="font-bold mb-4" style="color: var(--student-document-text-primary);">Contenido bienvenida</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <flux:input wire:model="main_encabezado" :label="__('Título Negro')" type="text" required class="w-full"/>
                        <flux:input wire:model="main_encabezado2" :label="__('Título Azul')" type="text" required class="w-full"/>
                        <flux:input wire:model="main_subencabezado" :label="__('Subtítulo')" type="text" required class="w-full"/>                        
                        <flux:input wire:model="indicador" :label="__('Indicador')" type="text" required class="w-full"/>
                    </div>
                </div>

                {{-- ====== REQUISITOS ====== --}}
                <div class="p-6 rounded-xl shadow-lg" style="background-color: var(--student-document-bg);">
                    <h3 class="font-bold mb-4" style="color: var(--student-document-text-primary);">Requisitos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:input wire:model="requisito_avance_academico" :label="__('Porcentaje mínimo de créditos')" type="text" required class="w-full"/>
                    </div>
                </div>

                {{-- ====== DURACIÓN Y CRÉDITOS ====== --}}
                <div class="p-6 rounded-xl shadow-lg" style="background-color: var(--student-document-bg);">
                    <h3 class="font-bold mb-4" style="color: var(--student-document-text-primary);">Duración y Créditos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <flux:input wire:model="horas_total" :label="__('Horas Totales')" type="text" required class="w-full"/>
                        <flux:input wire:model="creditos" :label="__('Créditos')" type="text" required class="w-full"/>
                        <flux:input wire:model="meses_maximo" :label="__('Meses Máximo')" type="text" required class="w-full"/>
                    </div>
                </div>

                {{-- ====== INSTITUCIÓN ====== --}}
                <div class="p-6 rounded-xl shadow-lg" style="background-color: var(--student-document-bg);">
                    <h3 class="font-bold mb-4" style="color: var(--student-document-text-primary);">Institución</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Nombre de la institución --}}
                        <flux:input wire:model="nombre_institucion" :label="__('Nombre de la Institución')" type="text" required class="w-full"/>

                        {{-- Abreviatura --}}
                        <flux:input wire:model="abreviatura" :label="__('Abreviatura')" type="text" required class="w-full"/>
                        
                    </div>

                    {{-- Logo en su propia línea, más pequeño y alineado a la izquierda --}}
                    <div class="mt-2">
                        <flux:field>
                            <flux:label>Logo de la Institución</flux:label>
                            <flux:input type="file" wire:model="logoFile" accept=".png,.jpg,.jpeg" class="w-full" />
                            <flux:error name="logoFile" />

                            {{-- Previsualización de la imagen --}}
                            @if ($logoFile)
                                <div class="mt-2">
                                    <img src="{{ $logoFile->temporaryUrl() }}" 
                                        alt="Logo" 
                                        class="w-20 h-20 object-cover rounded-lg shadow-sm border border-gray-200">
                                </div>
                            @elseif ($welcome && $welcome->logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $welcome->logo) }}" 
                                        alt="Logo" 
                                        class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                </div>
                            @endif
                        </flux:field>
                    </div>
                </div>



                {{-- ====== CONTACTO ====== --}}
                <div class="p-6 rounded-xl shadow-lg" style="background-color: var(--student-document-bg);">
                    <h3 class="font-bold mb-4" style="color: var(--student-document-text-primary);">Información de Contacto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <flux:input wire:model="email_contacto" :label="__('Correo Electrónico')" type="text" required class="w-full"/>
                        <flux:input wire:model="telefono_contacto" :label="__('Teléfono')" type="text" required class="w-full"/>
                        <flux:input wire:model="direccion_contacto" :label="__('Dirección')" type="text" required class="w-full"/>
                    </div>
                </div>

                {{-- Botón Guardar --}}
                <div class="mt-4 flex justify-end">
                    <button wire:click="update"
                            class="px-6 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow-lg text-white"
                            style="background: linear-gradient(135deg, var(--color-hero-accent-primary) 0%, var(--color-hero-accent-secondary) 100%);">
                        Actualizar
                    </button>
                </div>

            </div>

        </div>
    </x-settings.layout>
</section>
