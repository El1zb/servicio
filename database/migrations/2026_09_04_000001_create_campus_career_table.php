<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campus_career', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_id')->constrained()->cascadeOnDelete();
            $table->foreignId('career_id')->constrained()->cascadeOnDelete();
            $table->unique(['campus_id', 'career_id']);
            $table->timestamps();
        });

        // Antes de esta tabla, cualquier carrera se mostraba en cualquier
        // plantel; para no romper el registro de estudiantes ya existente,
        // arrancamos con la matriz completa (todo con todo) y el admin
        // recorta las excepciones desde ahí.
        $campusIds = DB::table('campuses')->whereNull('deleted_at')->pluck('id');
        $careerIds = DB::table('careers')->whereNull('deleted_at')->pluck('id');

        $now  = now();
        $rows = [];
        foreach ($campusIds as $campusId) {
            foreach ($careerIds as $careerId) {
                $rows[] = [
                    'campus_id'  => $campusId,
                    'career_id'  => $careerId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('campus_career')->insert($chunk);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('campus_career');
    }
};
