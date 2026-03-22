{{-- Cards de documentos navegables por fecha --}}
@php
    $dateKeys = array_keys($documents->toArray());
    // Ordenar de más reciente a más antiguo
    usort($dateKeys, function($a, $b) {
        if ($a === 'Sin fecha') return 1;
        if ($b === 'Sin fecha') return -1;
        return \Carbon\Carbon::parse($b)->gt(\Carbon\Carbon::parse($a)) ? 1 : -1;
    });
@endphp

<div x-data="{
        currentIndex: 0,
        dates: {{ json_encode($dateKeys) }},
        get currentDate() { return this.dates[this.currentIndex]; },
        get total() { return this.dates.length; },
        prev() { if (this.currentIndex > 0) this.currentIndex--; },
        next() { if (this.currentIndex < this.total - 1) this.currentIndex++; }
    }">

    {{-- Navegación integrada como header --}}
    <div class="rounded-xl overflow-hidden mb-1">

        <div class="relative">
            @foreach($documents as $limitDate => $docs)
                @php
                    $isExpired    = $limitDate !== 'Sin fecha' && now()->gt(\Carbon\Carbon::parse($limitDate)->endOfDay());
                    $totalDocs    = count($docs);
                    $uploadedDocs = collect($docs)->filter(fn($d) => $d->student_file_name)->count();
                    $allApproved  = collect($docs)->every(fn($d) => $d->status === 'revisado');

                    $statusText = '';
                    if ($allApproved)           $statusText = 'COMPLETADO';
                    elseif ($isExpired)         $statusText = 'VENCIDO';
                    elseif ($limitDate !== 'Sin fecha') {
                        $today = \Carbon\Carbon::today();
                        $limit = \Carbon\Carbon::parse($limitDate)->startOfDay();
                        if ($limit->eq($today))                       $statusText = 'HOY';
                        elseif ($limit->eq($today->copy()->addDay())) $statusText = 'MAÑANA';
                        elseif ($limit->gt($today->copy()->addDay())) $statusText = $today->diffInDays($limit) . ' DÍAS';
                    }
                @endphp

                {{-- wire:key #1: para que Livewire identifique cada card de fecha --}}
                <div wire:key="date-header-{{ $limitDate }}">
                    <div x-show="currentDate === '{{ $limitDate }}'" x-cloak
                        class="@if($isExpired && !$allApproved) bg-[var(--color-card-bg)] @elseif($allApproved) bg-[var(--color-card-bg)] @else bg-[var(--color-card-bg)] @endif
                                rounded-xl px-6 pt-5 pb-4">

                        {{-- FECHA LÍMITE label --}}
                        <p class="text-center text-[var(--color-secondary)] text-[10px] font-semibold uppercase tracking-[0.18em] mb-2">
                            Fecha límite
                        </p>

                        {{-- Fila: flecha | fecha | flecha --}}
                        <div class="flex items-center justify-center gap-4">

                            {{-- Flecha izquierda --}}
                            <button @click="prev()" :disabled="currentIndex === 0"
                                    class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-full
                                        bg-[var(--color-bg-icon)] text-[var(--color-primary)]
                                        transition-all duration-200 hover:scale-105 active:scale-95
                                        disabled:opacity-20 disabled:cursor-not-allowed disabled:hover:scale-100">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>

                            {{-- Fecha --}}
                            <h3 class="text-[var(--index-text-primary)] text-[1.75rem] font-bold leading-none tracking-tight">
                                {{ $limitDate !== 'Sin fecha'
                                    ? \Carbon\Carbon::parse($limitDate)->locale('es')->isoFormat('DD MMM YYYY')
                                    : 'Sin fecha' }}
                            </h3>

                            {{-- Flecha derecha --}}
                            <button @click="next()" :disabled="currentIndex === total - 1"
                                    class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-full
                                        bg-[var(--color-bg-icon)] text-[var(--color-primary)]
                                        transition-all duration-200 hover:scale-105 active:scale-95
                                        disabled:opacity-20 disabled:cursor-not-allowed disabled:hover:scale-100">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>

                        </div>

                        {{-- Estado: VENCIDO / COMPLETADO / etc --}}
                        @if($statusText)
                            <p class="text-center mt-1.5 text-xs font-bold uppercase tracking-widest
                                @if($isExpired && !$allApproved) text-[var(--color-primary)]
                                @elseif($allApproved)            text-[var(--color-primary)]
                                @else                            text-[var(--color-primary)]
                                @endif">
                                {{ $statusText }}
                            </p>
                        @endif

                        {{-- Contadores --}}
                        <p class="text-center text-[var(--color-secondary)] text-[10px] font-semibold uppercase tracking-wider mt-3 ">
                            {{ $uploadedDocs }}/{{ $totalDocs }} entregados
                        </p>

                        {{-- Dots --}}
                        <div class="flex items-center justify-center gap-1.5 mt-3">
                            @foreach($dateKeys as $i => $key)
                                @php
                                    $isExp  = $key !== 'Sin fecha' && now()->gt(\Carbon\Carbon::parse($key)->endOfDay());
                                    $isAppr = collect($documents[$key] ?? [])->every(fn($d) => $d->status === 'revisado');
                                @endphp
                                <button @click="currentIndex = {{ $i }}"
                                        class="transition-all duration-300 rounded-full
                                            {{ $isAppr ? 'bg-[var(--color-indicator)]' : ($isExp ? 'bg-[var(--color-indicator)]' : 'bg-[var(--color-primary)]') }}"
                                        :class="currentIndex === {{ $i }}
                                            ? 'w-4 h-1.5 opacity-90'
                                            : 'w-1.5 h-1.5 opacity-30 hover:opacity-60'">
                                </button>
                            @endforeach
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{-- Documentos de la fecha activa --}}
    <div class="space-y-3 pt-3">
        @forelse($documents as $limitDate => $docs)
            @php
                $isExpired = $limitDate !== 'Sin fecha' && now()->gt(\Carbon\Carbon::parse($limitDate)->endOfDay());
            @endphp
            <div x-show="currentDate === '{{ $limitDate }}'" x-cloak>
                <div class="space-y-4">
                    @foreach($docs as $document)
                        {{-- wire:key #2: para que Livewire actualice cada documento individualmente --}}
                        <div wire:key="doc-{{ $document->id }}">
                            @include('livewire.students.documents.partials.document-item', [
                                'document'  => $document,
                                'isExpired' => $isExpired,
                                'student'   => $student,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            @include('livewire.students.documents.partials.empty-states')
        @endforelse
    </div>

</div>