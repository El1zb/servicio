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
        Schema::create('file_student_uploads', function (Blueprint $table) {
            $table->id();

            $table->foreignId('file_id')->constrained('files')->onDelete('cascade'); // Documento base
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // Estudiante

            $table->string('file_path')->nullable(); // Ruta del Word o PDF subido
            $table->string('name_file')->nullable(); // Nombre original del archivo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_student_uploads');
    }
};
