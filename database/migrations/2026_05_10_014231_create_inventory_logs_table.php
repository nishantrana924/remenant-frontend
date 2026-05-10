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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('old_stock')->default(0);
            $table->integer('new_stock');
            $table->integer('change_amount');
            $table->string('reason')->nullable(); // e.g. "manual_update", "order_placed", "restock"
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // The admin who did it
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
