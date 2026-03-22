{{-- Documentos Informativos (admin_only) --}}
@if($adminOnlyDocuments->count() > 0)
<div x-data="{ expanded: false }" class="mb-4">

    <button @click="expanded = !expanded"
            class="w-full flex items-center justify-between gap-2 px-1 mb-7 group focus:outline-none">
        <div class="flex items-center gap-2">
            <div class="w-1 h-4 rounded-full bg-[var(--color-indicator)]"></div>
            <p class="text-xs font-bold text-[var(--color-text)] uppercase tracking-wider">
                Información y Recursos
            </p>
            <span class="text-xs text-[var(--color-secondary)] opacity-80">· {{ $adminOnlyDocuments->count() }}</span>
        </div>
        @if($adminOnlyDocuments->count() > 1)
            <div class="flex items-center gap-1 text-xs text-[var(--color-secondary)] group-hover:text-[var(--color-text)] transition-colors">
                <span x-text="expanded ? 'VER MENOS' : 'VER TODOS'" class="font-bold"></span>
                <i class="fas fa-chevron-down text-[10px] transition-transform" :class="{ 'rotate-180': expanded }"></i>
            </div>
        @endif
    </button>

    <div class="space-y-2 ">
        {{-- Primero siempre visible --}}
        @include('livewire.students.documents.partials.admin-document-card', [
            'document' => $adminOnlyDocuments->first()
        ])

        {{-- Resto colapsable --}}
        @if($adminOnlyDocuments->count() > 1)
            <div x-show="expanded" x-collapse>
                <div class="space-y-2">
                    @foreach($adminOnlyDocuments->skip(1) as $document)
                        @include('livewire.students.documents.partials.admin-document-card', compact('document'))
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</div>
@endif