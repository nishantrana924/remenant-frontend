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
        Schema::create('page_content_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_content_id')->constrained('page_contents')->onDelete('cascade');
            $table->json('content');
            $table->string('status')->default('draft');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('version_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_content_versions');
    }
};
