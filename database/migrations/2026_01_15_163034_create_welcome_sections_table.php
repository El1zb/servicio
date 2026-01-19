<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('welcome_sections', function (Blueprint $table) {
            $table->id();

            // Header
            $table->string('header_title');       // Ej: "Servicio Social ITSCO"
            $table->string('header_subtitle');    // Ej: "ITSCO"

            // Indicador
            $table->string('indicador');          // Ej: "Agiliza en linea"

            // Main encabezados
            $table->string('main_encabezado');    // Ej: "Gestión de" (color negro en frontend)
            $table->string('main_encabezado2');   // Ej: "Servicio Social" (color azul en frontend)
            $table->string('main_subencabezado'); // Ej: "Instituto Tecnológico Superior de Cosamaloapan"

            // Requisitos
            $table->string('requisito_avance_academico'); // Ej: "70% de créditos aprobados"

            // Duración y créditos
            $table->string('horas_total');
            $table->string('creditos');
            $table->string('meses_maximo');

            // Institución
            $table->string('nombre_institucion');  // Ej: "Instituto Tecnológico Superior de Cosamaloapan"
            $table->string('abreviatura');         // Ej: "ITSCO"
            $table->string('logo');                // Ruta o URL del logo

            // Contacto
            $table->string('email_contacto');
            $table->string('telefono_contacto');
            $table->string('direccion_contacto');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welcome_sections');
    }
};
