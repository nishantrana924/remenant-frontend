<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Generate a new modular structure (Model, Controller, Service, Repository, etc.)';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $variable = Str::camel($name);
        $variablePlural = Str::plural($variable);
        $tableName = Str::snake($variablePlural);

        $this->generateFile($name, 'Model', app_path("Models/{$name}.php"), 'model.stub');
        $this->generateFile($name, 'Controller', app_path("Http/Controllers/Admin/{$name}Controller.php"), 'controller.stub', $variable, $variablePlural);
        $this->generateFile($name, 'Service', app_path("Services/{$name}Service.php"), 'service.stub');
        $this->generateFile($name, 'RepositoryInterface', app_path("Repositories/{$name}RepositoryInterface.php"), 'repository_interface.stub');
        $this->generateFile($name, 'Repository', app_path("Repositories/{$name}Repository.php"), 'repository.stub');
        $this->generateFile($name, 'Request', app_path("Http/Requests/Admin/{$name}Request.php"), 'request.stub');
        
        $migrationName = "create_{$tableName}_table";
        $migrationFile = database_path("migrations/" . date('Y_m_d_His') . "_{$migrationName}.php");
        $this->generateMigration($name, $tableName, $migrationFile);

        $this->info("Module {$name} generated successfully.");
        $this->warn("Don't forget to bind the repository in RepositoryServiceProvider and create the migration manually.");
    }

    protected function generateFile($name, $type, $path, $stub, $variable = '', $variablePlural = '')
    {
        if (File::exists($path)) {
            $this->error("{$type} already exists!");
            return;
        }

        $content = File::get(base_path("stubs/module/{$stub}"));
        $content = str_replace(
            ['{{class}}', '{{variable}}', '{{variablePlural}}'],
            [$name, $variable, $variablePlural],
            $content
        );

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
        $this->line("{$type} created at {$path}");
    }

    protected function generateMigration($name, $tableName, $path)
    {
        $content = File::get(base_path("stubs/module/migration.stub"));
        $content = str_replace('{{tableName}}', $tableName, $content);
        File::put($path, $content);
        $this->line("Migration created at {$path}");
    }
}
