<?php

namespace App\Notifications;

use App\Models\Document;

class DocumentStatusChanged extends StudentNotification
{
    /**
     * @param  string  $status  'revisado' o 'rechazado' (mismos valores que Document::status)
     */
    public function __construct(private Document $document, private string $status)
    {
    }

    public function title(): string
    {
        return $this->status === 'revisado' ? 'Documento aprobado' : 'Documento rechazado';
    }

    public function body(): string
    {
        $name = $this->document->name;

        return $this->status === 'revisado'
            ? "Tu documento \"{$name}\" ha sido revisado y aprobado."
            : "Tu documento \"{$name}\" fue rechazado. Revisa los comentarios.";
    }
}
