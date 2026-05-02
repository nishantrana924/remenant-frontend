<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'badge')) $table->string('badge')->nullable()->after('tagline');
            if (!Schema::hasColumn('products', 'highlights')) $table->longText('highlights')->nullable()->change();
            if (!Schema::hasColumn('products', 'ritual')) $table->json('ritual')->nullable()->after('highlights');
            if (!Schema::hasColumn('products', 'brand_info')) $table->longText('brand_info')->nullable()->after('ritual');
            if (!Schema::hasColumn('products', 'extra_content')) $table->longText('extra_content')->nullable()->after('brand_info');
            if (!Schema::hasColumn('products', 'discount_type')) $table->string('discount_type')->nullable()->after('mrp');
            if (!Schema::hasColumn('products', 'discount_value')) $table->decimal('discount_value', 10, 2)->nullable()->after('discount_type');
            if (!Schema::hasColumn('products', 'theme_color')) $table->string('theme_color')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['badge', 'ritual', 'brand_info', 'extra_content', 'discount_type', 'discount_value', 'theme_color']);
        });
    }
};
