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
            $table->decimal('subtotal', 10, 2)->default(0)->after('user_id');
            $table->string('coupon_code')->nullable()->after('subtotal');
            $table->string('coupon_discount_type')->nullable()->after('coupon_code');
            $table->decimal('coupon_discount_value', 10, 2)->nullable()->after('coupon_discount_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal',
                'coupon_code',
                'coupon_discount_type',
                'coupon_discount_value'
            ]);
        });
    }
};
