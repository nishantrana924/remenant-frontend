<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_batch_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('order_id');

            $table->foreign('batch_id')->references('id')->on('warehouse_batches')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->primary(['batch_id', 'order_id']);
            $table->unique('order_id');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_batch_orders');
    }
};
