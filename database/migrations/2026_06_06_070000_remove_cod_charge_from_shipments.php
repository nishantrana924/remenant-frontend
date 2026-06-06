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
        if (Schema::hasColumn('shipments', 'cod_charge')) {
            Schema::table('shipments', function (Blueprint $table) {
                $table->dropColumn('cod_charge');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('shipments', 'cod_charge')) {
            Schema::table('shipments', function (Blueprint $table) {
                $table->decimal('cod_charge', 10, 2)->default(0);
            });
        }
    }
};
