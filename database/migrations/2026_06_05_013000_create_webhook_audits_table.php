<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_audits', function (Blueprint $table) {
            $table->id();
            $table->string('webhook_id')->index();
            $table->string('provider');
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('status');
            $table->string('result');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_audits');
    }
};
