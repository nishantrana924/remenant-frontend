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
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'refund_requested_at')) {
                $table->timestamp('refund_requested_at')->nullable()->after('razorpay_refund_id');
            }
            if (!Schema::hasColumn('orders', 'refund_processed_at')) {
                $table->timestamp('refund_processed_at')->nullable()->after('refund_requested_at');
            }
            if (!Schema::hasColumn('orders', 'payment_reconciled_at')) {
                $table->timestamp('payment_reconciled_at')->nullable()->after('refund_processed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'paid_at',
                'refund_requested_at',
                'refund_processed_at',
                'payment_reconciled_at',
            ]);
        });
    }
};
