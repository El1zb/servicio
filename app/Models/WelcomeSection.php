<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelcomeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'header_title',
        'header_subtitle',
        'indicador',
        'main_encabezado',
        'main_encabezado2',
        'main_subencabezado',
        'requisito_avance_academico',
        'horas_total',
        'creditos',
        'meses_maximo',
        'nombre_institucion',
        'abreviatura',
        'logo',
        'email_contacto',
        'telefono_contacto',
        'direccion_contacto',
    ];
}
