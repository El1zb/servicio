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
    <div class="bg-gradient-to-r
        {{ collect($dateKeys)->map(function($k) use ($documents) {
            $isExpired   = $k !== 'Sin fecha' && now()->gt(\Carbon\Carbon::parse($k)->endOfDay());
            $allApproved = collect($documents[$k] ?? [])->every(fn($d) => $d->status === 'revisado');
            return ['key' => $k, 'expired' => $isExpired, 'approved' => $allApproved];
        }) ? '' : '' }}
        rounded-xl overflow-hidden mb-1">

        @foreach($documents->sortKeysDesc() as $limitDate => $docs)
        @endforeach

        {{-- Header con flechas --}}
        <div class="relative">
            @foreach($documents as $limitDate => $docs)
                @php
                    $isExpired    = $limitDate !== 'Sin fecha' && now()->gt(\Carbon\Carbon::parse($limitDate)->endOfDay());
                    $totalDocs    = count($docs);
                    $uploadedDocs = collect($docs)->filter(fn($d) => $d->student_file_name)->count();
                    $allApproved  = collect($docs)->every(fn($d) => $d->status === 'revisado');

                    $statusText = '';
                    if ($allApproved)       $statusText = 'COMPLETADO';
                    elseif ($isExpired)     $statusText = 'VENCIDO';
                    elseif ($limitDate !== 'Sin fecha') {
                        $today = \Carbon\Carbon::today();
                        $limit = \Carbon\Carbon::parse($limitDate)->startOfDay();
                        if ($limit->eq($today))                       $statusText = 'HOY';
                        elseif ($limit->eq($today->copy()->addDay())) $statusText = 'MAÑANA';
                        elseif ($limit->gt($today->copy()->addDay())) $statusText = $today->diffInDays($limit) . ' DÍAS';
                    }
                @endphp

                <div x-show="currentDate === '{{ $limitDate }}'" x-cloak
                     class="@if($isExpired && !$allApproved) bg-[var(--index-card-header-bg)] @elseif($allApproved) bg-gradient-to-r from-[var(--period-detail-status-approved-bg)] to-[var(--index-card-header-approved-bg)] @else bg-gradient-to-r from-[var(--index-content-bg)] to-[var(--index-content-bg)] @endif
                            rounded-xl px-5 py-4">
                    <div class="flex items-center justify-between">

                        {{-- Flecha izquierda --}}
                        <button @click="prev()" :disabled="currentIndex === 0"
                                class="w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-200
                                       {{ $isExpired || $allApproved
                                           ? 'bg-[var(--period-detail-card-indicator-bg)] text-[var(--index-text-primary)]'
                                           : 'bg-white/15 text-white' }}
                                       hover:scale-105 active:scale-95
                                       disabled:opacity-25 disabled:cursor-not-allowed disabled:hover:scale-100">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>

                        {{-- Info central --}}
                        <div class="text-center flex-1 px-4">
                            <div class="flex items-center justify-center gap-2 mb-0.5">
                                <p class="{{ $isExpired || $allApproved ? 'text-[var(--index-text-secondary)]' : 'text-white/60' }} text-xs font-medium">
                                    Fecha límite
                                </p>
                                @if($statusText)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                        {{ $isExpired || $allApproved
                                            ? 'bg-[var(--period-detail-card-indicator-bg)] text-[var(--index-text-primary)]'
                                            : 'bg-white/20 text-white' }}">
                                        {{ $statusText }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="{{ $isExpired || $allApproved ? 'text-[var(--index-text-primary)]' : 'text-white' }} text-lg font-bold leading-tight">
                                {{ $limitDate !== 'Sin fecha'
                                    ? \Carbon\Carbon::parse($limitDate)->locale('es')->isoFormat('DD MMM YYYY')
                                    : 'Sin fecha' }}
                            </h3>
                            <p class="{{ $isExpired || $allApproved ? 'text-[var(--index-text-secondary)]' : 'text-white/50' }} text-xs mt-0.5">
                                {{ $uploadedDocs }}/{{ $totalDocs }} entregados
                                <span class="mx-1.5 opacity-40">·</span>
                                <span x-text="(currentIndex + 1) + ' de ' + total"></span>
                            </p>
                        </div>

                        {{-- Flecha derecha --}}
                        <button @click="next()" :disabled="currentIndex === total - 1"
                                class="w-9 h-9 flex items-center justify-center rounded-lg transition-all duration-200
                                       {{ $isExpired || $allApproved
                                           ? 'bg-[var(--period-detail-card-indicator-bg)] text-[var(--index-text-primary)]'
                                           : 'bg-white/15 text-white' }}
                                       hover:scale-105 active:scale-95
                                       disabled:opacity-25 disabled:cursor-not-allowed disabled:hover:scale-100">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Dots indicadores --}}
        <div class="flex items-center justify-center gap-1.5 py-3">
            @foreach($dateKeys as $i => $key)
                @php
                    $isExp  = $key !== 'Sin fecha' && now()->gt(\Carbon\Carbon::parse($key)->endOfDay());
                    $isAppr = collect($documents[$key] ?? [])->every(fn($d) => $d->status === 'revisado');
                @endphp
                <button @click="currentIndex = {{ $i }}"
                        class="transition-all duration-300 rounded-full
                               {{ $isAppr ? 'bg-green-500' : ($isExp ? 'bg-[var(--index-text-secondary)]' : 'bg-[var(--index-accent)]') }}"
                        :class="currentIndex === {{ $i }}
                            ? 'w-5 h-2 opacity-100'
                            : 'w-2 h-2 opacity-40 hover:opacity-70'">
                </button>
            @endforeach
        </div>
    </div>

    {{-- Documentos de la fecha activa --}}
    <div class="space-y-3">
        @forelse($documents as $limitDate => $docs)
            @php
                $isExpired = $limitDate !== 'Sin fecha' && now()->gt(\Carbon\Carbon::parse($limitDate)->endOfDay());
            @endphp
            <div x-show="currentDate === '{{ $limitDate }}'" x-cloak>
                <div class="space-y-3">
                    @foreach($docs as $document)
                        @include('livewire.students.documents.partials.document-item', [
                            'document'  => $document,
                            'isExpired' => $isExpired,
                            'student'   => $student,
                        ])
                    @endforeach
                </div>
            </div>
        @empty
            @include('livewire.students.documents.partials.empty-states')
        @endforelse
    </div>

</div>