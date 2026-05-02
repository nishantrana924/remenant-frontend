<?php
/**
 * Synchronize Product Table with Frontend Needs
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('long_description');
            }
            if (!Schema::hasColumn('products', 'gallery')) {
                $table->json('gallery')->nullable()->after('image');
            }
            if (!Schema::hasColumn('products', 'benefits')) {
                $table->json('benefits')->nullable()->after('gallery');
            }
            if (!Schema::hasColumn('products', 'highlights')) {
                $table->json('highlights')->nullable()->after('benefits');
            }
            if (!Schema::hasColumn('products', 'ritual')) {
                $table->json('ritual')->nullable()->after('highlights');
            }
            if (!Schema::hasColumn('products', 'specs')) {
                $table->json('specs')->nullable()->after('ritual');
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
            // Fix reviews_count if needed
            if (!Schema::hasColumn('products', 'reviews')) {
                $table->integer('reviews')->default(0)->after('rating');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['image', 'gallery', 'benefits', 'highlights', 'ritual', 'specs', 'is_featured', 'reviews']);
        });
    }
};
