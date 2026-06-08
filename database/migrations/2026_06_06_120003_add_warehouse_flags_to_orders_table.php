<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('requires_manual_review')->default(false)->index();
            $table->string('manual_review_reason')->nullable();
            $table->decimal('calculated_weight', 8, 2)->nullable();
            $table->decimal('override_weight', 8, 2)->nullable();
            $table->decimal('calculated_length', 8, 2)->nullable();
            $table->decimal('calculated_width', 8, 2)->nullable();
            $table->decimal('calculated_height', 8, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['requires_manual_review']);
            $table->dropColumn([
                'requires_manual_review', 'manual_review_reason',
                'calculated_weight', 'override_weight',
                'calculated_length', 'calculated_width', 'calculated_height'
            ]);
        });
    }
};
