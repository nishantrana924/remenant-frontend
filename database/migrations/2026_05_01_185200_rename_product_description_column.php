<?php
/**
 * Fix Product Column Names
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'short_description') && !Schema::hasColumn('products', 'description')) {
                $table->renameColumn('short_description', 'description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'description') && !Schema::hasColumn('products', 'short_description')) {
                $table->renameColumn('description', 'short_description');
            }
        });
    }
};
