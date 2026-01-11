<!-- Toast Notifications Container -->
<div class="fixed top-4 right-4 z-50 space-y-3" 
     x-data="{ 
         notifications: [],
         addNotification(type, message) {
             const id = Date.now();
             this.notifications.push({ id, type, message });
             setTimeout(() => {
                 this.removeNotification(id);
             }, 5000);
         },
         removeNotification(id) {
             this.notifications = this.notifications.filter(n => n.id !== id);
         }
     }"
     @notify.window="addNotification($event.detail.type, $event.detail.message)"
     id="toast-container">

    <!-- Notificaciones dinámicas -->
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="true"
             x-transition:enter="transform transition ease-out duration-300"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transform transition ease-in duration-200"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             :class="{
                 'border-[var(--toast-notification-success)]': notification.type === 'success',
                 'border-[var(--toast-notification-error)]': notification.type === 'error',
                 'border-[var(--toast-notification-info)]': notification.type === 'info',
                 'border-[var(--toast-notification-warning)]': notification.type === 'warning'
             }"
             class="bg-[var(--toast-notification-bg)] rounded-xl shadow-2xl border-l-4 p-4 max-w-md flex items-start gap-3 backdrop-blur-sm">
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-[var(--toast-notification-text-primary)] text-sm mb-1"
                    x-text="notification.type === 'success' ? '¡Éxito!' : (notification.type === 'error' ? 'Error' : (notification.type === 'warning' ? 'Advertencia' : 'Información'))"></h4>
                <p class="text-[var(--toast-notification-text-secondary)] text-sm" x-text="notification.message"></p>
            </div>
            <button @click="removeNotification(notification.id)" 
                    class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </template>
</div>