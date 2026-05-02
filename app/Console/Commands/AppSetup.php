<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AppSetup extends Command
{
    protected $signature = 'app:setup';
    protected $description = 'Initialize the project with migration, seeding, and cache clearing.';

    public function handle()
    {
        $this->info('Starting project setup...');

        $this->call('migrate:fresh', ['--force' => true]);
        $this->info('Migrations completed.');

        $this->call('db:seed', ['--force' => true]);
        $this->info('Database seeded.');

        $this->call('storage:link');
        $this->info('Storage link created.');

        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');
        $this->info('Caches cleared.');

        $this->info('Project setup finished successfully.');
    }
}
