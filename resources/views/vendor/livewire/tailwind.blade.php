@php
if (! isset($scrollTo)) {
    $scrollTo = false;
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">

            {{-- Móvil --}}
            <div class="flex justify-between flex-1 sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-full cursor-default"
                              style="background-color: var(--color-card-bg);
                                     color: var(--color-secondary);
                                     opacity: 0.5;">
                            Anterior
                        </span>
                    @else
                        <button type="button"
                                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                wire:loading.attr="disabled"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-full transition"
                                style="background-color: var(--color-card-bg);
                                       color: var(--color-primary-2);"
                                onmouseover="this.style.backgroundColor='var(--color-primary-2)'; this.style.color='var(--color-bg)';"
                                onmouseout="this.style.backgroundColor='var(--color-card-bg)'; this.style.color='var(--color-primary-2)';">
                            Anterior
                        </button>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <button type="button"
                                wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                wire:loading.attr="disabled"
                                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium rounded-full transition"
                                style="background-color: var(--color-card-bg);
                                       color: var(--color-primary-2);"
                                onmouseover="this.style.backgroundColor='var(--color-primary-2)'; this.style.color='var(--color-bg)';"
                                onmouseout="this.style.backgroundColor='var(--color-card-bg)'; this.style.color='var(--color-primary-2)';">
                            Siguiente
                        </button>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium rounded-full cursor-default"
                              style="background-color: var(--color-card-bg);
                                     color: var(--color-secondary);
                                     opacity: 0.5;">
                            Siguiente
                        </span>
                    @endif
                </span>
            </div>

            {{-- Escritorio --}}
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
                 x-data="{ pageInput: {{ $paginator->currentPage() }}, lastPage: {{ $paginator->lastPage() }} }" wire:key="paginator-desktop-{{ $paginator->currentPage() }}">

                {{-- Texto "X - Y / Z" --}}
                <p class="text-sm" style="color: var(--period-detail-text-secondary);">
                    <span class="font-medium" style="color: var(--period-detail-text-primary);">{{ $paginator->firstItem() }}</span> 
                    -
                    <span class="font-medium" style="color: var(--period-detail-text-primary);">{{ $paginator->lastItem() }}</span>
                    de
                    <span class="font-medium" style="color: var(--period-detail-text-primary);">{{ $paginator->total() }}</span>
                    resultados
                </p>

                {{-- Controles --}}
                <div class="flex items-center gap-2">

                    {{-- Anterior --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium rounded-lg cursor-default"
                                  style="background-color: var(--period-detail-card-bg);
                                         color: var(--period-detail-text-secondary);
                                         border: 1px solid var(--period-detail-border);
                                         opacity: 0.5;"
                                  aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <button type="button"
                                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }} pageInput = {{ $paginator->currentPage() - 1 }}"
                                dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium rounded-lg transition"
                                style="background-color: var(--period-detail-card-bg);
                                       color: var(--period-detail-text-primary);
                                       border: 1px solid var(--period-detail-border);"
                                onmouseover="this.style.backgroundColor='var(--period-detail-brand-primary)'; this.style.color='var(--period-detail-text-icon)';"
                                onmouseout="this.style.backgroundColor='var(--period-detail-card-bg)'; this.style.color='var(--period-detail-text-primary)';"
                                aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif

                    {{-- Input [ X ] de Y --}}
                    <div class="flex items-center gap-1.5 text-sm" style="color: var(--period-detail-text-secondary);">
                        <input
                            type="text"
                            inputmode="numeric"
                            x-model="pageInput"
                            class="w-14 px-2 py-1.5 text-sm text-center rounded-lg transition focus:outline-none focus:ring-2"
                            style="background-color: var(--period-detail-card-bg);
                                   color: var(--period-detail-text-primary);
                                   border: 1px solid var(--period-detail-border);"
                            title="Escribe un número y presiona Enter o pierde el foco"
                            x-on:keydown="
                                if (!/[0-9]|Backspace|Delete|ArrowLeft|ArrowRight|Tab|Enter/.test($event.key)) {
                                    $event.preventDefault();
                                }
                            "
                            x-on:keydown.enter="
                                let p = parseInt(pageInput);
                                if (p >= 1 && p <= {{ $paginator->lastPage() }}) {
                                    $wire.gotoPage(p, '{{ $paginator->getPageName() }}');
                                    {{ $scrollIntoViewJsSnippet }}
                                } else {
                                    pageInput = {{ $paginator->currentPage() }};
                                }
                            "
                            x-on:blur="
                                let p = parseInt(pageInput);
                                if (p >= 1 && p <= {{ $paginator->lastPage() }}) {
                                    $wire.gotoPage(p, '{{ $paginator->getPageName() }}');
                                    {{ $scrollIntoViewJsSnippet }}
                                } else {
                                    pageInput = {{ $paginator->currentPage() }};
                                }
                            "
                        />
                        <span>de</span>
                        <span class="font-semibold" style="color: var(--period-detail-text-primary);">{{ $paginator->lastPage() }}</span>
                    </div>

                    {{-- Siguiente --}}
                    @if ($paginator->hasMorePages())
                        <button type="button"
                                wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }} pageInput = {{ $paginator->currentPage() + 1 }}"
                                dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium rounded-lg transition"
                                style="background-color: var(--period-detail-card-bg);
                                       color: var(--period-detail-text-primary);
                                       border: 1px solid var(--period-detail-border);"
                                onmouseover="this.style.backgroundColor='var(--period-detail-brand-primary)'; this.style.color='var(--period-detail-text-icon)';"
                                onmouseout="this.style.backgroundColor='var(--period-detail-card-bg)'; this.style.color='var(--period-detail-text-primary)';"
                                aria-label="{{ __('pagination.next') }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @else
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium rounded-lg cursor-default"
                                  style="background-color: var(--period-detail-card-bg);
                                         color: var(--period-detail-text-secondary);
                                         border: 1px solid var(--period-detail-border);
                                         opacity: 0.5;"
                                  aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif

                </div>
            </div>
        </nav>
    @endif
</div>