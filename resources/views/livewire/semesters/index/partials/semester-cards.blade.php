{{-- Grid de Semestres --}}
<div class="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 p-4">
    @foreach($semesters as $semester)
        <div style="background-color: var(--index-card-bg); border: 1px solid var(--index-border); border-radius: 14px; overflow: hidden; transition: border-color 0.18s ease, box-shadow 0.18s ease; display: flex; flex-direction: column; height: 100%;"
             onmouseover="this.style.borderColor='var(--index-border)'; this.style.boxShadow='0 4px 24px rgba(0,0,0,0.10)'"
             onmouseout="this.style.borderColor='var(--index-border)'; this.style.boxShadow='none'">

            {{-- Cuerpo --}}
            <div style="padding: 20px 18px 16px; flex: 1; display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <p style="font-size: 9px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--index-text-secondary); margin-bottom: 4px; margin-top: 0;">
                        Semestre
                    </p>
                    <h3 style="font-size: 13.5px; font-weight: 500; color: var(--index-text-primary); line-height: 1.35; letter-spacing: -0.01em; margin: 0;">
                        {{ $semester->name }}
                    </h3>
                </div>
            </div>

            {{-- Footer --}}
            <div style="padding: 8px 10px; border-top: 1px solid var(--index-border); display: flex; align-items: center; justify-content: flex-end; gap: 2px; flex-shrink: 0;">

                {{-- Editar --}}
                <button wire:click="edit({{ $semester->id }})"
                        style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid transparent; background: transparent; color: var(--index-text-secondary); cursor: pointer; transition: color 0.15s, border-color 0.15s, background-color 0.15s;"
                        onmouseover="this.style.color='var(--index-text-primary)'; this.style.borderColor='var(--period-detail-btn-edit-hover)'"
                        onmouseout="this.style.color='var(--index-text-secondary)'; this.style.borderColor='transparent'">
                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>

                {{-- Separador --}}
                <span style="width: 1px; height: 14px; background-color: var(--index-border); display: inline-block; margin: 0 2px; flex-shrink: 0;"></span>

                {{-- Eliminar --}}
                <button wire:click="confirmDelete({{ $semester->id }})"
                        style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid transparent; background: transparent; color: var(--index-text-secondary); cursor: pointer; transition: color 0.15s, border-color 0.15s, background-color 0.15s;"
                        onmouseover="this.style.color='var(--index-status-rejected-icon)'; this.style.borderColor='var(--index-status-rejected-border)'"
                        onmouseout="this.style.color='var(--index-text-secondary)'; this.style.borderColor='transparent'">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>

            </div>
        </div>
    @endforeach
</div>

{{-- Paginación --}}
<div class="mt-6 px-4">
    {{ $semesters->links() }}
</div>