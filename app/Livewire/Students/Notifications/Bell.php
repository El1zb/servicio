<?php

namespace App\Livewire\Students\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Campanita de notificaciones del estudiante. Se monta en el layout (ver
 * sidebar.blade.php), no en cada página, para estar visible siempre.
 *
 * Sin wire:poll: el contador se refresca al navegar (el componente se
 * re-monta) y al vuelo cuando llega un push con la pestaña abierta, vía el
 * evento "notification-received" (ver public/sw.js + app.js) — nada de
 * peticiones periódicas por alumno.
 */
class Bell extends Component
{
    public bool $open = false;

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function markAsRead(string $id): void
    {
        Auth::user()->notifications()->where('id', $id)->first()?->markAsRead();
    }

    // Sin cuerpo: manejar el evento ya fuerza el re-render de abajo.
    #[On('notification-received')]
    public function refresh(): void
    {
    }

    public function render()
    {
        return view('livewire.students.notifications.bell', [
            // Solo se consulta la lista si el panel está abierto; cerrado,
            // solo corre el COUNT (columna indexada por el morphs() de la
            // migración de notifications).
            'notifications' => $this->open
                ? Auth::user()->notifications()->latest()->take(10)->get()
                : collect(),
            'unreadCount' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}
