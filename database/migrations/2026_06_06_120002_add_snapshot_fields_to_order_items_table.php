<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_name')->nullable();
            $table->string('product_sku')->nullable();
            $table->string('product_image')->nullable();
            $table->decimal('product_weight', 8, 2)->nullable();
            $table->decimal('product_length', 8, 2)->nullable();
            $table->decimal('product_width', 8, 2)->nullable();
            $table->decimal('product_height', 8, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'product_name', 'product_sku', 'product_image',
                'product_weight', 'product_length', 'product_width', 'product_height'
            ]);
        });
    }
};
