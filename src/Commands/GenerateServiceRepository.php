<?php

namespace Moonz\Generator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateServiceRepository extends Command
{
    protected $signature = 'make:service-repository {name}';
    protected $description = 'Create a new service and repository classes';

    public function handle()
    {
        $name = $this->argument('name');
        
        // Generate Service
        $this->generateService($name);
        // Generate Repository
        $this->generateRepository($name);

        $this->info("Service and Repository for {$name} created successfully!");
    }

    protected function generateService($name)
    {
        $servicePath = app_path("Services/{$name}Service.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/service.stub');
        
        $content = str_replace(
            ['{{class}}', '{{namespace}}'],
            [$name, 'App\Services'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Services'));
        File::put($servicePath, $content);
    }

    protected function generateRepository($name)
    {
        $repositoryPath = app_path("Repositories/{$name}Repository.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/repository.stub');
        
        $content = str_replace(
            ['{{class}}', '{{namespace}}'],
            [$name, 'App\Repositories'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Repositories'));
        File::put($repositoryPath, $content);
    }
}