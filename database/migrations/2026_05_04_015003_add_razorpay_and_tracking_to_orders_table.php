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
        Schema::table('orders', function (Blueprint $table) {
            // Razorpay Fields
            $table->string('razorpay_order_id')->nullable()->after('payment_method');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
            
            // Advanced Tracking
            $table->string('tracking_url')->nullable()->after('courier_name');
            $table->timestamp('shipped_at')->nullable()->after('delivery_status');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature',
                'tracking_url', 'shipped_at', 'delivered_at'
            ]);
        });
    }
};
