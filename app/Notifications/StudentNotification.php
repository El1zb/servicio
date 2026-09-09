<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

/**
 * Base de las notificaciones del estudiante (perfil y documentos): mismos
 * canales (campanita in-app + push) y payload — cada subclase solo define
 * título/cuerpo. Sin ShouldQueue: no hay worker de colas corriendo, y el
 * envío es instantáneo salvo que el estudiante tenga push activo (en cuyo
 * caso el propio paquete atrapa cualquier fallo, sin tumbar al admin).
 */
abstract class StudentNotification extends Notification
{
    abstract public function title(): string;

    abstract public function body(): string;

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->title(),
            'body'  => $this->body(),
            'url'   => route('student-documents.index'),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title($this->title())
            ->body($this->body())
            ->icon('/apple-touch-icon.png')
            ->data(['url' => route('student-documents.index')]);
    }
}
