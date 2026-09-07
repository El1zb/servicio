<!-- Toast Notifications (Minimal · Blanco + ícono de color) -->
<div
    class="fixed top-4 right-4 z-50 space-y-2"
    x-data="{
        notifications: [],
        addNotification(type, message) {
            const id = Date.now();
            this.notifications.push({ id, type, message });
            setTimeout(() => this.removeNotification(id), 5000);
        },
        removeNotification(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }"
    @notify.window="addNotification($event.detail.type, $event.detail.message)"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="toast-card"
        >
            <!-- Icono -->
            <span class="toast-icon" x-show="notification.type === 'success'">
                <svg viewBox="0 0 48 48" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M44 24C44 35.0457 35.0457 44 24 44C12.9543 44 4 35.0457 4 24C4 12.9543 12.9543 4 24 4C35.0457 4 44 12.9543 44 24ZM13 24C13 23.4477 13.4477 23 14 23H23V14C23 13.4477 23.4477 13 24 13C24.5523 13 25 13.4477 25 14V23H34C34.5523 23 35 23.4477 35 24C35 24.5523 34.5523 25 34 25H25V34C25 34.5523 24.5523 35 24 35C23.4477 35 23 34.5523 23 34V25H14C13.4477 25 13 24.5523 13 24Z" fill="#16A34A"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M24 42C33.9411 42 42 33.9411 42 24C42 14.0589 33.9411 6 24 6C14.0589 6 6 14.0589 6 24C6 33.9411 14.0589 42 24 42ZM11 24C11 22.3431 12.3431 21 14 21H21V14C21 12.3431 22.3431 11 24 11C25.6569 11 27 12.3431 27 14V21H34C35.6569 21 37 22.3431 37 24C37 25.6569 35.6569 27 34 27H27V34C27 35.6569 25.6569 37 24 37C22.3431 37 21 35.6569 21 34V27H14C12.3431 27 11 25.6569 11 24ZM14 23C13.4477 23 13 23.4477 13 24C13 24.5523 13.4477 25 14 25H23L23 34C23 34.5523 23.4477 35 24 35C24.5523 35 25 34.5523 25 34L25 25H34C34.5523 25 35 24.5523 35 24C35 23.4477 34.5523 23 34 23H25V14C25 13.4477 24.5523 13 24 13C23.4477 13 23 13.4477 23 14V23H14ZM24 44C35.0457 44 44 35.0457 44 24C44 12.9543 35.0457 4 24 4C12.9543 4 4 12.9543 4 24C4 35.0457 12.9543 44 24 44Z" fill="#16A34A"/>
                </svg>
            </span>

            <span class="toast-icon" x-show="notification.type === 'error'">
                <svg viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM7 12C7 11.4477 7.44772 11 8 11H16C16.5523 11 17 11.4477 17 12C17 12.5523 16.5523 13 16 13H8C7.44772 13 7 12.5523 7 12Z" fill="#DC2626"/>
                </svg>
            </span>

            <span class="toast-icon" x-show="notification.type === 'warning'">
                <svg viewBox="0 0 48 48" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M44 24C44 35.0457 35.0457 44 24 44C12.9543 44 4 35.0457 4 24C4 12.9543 12.9543 4 24 4C35.0457 4 44 12.9543 44 24ZM21 12C21 10.3431 22.3431 9 24 9C25.6569 9 27 10.3431 27 12V28C27 29.6569 25.6569 31 24 31C22.3431 31 21 29.6569 21 28V12ZM24 33C22.3431 33 21 34.3431 21 36C21 37.6569 22.3431 39 24 39C25.6569 39 27 37.6569 27 36C27 34.3431 25.6569 33 24 33Z" fill="#D97706"/>
                </svg>
            </span>

            <span class="toast-icon" x-show="notification.type === 'info'">
                <svg viewBox="0 0 48 48" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M44 24C44 35.0457 35.0457 44 24 44C12.9543 44 4 35.0457 4 24C4 12.9543 12.9543 4 24 4C35.0457 4 44 12.9543 44 24ZM24 12C22.3431 12 21 13.3431 21 15C21 16.6569 22.3431 18 24 18C25.6569 18 27 16.6569 27 15C27 13.3431 25.6569 12 24 12ZM21 24C21 22.3431 22.3431 21 24 21C25.6569 21 27 22.3431 27 24V33C27 34.6569 25.6569 36 24 36C22.3431 36 21 34.6569 21 33V24Z" fill="#64748B"/>
                </svg>
            </span>

            <!-- Mensaje -->
            <p class="toast-message" x-text="notification.message"></p>

            <!-- Cerrar -->
            <button
                @click="removeNotification(notification.id)"
                class="toast-close"
                aria-label="Cerrar"
            >
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>
