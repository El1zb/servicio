<?php

namespace App\Notifications;

use App\Models\Document;

class DocumentCommented extends StudentNotification
{
    public function __construct(private Document $document)
    {
    }

    public function title(): string
    {
        return 'Nuevo comentario';
    }

    public function body(): string
    {
        return "Se agregó un comentario a tu documento \"{$this->document->name}\".";
    }
}
