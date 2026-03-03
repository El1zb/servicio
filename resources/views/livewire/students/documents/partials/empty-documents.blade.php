{{-- Estado vacío --}}
@if($adminOnlyDocuments->count() === 0)
    <div class="rounded-xl shadow-sm p-12 text-center">
        <div class="w-20 h-20 bg-[var(--index-content-bg)] rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-folder-open text-4xl text-[var(--index-text-primary)]"></i>
        </div>
        <h3 class="text-xl font-bold text-[var(--index-text-primary)] mb-2">No hay documentos</h3>
        <p class="text-[var(--index-text-secondary)] text-sm">Cuando se te asignen documentos aparecerán aquí</p>
    </div>
@endif