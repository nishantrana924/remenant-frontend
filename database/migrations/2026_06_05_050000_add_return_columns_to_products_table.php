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
            $table->boolean('return_allowed')->default(true)->after('stock');
            $table->integer('return_days')->default(7)->after('return_allowed');
            $table->decimal('return_fee', 10, 2)->default(0)->after('return_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['return_allowed', 'return_days', 'return_fee']);
        });
    }
};
