<?php

namespace App\Notifications;

class ProfileApproved extends StudentNotification
{
    public function title(): string
    {
        return 'Perfil aprobado';
    }

    public function body(): string
    {
        return 'Tu perfil de servicio social ha sido aprobado. Ya puedes subir tus documentos.';
    }
}
