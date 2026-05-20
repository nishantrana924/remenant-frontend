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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('nimbus_shipment_id')->nullable()->index();
            $table->string('awb_number')->nullable()->index();
            $table->string('courier_id')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('status')->default('pending');
            $table->string('label_url')->nullable();
            $table->string('manifest_url')->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('cod_charge', 10, 2)->default(0);
            $table->decimal('weight', 10, 2)->nullable();
            $table->string('tracking_url')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
