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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('file_id')->nullable()->constrained('files')->onDelete('set null'); // relación con File

            // Información del documento
            $table->string('name'); // nombre del documento/entrega

            // Archivos subidos por el estudiante
            $table->string('student_file_path')->nullable();
            $table->string('student_file_name')->nullable();

            // Fecha límite personalizada (solo para este estudiante)
            $table->date('custom_limit_date')->nullable();

            // Estado del documento
            $table->enum('status', ['en_revision', 'revisado', 'rechazado'])->default('en_revision');
            $table->boolean('is_active')->default(true); // nuevo: para manejar documentos activos/inactivos
            $table->text('comments')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
