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
        // First, update all existing users with 'user' role to 'admin'
        DB::table('users')->where('role', 'user')->update(['role' => 'admin']);

        // Modify the role column to only allow 'admin' and set default to 'admin'
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin'])->default('admin')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the original enum with 'user' and 'admin'
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin'])->default('user')->change();
        });
    }
};
