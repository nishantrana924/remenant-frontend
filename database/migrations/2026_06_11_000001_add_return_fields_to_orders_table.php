<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'return_status')) {
                $table->string('return_status')->default('none')->after('refund_processed_at');
                // none | requested | approved | rejected | picked_up | completed
            }
            if (!Schema::hasColumn('orders', 'return_reason')) {
                $table->text('return_reason')->nullable()->after('return_status');
            }
            if (!Schema::hasColumn('orders', 'return_requested_at')) {
                $table->timestamp('return_requested_at')->nullable()->after('return_reason');
            }
            if (!Schema::hasColumn('orders', 'return_approved_at')) {
                $table->timestamp('return_approved_at')->nullable()->after('return_requested_at');
            }
            if (!Schema::hasColumn('orders', 'return_shipping_charge')) {
                $table->decimal('return_shipping_charge', 10, 2)->default(0)->after('return_approved_at');
            }
            if (!Schema::hasColumn('orders', 'return_admin_note')) {
                $table->text('return_admin_note')->nullable()->after('return_shipping_charge');
            }
            if (!Schema::hasColumn('orders', 'return_awb')) {
                $table->string('return_awb')->nullable()->after('return_admin_note');
            }
            if (!Schema::hasColumn('orders', 'return_nimbus_shipment_id')) {
                $table->string('return_nimbus_shipment_id')->nullable()->after('return_awb');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'return_status',
                'return_reason',
                'return_requested_at',
                'return_approved_at',
                'return_shipping_charge',
                'return_admin_note',
                'return_awb',
                'return_nimbus_shipment_id',
            ]);
        });
    }
};
