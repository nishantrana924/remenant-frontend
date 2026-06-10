<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds billed_charge column to shipments table.
     * This column stores the actual courier charge fetched from NimbusPost rate API
     * at the time of shipment creation. It was being saved in shipToNimbusPost() and
     * bulkShipToNimbusPost() but the column was missing, causing 500 errors.
     */
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            if (!Schema::hasColumn('shipments', 'billed_charge')) {
                $table->decimal('billed_charge', 10, 2)->nullable()->after('label_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            if (Schema::hasColumn('shipments', 'billed_charge')) {
                $table->dropColumn('billed_charge');
            }
        });
    }
};
