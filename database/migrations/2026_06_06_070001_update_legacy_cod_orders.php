<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('orders')->where('payment_method', 'cod')->update(['payment_method' => 'razorpay']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // One way migration
    }
};
