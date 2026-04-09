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
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->string('studio_code')->unique();
            $table->string('photo')->nullable();
            $table->string('name');
            $table->integer('max_people_per_session');
            $table->integer('session_duration');
            $table->integer('photo_strips');
            $table->enum('paper_type', ['negative_film', 'photo_paper'])->default('photo_paper');
            $table->decimal('price', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studios');
    }
};
