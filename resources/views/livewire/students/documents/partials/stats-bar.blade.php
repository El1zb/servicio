{{-- Stats — mismo patrón que dashboard/index/partials/stats.blade.php --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-5">

    @php
        $statCards = [
            [
                'label'       => 'Por vencer',
                'value'       => $stats['por_vencer'],
                'description' => 'En los próximos 7 días',
                'dark'        => true,
                'viewBox'     => '0 0 192 192',
                'icon'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="12" d="M96 170c40.869 0 74-33.131 74-74 0-40.87-33.131-74-74-74-40.87 0-74 33.13-74 74 0 40.869 33.13 74 74 74Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="12" d="M95.8 46.315a49.686 49.686 0 1 1-35.129 84.823L95.8 96V46.315Z"/>',
                'icon_fill'   => false,
            ],
            [
                'label'       => 'Rechazados',
                'value'       => $stats['rechazados'],
                'description' => 'Requieren corrección',
                'dark'        => false,
                'viewBox'     => '0 0 200 200',
                'icon'        => '<path d="M100,15a85,85,0,1,0,85,85A84.93,84.93,0,0,0,100,15Zm0,150a65,65,0,1,1,65-65A64.87,64.87,0,0,1,100,165Zm30-72.5H70a10,10,0,0,0,0,20h60a10,10,0,0,0,0-20Z"/>',
                'icon_fill'   => true,
            ],
            [
                'label'       => 'En revisión',
                'value'       => $stats['en_revision'],
                'description' => 'Esperando respuesta',
                'dark'        => false,
                'viewBox'     => '0 0 24 24',
                'icon'        => '<path fill-rule="evenodd" clip-rule="evenodd" d="M4 12C4 7.58172 7.58172 4 12 4C12.5523 4 13 3.55228 13 3C13 2.44772 12.5523 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C14.7611 22 17.2625 20.8796 19.0711 19.0711C19.4616 18.6805 19.4616 18.0474 19.0711 17.6569C18.6805 17.2663 18.0474 17.2663 17.6569 17.6569C16.208 19.1057 14.2094 20 12 20C7.58172 20 4 16.4183 4 12ZM13 6C13 5.44772 12.5523 5 12 5C11.4477 5 11 5.44772 11 6V12C11 12.2652 11.1054 12.5196 11.2929 12.7071L14.2929 15.7071C14.6834 16.0976 15.3166 16.0976 15.7071 15.7071C16.0976 15.3166 16.0976 14.6834 15.7071 14.2929L13 11.5858V6ZM21.7483 15.1674C21.535 15.824 20.8298 16.1833 20.1732 15.97C19.5167 15.7566 19.1574 15.0514 19.3707 14.3949C19.584 13.7383 20.2892 13.379 20.9458 13.5923C21.6023 13.8057 21.9617 14.5108 21.7483 15.1674ZM21.0847 11.8267C21.7666 11.7187 22.2318 11.0784 22.1238 10.3966C22.0158 9.71471 21.3755 9.2495 20.6937 9.3575C20.0118 9.46549 19.5466 10.1058 19.6546 10.7877C19.7626 11.4695 20.4029 11.9347 21.0847 11.8267ZM20.2924 5.97522C20.6982 6.53373 20.5744 7.31544 20.0159 7.72122C19.4574 8.127 18.6757 8.00319 18.2699 7.44468C17.8641 6.88617 17.9879 6.10446 18.5464 5.69868C19.1049 5.2929 19.8867 5.41671 20.2924 5.97522ZM17.1997 4.54844C17.5131 3.93333 17.2685 3.18061 16.6534 2.86719C16.0383 2.55378 15.2856 2.79835 14.9722 3.41346C14.6588 4.02858 14.9033 4.78129 15.5185 5.09471C16.1336 5.40812 16.8863 5.16355 17.1997 4.54844Z"/>',
                'icon_fill'   => true,
            ],
            [
                'label'       => 'Aprobados',
                'value'       => $stats['aprobados'],
                'description' => 'Entrega completada',
                'dark'        => false,
                'viewBox'     => '0 0 24 24',
                'icon'        => '<path d="M4 12.6111L8.92308 17.5L20 6.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
                'icon_fill'   => false,
            ],
            [
                'label'       => 'Vencidos',
                'value'       => $stats['vencidos'],
                'description' => 'Sin entregar',
                'dark'        => false,
                'viewBox'     => '0 0 24 24',
                'icon'        => '<line x1="12" y1="8" x2="12" y2="12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="12" y1="16" x2="12" y2="16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><circle cx="12" cy="12" r="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>',
                'icon_fill'   => false,
            ],
        ];
    @endphp

    @foreach($statCards as $card)
        <div class="stat-card {{ $card['dark'] ? 'stat-card-dark' : '' }}">
            <div class="stat-card-top">
                <p class="stat-card-label">{{ $card['label'] }}</p>
                <div class="stat-card-icon">
                    <svg viewBox="{{ $card['viewBox'] }}" fill="{{ $card['icon_fill'] ? 'currentColor' : 'none' }}" stroke="currentColor">
                        {!! $card['icon'] !!}
                    </svg>
                </div>
            </div>

            <p class="stat-card-value">{{ $card['value'] }}</p>

            <p class="stat-card-description">
                <span class="stat-card-dot"></span>
                {{ $card['description'] }}
            </p>
        </div>
    @endforeach

</div>
