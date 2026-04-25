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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('title');
            $table->enum('category', [
                'promo',
                'event',
                'pengumuman',
                'update_studio'
            ]);
            $table->date('publish_date');
            $table->string('short_caption');
            $table->text('content');
            $table->string('instagram_url')->nullable();
            $table->boolean('sync_insta')->default(false);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
