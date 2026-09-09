<?php

namespace App\Notifications;

class ProfileRejected extends StudentNotification
{
    public function __construct(private string $reason)
    {
    }

    public function title(): string
    {
        return 'Perfil rechazado';
    }

    public function body(): string
    {
        return "Tu perfil fue rechazado. Motivo: {$this->reason}";
    }
}
