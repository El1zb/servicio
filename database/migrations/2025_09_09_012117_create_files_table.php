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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    
            $table->date('limit_date');                
            $table->foreignId('period_id')->constrained()->onDelete('cascade');
            $table->string('file_path');        
            $table->string('name_file')->nullable();       
            $table->string('example_path')->nullable(); 
            $table->string('example_name_file')->nullable();
            $table->integer('max_size')->default(10240); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
