{{-- Campanita de notificaciones (solo estudiante). Modal con difuminado
     detrás (.notif-backdrop): tarjeta flotante bajo la topbar, más chica en
     mobile (ver sizing en sidebar.css). Cierra al hacer click fuera o Esc.

     Sin wire:poll: el contador se refresca al navegar y al vuelo cuando
     llega un push con la pestaña abierta (evento "notification-received",
     ver app.js + public/sw.js) — nada de peticiones periódicas. --}}
<div class="notif-bell-wrap"
     x-data="{
        pushStatus: 'off',
        async initPush() { this.pushStatus = await window.pushSubscriptionStatus(); },
        async togglePush() {
            // 'unsupported': navegador sin Push API en pestaña normal
            // (Safari/Chrome en iOS fuera de la app instalada — ver
            // pushSubscriptionStatus() en app.js). En vez de intentar y
            // fallar con un error técnico, explica qué hacer como toast
            // (mismo componente que el resto de avisos de la app).
            // navigator.standalone solo existe en iOS.
            if (this.pushStatus === 'unsupported') {
                this.$dispatch('notify', {
                    type: 'info',
                    message: typeof navigator.standalone !== 'undefined'
                        ? 'Instala la app en tu pantalla de inicio para activar las notificaciones.'
                        : 'Las notificaciones no están disponibles en este navegador.',
                });
                return;
            }
            if (this.pushStatus === 'on') {
                if (await window.disablePushNotifications()) this.pushStatus = 'off';
            } else {
                if (await window.enablePushNotifications()) this.pushStatus = 'on';
            }
        },
     }"
     x-init="initPush()"
     x-effect="document.body.classList.toggle('notif-panel-open', $wire.open)"
     @click.outside="$wire.open = false"
     @keydown.escape.window="$wire.open = false">
    <button type="button" class="notif-bell-btn" wire:click="toggle" aria-label="Notificaciones">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.31317 12.463C6.20006 9.29213 8.60976 6.6252 11.701 6.5C14.7923 6.6252 17.202 9.29213 17.0889 12.463C17.0889 13.78 18.4841 15.063 18.525 16.383C18.525 16.4017 18.525 16.4203 18.525 16.439C18.5552 17.2847 17.9124 17.9959 17.0879 18.029H13.9757C13.9786 18.677 13.7404 19.3018 13.3098 19.776C12.8957 20.2372 12.3123 20.4996 11.701 20.4996C11.0897 20.4996 10.5064 20.2372 10.0923 19.776C9.66161 19.3018 9.42346 18.677 9.42635 18.029H6.31317C5.48869 17.9959 4.84583 17.2847 4.87602 16.439C4.87602 16.4203 4.87602 16.4017 4.87602 16.383C4.91795 15.067 6.31317 13.781 6.31317 12.463Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M9.42633 17.279C9.01212 17.279 8.67633 17.6148 8.67633 18.029C8.67633 18.4432 9.01212 18.779 9.42633 18.779V17.279ZM13.9757 18.779C14.3899 18.779 14.7257 18.4432 14.7257 18.029C14.7257 17.6148 14.3899 17.279 13.9757 17.279V18.779ZM12.676 5.25C13.0902 5.25 13.426 4.91421 13.426 4.5C13.426 4.08579 13.0902 3.75 12.676 3.75V5.25ZM10.726 3.75C10.3118 3.75 9.97601 4.08579 9.97601 4.5C9.97601 4.91421 10.3118 5.25 10.726 5.25V3.75ZM9.42633 18.779H13.9757V17.279H9.42633V18.779ZM12.676 3.75H10.726V5.25H12.676V3.75Z" fill="currentColor"/>
        </svg>

        @if($unreadCount > 0)
            <span class="notif-bell-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
        @endif
    </button>

    @if($open)
        <div class="notif-backdrop" wire:click="toggle"></div>

        <div class="notif-dropdown-panel">
            <div class="notif-panel-header">
                <span class="notif-panel-title">Notificaciones</span>
                <button type="button" class="notif-switch" :class="{ 'is-on': pushStatus === 'on' }"
                        @click="togglePush()" aria-label="Activar o desactivar notificaciones push">
                    <span class="notif-switch-thumb"></span>
                </button>
            </div>

            <div class="notif-panel-list">
                @forelse($notifications as $n)
                    {{-- Solo informativa: al hacer click únicamente se marca como
                         leída (apaga el punto), sin navegar a ningún lado. --}}
                    <div wire:click="markAsRead('{{ $n->id }}')"
                       class="notif-item {{ $n->read_at ? '' : 'is-unread' }}">
                        <div class="notif-item-title-row">
                            <span class="notif-item-title">{{ $n->data['title'] ?? '' }}</span>
                            <span class="notif-item-time">{{ $n->created_at->diffForHumans() }}</span>
                        </div>
                        <span class="notif-item-body">{{ $n->data['body'] ?? '' }}</span>
                        @unless($n->read_at)
                            <span class="notif-item-dot"></span>
                        @endunless
                    </div>
                @empty
                    <div class="notif-empty">Sin notificaciones por ahora.</div>
                @endforelse
            </div>
        </div>
    @endif
</div>
