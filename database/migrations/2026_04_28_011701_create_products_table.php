<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('mrp', 10, 2);
            $table->text('description')->nullable();
            $table->longText('long_description')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->float('rating')->default(5.0);
            $table->integer('reviews')->default(0);
            $table->string('color_theme')->default('orange');
            $table->string('color_gradient')->nullable();
            $table->json('benefits')->nullable();
            $table->json('specs')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
