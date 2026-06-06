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
        Schema::table('products', function (Blueprint $table) {
            $table->index('slug');
            $table->index('status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('order_number');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index('product_id');
        });

        Schema::table('category_product', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['status']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['order_number']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('category_product', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['product_id']);
        });
    }
};
