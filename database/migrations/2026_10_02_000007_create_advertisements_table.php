<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('position', [
                'horizontal_top',
                'sidebar_right',
                'article_middle',
                'home_bottom',
                'in_article',
            ])->default('horizontal_top');
            $table->enum('ad_type', ['image', 'code'])->default('image');
            $table->string('image_url')->nullable();
            $table->string('target_url')->nullable();
            $table->text('ad_code')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('impressions_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->integer('paragraph_position')->nullable()->default(3); // after paragraph 3, 5, 7
            $table->timestamps();

            $table->index(['position', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
