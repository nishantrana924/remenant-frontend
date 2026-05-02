<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add missing fields
            if (!Schema::hasColumn('products', 'video_url')) $table->string('video_url')->nullable()->after('hsn_code');
            if (!Schema::hasColumn('products', 'faqs')) $table->json('faqs')->nullable()->after('video_url');
            if (!Schema::hasColumn('products', 'low_stock_threshold')) $table->integer('low_stock_threshold')->default(10)->after('mrp');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'faqs', 'low_stock_threshold']);
        });
    }
};
