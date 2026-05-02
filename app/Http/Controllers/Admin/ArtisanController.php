<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class ArtisanController extends Controller
{
    /**
     * Run migrations
     */
    public function migrate()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            return "Migrations run successfully: <br><pre>" . Artisan::output() . "</pre>";
        } catch (\Exception $e) {
            return "Error running migrations: " . $e->getMessage();
        }
    }

    /**
     * Run seeders
     */
    public function seed()
    {
        try {
            Artisan::call('db:seed', ['--force' => true]);
            return "Database seeded successfully: <br><pre>" . Artisan::output() . "</pre>";
        } catch (\Exception $e) {
            return "Error seeding database: " . $e->getMessage();
        }
    }

    /**
     * Run full setup (AppSetup command)
     */
    public function setup()
    {
        try {
            Artisan::call('app:setup');
            return "App setup completed successfully: <br><pre>" . Artisan::output() . "</pre>";
        } catch (\Exception $e) {
            return "Error running app setup: " . $e->getMessage();
        }
    }

    public function debugDb()
    {
        $config = config('database');
        // Obfuscate sensitive info
        foreach ($config['connections'] as &$conn) {
            if (isset($conn['password'])) $conn['password'] = '******';
        }
        return response()->json([
            'default' => config('database.default'),
            'env_db_connection' => env('DB_CONNECTION'),
            'connections_keys' => array_keys($config['connections']),
            'full_config' => $config
        ]);
    }
}
