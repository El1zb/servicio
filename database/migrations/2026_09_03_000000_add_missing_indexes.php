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
        Schema::table('students', function (Blueprint $table) {
            $table->index('status');
            $table->index('control_number');
            $table->index('curp');
            $table->index('institutional_email');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->index('status');
            $table->index(['student_id', 'file_id']);
        });

        Schema::table('file_student_uploads', function (Blueprint $table) {
            // ManagesRevision::uploadIndividualFile() ya usa updateOrCreate sobre
            // exactamente esta combinación, así que el modelo de datos ya asume
            // que es única; esto lo hace explícito a nivel de base de datos.
            $table->unique(['file_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_status_index');
            $table->dropIndex('students_control_number_index');
            $table->dropIndex('students_curp_index');
            $table->dropIndex('students_institutional_email_index');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex('documents_status_index');
            $table->dropIndex('documents_student_id_file_id_index');
        });

        Schema::table('file_student_uploads', function (Blueprint $table) {
            $table->dropUnique(['file_id', 'student_id']);
        });
    }
};
