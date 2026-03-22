{{-- Estado vacío --}}
@if($adminOnlyDocuments->count() === 0)
    <div class="rounded-xl p-12 text-center">
        <div class="w-20 h-20 bg-[var(--color-card-bg)] rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-folder-open text-4xl text-[var(--color-primary)]"></i>
        </div>
        <h3 class="text-xl font-bold text-[var(--color-text)] mb-2">No hay documentos</h3>
        <p class="text-[var(--color-secondary)] text-sm">Cuando se te asignen documentos aparecerán aquí</p>
    </div>
@endif