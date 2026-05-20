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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('nimbus_warehouse_id')->nullable()->unique();
            $table->string('name');
            $table->string('contact_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address');
            $table->string('pincode');
            $table->string('city');
            $table->string('state');
            $table->string('gstin')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
