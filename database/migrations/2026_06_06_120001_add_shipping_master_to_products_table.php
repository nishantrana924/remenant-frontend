<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('default_weight', 8, 2)->nullable();
            $table->decimal('default_length', 8, 2)->nullable();
            $table->decimal('default_width', 8, 2)->nullable();
            $table->decimal('default_height', 8, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['default_weight', 'default_length', 'default_width', 'default_height']);
        });
    }
};
