<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('job_uuid')->nullable();
            $table->string('request_uuid')->nullable();
            $table->unsignedBigInteger('related_batch_id')->nullable();
            $table->unsignedBigInteger('related_order_id')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('related_batch_id')->references('id')->on('warehouse_batches')->onDelete('cascade');
            $table->foreign('related_order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->index('related_batch_id');
            $table->index('related_order_id');
            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_activity_logs');
    }
};
