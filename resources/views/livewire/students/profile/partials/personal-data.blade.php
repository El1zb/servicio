{{-- Datos Personales --}}
<div class="bg-[var(--color-card-bg)] rounded-xl 
            overflow-hidden transition-all duration-300">

    {{-- Header --}}
    <div class="px-6 py-4 border-b border-[var(--color-border-hover)]"
         style="background-color: var(--color-card-bg);">
        <h2 class="text-lg font-semibold text-[var(--color-primary-2)]!">
            Datos Personales
        </h2>
    </div>

    {{-- Body --}}
    <div class="p-6 space-y-6">

        {{-- Nombre completo --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <flux:input
                wire:model="name"
                label="Nombre(s) *"
                type="text" inputmode="text" required
                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras"
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')"
            />
            <flux:input
                wire:model="last_name_paterno"
                label="Apellido Paterno *"
                type="text" inputmode="text" required
                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras"
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')"
            />
            <flux:input
                wire:model="last_name_materno"
                label="Apellido Materno *"
                type="text" inputmode="text" required
                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras"
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')"
            />
        </div>

        {{-- Identificación oficial --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <flux:input
                wire:model="curp"
                label="CURP *"
                type="text" inputmode="text" required
                maxlength="18" minlength="18"
                pattern="[A-Z0-9]{18}" title="La CURP debe tener exactamente 18 caracteres."
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover); text-transform: uppercase;"
                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
            />
            <flux:input
                wire:model="rfc"
                label="RFC"
                type="text" inputmode="text"
                maxlength="13" minlength="12"
                pattern="[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}" title="RFC válido (12 o 13 caracteres, en mayúsculas)"
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover); text-transform: uppercase;"
                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9Ñ&]/g, '')"
            />
        </div>

        {{-- Contacto --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <flux:input
                wire:model="phone"
                label="Teléfono *"
                type="text" inputmode="numeric"
                placeholder="Ej. 0000000000" required
                maxlength="10" minlength="10"
                pattern="[0-9]{10}" title="El teléfono debe contener 10 dígitos numéricos"
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            />
            <flux:input
                wire:model="personal_email"
                label="Correo Personal *"
                type="email" inputmode="email"
                placeholder="Ej. personal@ejemplo.com" required
                title="Ingresa un correo válido"
                style="background-color: var(--color-card-bg); color: var(--color-primary-2); border: 1px solid var(--color-border-hover);"
                oninput="this.value = this.value.toLowerCase()"
            />
        </div>

    </div>
</div>