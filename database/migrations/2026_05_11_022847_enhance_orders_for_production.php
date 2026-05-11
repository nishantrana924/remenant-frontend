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
            if (!Schema::hasColumn('orders', 'alternate_phone')) {
                $table->string('alternate_phone')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('orders', 'landmark')) {
                $table->string('landmark')->nullable()->after('address');
            }
            if (!Schema::hasColumn('orders', 'estimated_delivery_date')) {
                $table->date('estimated_delivery_date')->nullable()->after('delivery_status');
            }
            if (!Schema::hasColumn('orders', 'payment_transaction_id')) {
                $table->string('payment_transaction_id')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'refund_status')) {
                $table->string('refund_status')->default('none')->after('payment_transaction_id');
            }
            if (!Schema::hasColumn('orders', 'refund_amount')) {
                $table->decimal('refund_amount', 10, 2)->default(0)->after('refund_status');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'sku')) {
                $table->string('sku')->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('order_items', 'variant_size')) {
                $table->string('variant_size')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('order_items', 'variant_color')) {
                $table->string('variant_color')->nullable()->after('variant_size');
            }
            if (!Schema::hasColumn('order_items', 'weight')) {
                $table->string('weight')->nullable()->after('variant_color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'alternate_phone', 'landmark', 'estimated_delivery_date', 
                'payment_transaction_id', 'refund_status', 'refund_amount'
            ]);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['sku', 'variant_size', 'variant_color', 'weight']);
        });
    }
};
