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
        Schema::table('warehouses', function (Blueprint $table) {
            if (!Schema::hasColumn('warehouses', 'nimbus_id')) {
                $table->string('nimbus_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('warehouses', 'contact_person')) {
                $table->string('contact_person')->nullable()->after('name');
            }
            if (!Schema::hasColumn('warehouses', 'address_2')) {
                $table->string('address_2')->nullable()->after('address');
            }
            if (!Schema::hasColumn('warehouses', 'gst_number')) {
                $table->string('gst_number')->nullable()->after('state');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn(['nimbus_id', 'contact_person', 'address_2', 'gst_number']);
        });
    }
};
