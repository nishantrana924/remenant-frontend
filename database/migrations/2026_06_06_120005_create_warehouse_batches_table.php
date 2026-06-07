<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_signature')->index();
            $table->enum('batch_type', ['single_product', 'single_product_quantity', 'combo', 'multi_product', 'manual_review']);
            $table->enum('status', ['pending', 'processing', 'awb_generated', 'labels_generated', 'slips_printed', 'ready_for_pickup', 'dispatched', 'completed']);
            $table->unsignedBigInteger('suggested_courier_id')->nullable();
            $table->unsignedBigInteger('assigned_courier_id')->nullable();
            $table->decimal('assigned_weight', 8, 2)->nullable();
            $table->integer('total_orders')->default(0);
            $table->integer('total_units')->default(0);
            $table->decimal('total_order_value', 10, 2)->default(0);
            $table->decimal('estimated_shipping_cost', 10, 2)->nullable();
            $table->decimal('actual_shipping_cost', 10, 2)->nullable();
            $table->unsignedBigInteger('locked_by')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('suggested_courier_id')->references('id')->on('couriers')->onDelete('set null');
            $table->foreign('assigned_courier_id')->references('id')->on('couriers')->onDelete('set null');
            $table->foreign('locked_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['status', 'batch_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_batches');
    }
};
