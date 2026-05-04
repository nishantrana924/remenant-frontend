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
        // Insert default roles
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->default(2)->after('phone');
        });

        // Migrate existing data
        DB::table('users')->where('role', 'admin')->update(['role_id' => 1]);
        DB::table('users')->where('role', 'user')->update(['role_id' => 2]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('role_id');
        });

        DB::table('users')->where('role_id', 1)->update(['role' => 'admin']);
        DB::table('users')->where('role_id', 2)->update(['role' => 'user']);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role_id');
        });
    }
};
