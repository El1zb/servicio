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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('campus_id')->constrained('campuses')->onDelete('cascade');
            $table->foreignId('period_id')->constrained('periods')->onDelete('cascade'); // <-- agregado
            $table->enum('system', ['Escolarizado', 'Sabatino']);
            $table->foreignId('career_id')->constrained('careers')->onDelete('cascade');
            $table->string('curp')->unique();
            $table->string('rfc')->nullable();
            $table->string('control_number')->unique();
            $table->string('last_name_paterno');
            $table->string('last_name_materno');
            $table->string('name');
            $table->string('institutional_email')->unique();
            $table->string('personal_email');
            $table->string('phone');
            $table->decimal('reticular_progress', 5, 2)->default(0); 
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
