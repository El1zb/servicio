<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WelcomeSection;

class WelcomeSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WelcomeSection::create([
            'header_title' => 'Servicio Social',
            'header_subtitle' => 'ITSCO',
            'indicador' => 'Agiliza en linea',
            'main_encabezado' => 'Gestión de',
            'main_encabezado2' => 'Servicio Social',
            'main_subencabezado' => 'Instituto Tecnológico Superior de Cosamaloapan',
            'requisito_avance_academico' => '70%',
            'horas_total' => '500',
            'creditos' => '10',
            'meses_maximo' => '6',
            'nombre_institucion' => 'Instituto Tecnológico Superior de Cosamaloapan',
            'abreviatura' => 'ITSCO',
            'logo' => 'logo.png',
            'email_contacto' => 'serviciosocial@itsco.edu.mx',
            'telefono_contacto' => '(288) 882-4000',
            'direccion_contacto' => 'Cosamaloapan, Veracruz',
        ]);
    }
}
