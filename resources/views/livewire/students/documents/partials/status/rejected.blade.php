<div class="flex items-center justify-center min-h-[80vh] px-4">
    <div class="max-w-sm w-full text-center">

        <svg width="56" height="56" viewBox="0 0 200 200" class="mx-auto mb-6" style="color: var(--color-primary-2);">
            <path fill="currentColor" d="M100,15a85,85,0,1,0,85,85A84.93,84.93,0,0,0,100,15Zm0,150a65,65,0,1,1,65-65A64.87,64.87,0,0,1,100,165Zm30-72.5H70a10,10,0,0,0,0,20h60a10,10,0,0,0,0-20Z"/>
        </svg>

        <h2 class="text-2xl font-bold mb-3" style="color: var(--color-primary-2);">Perfil rechazado</h2>
        <p class="text-sm leading-relaxed mb-8" style="color: var(--color-secondary);">
            Por favor, actualiza la información necesaria y vuelve a enviarlo para su revisión.
            @if($student?->rejection_reason)
                <span style="color: #DC2626;"><strong>Motivo:</strong> {{ $student->rejection_reason }}</span>
            @endif
        </p>

        <button type="button" wire:click="openProfileModal" class="btn-primary">
            Corregir perfil
        </button>
    </div>
</div>
