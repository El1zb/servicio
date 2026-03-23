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
                $table->date('limit_date')->nullable();
                $table->text('firman')->nullable();
                $table->text('observations')->nullable();

                $table->foreignId('period_id')->constrained()->onDelete('cascade');

                $table->string('file_path')->nullable();
                $table->string('name_file')->nullable();
                $table->string('example_path')->nullable();
                $table->string('example_name_file')->nullable();
                $table->integer('max_size')->default(10240);

                $table->enum('upload_mode', ['user_only', 'admin_only', 'bidirectional'])->default('bidirectional');
                //$table->string('upload_mode')->default('bidirectional');
                $table->boolean('is_individual')->default(false);

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
