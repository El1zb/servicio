<!-- Toast Notifications (Minimal · Elegant Colors) -->
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

            :class="{
                /* Éxito · Verde esmeralda elegante */
                'bg-emerald-50 text-emerald-900 border-emerald-200':
                    notification.type === 'success',

                /* Error · Vino oscuro (menos agresivo que rojo puro) */
                'bg-rose-50 text-rose-900 border-rose-200':
                    notification.type === 'error',

                /* Warning · Ámbar suave, no amarillo chillón */
                'bg-amber-50 text-amber-900 border-amber-200':
                    notification.type === 'warning',

                /* Info · Azul grisáceo, más enterprise */
                'bg-sky-50 text-sky-900 border-sky-200':
                    notification.type === 'info'
            }"

            class="flex items-center gap-3
                   px-5 py-3
                   rounded-lg
                   border
                   shadow-sm
                   max-w-md"
        >
            <!-- Icon -->
            <span
                x-show="notification.type === 'success'"
                class="flex items-center justify-center
                    w-5 h-5
                    rounded-full
                    bg-emerald-600
                    text-white
                    text-[11px]
                    font-semibold
                    leading-none"
            >
                ✓
            </span>

            <span
                x-show="notification.type === 'error'"
                class="flex items-center justify-center
                    w-5 h-5
                    rounded-full
                    bg-rose-600
                    text-white
                    text-[11px]
                    font-semibold
                    leading-none"
            >
                ✕
            </span>

            <span
                x-show="notification.type === 'warning'"
                class="flex items-center justify-center
                    w-5 h-5
                    rounded-full
                    bg-amber-500
                    text-white
                    text-[11px]
                    font-semibold
                    leading-none"
            >
                !
            </span>

            <span
                x-show="notification.type === 'info'"
                class="flex items-center justify-center
                    w-5 h-5
                    rounded-full
                    bg-sky-600
                    text-white
                    text-[11px]
                    font-semibold
                    leading-none"
            >
                i
            </span>


            <!-- Message -->
            <p class="text-sm flex-1 leading-snug" x-text="notification.message"></p>

            <!-- Close -->
            <button
                @click="removeNotification(notification.id)"
                class="opacity-40 hover:opacity-100 transition-opacity"
                aria-label="Cerrar"
            >
                ✕
            </button>
        </div>
    </template>
</div>
