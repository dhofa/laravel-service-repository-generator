<?php

namespace Moonz\Generator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateServiceRepository extends Command
{
    protected $signature = 'make:service-repository {name}';
    protected $description = 'Create a new service and repository classes';

    public function handle()
    {
        $name = $this->argument('name');
        
        // Generate Base Repository
        $this->generateBaseRepository();

        // Generate Migration
        $this->generateMigration($name);

        // Generate Model
        $this->generateModel($name);

        // Generate Interface Repository
        $this->generateInterfaceRepository($name);

        // Generate Repository
        $this->generateRepository($name);

        // Generate Request
        $this->generateRequest($name);

        // Generate Resource
        $this->generateResource($name);

        // Generate Service
        $this->generateService($name);

        $this->info("Service and Repository for {$name} created successfully!");
    }

    protected function generateMigration($name)
    {
        $table_name = Str::plural(Str::snake($name));
        $migrationPath = database_path("migrations/".date('Y_m_d_His')."_create_{$table_name}_table.php");
        $stubMigration = file_get_contents(__DIR__ . '/../Stubs/migration.stub');
        $contentMigration = str_replace(
            ['{{table_name}}'],
            [$table_name],
            $stubMigration
        );
        File::ensureDirectoryExists(database_path('migrations'));
        File::put($migrationPath, $contentMigration);

        $this->info("Migration for {$name} created successfully at <info>{$migrationPath}</info>!");
    }

    protected function generateModel($name)
    {
        $modelPath = app_path("Models/{$name}.php");
        $stubModel = file_get_contents(__DIR__ . '/../Stubs/model.stub');
        $contentModel = str_replace(
            ['{{class}}', '{{namespace}}'],
            [$name, 'App\Models'],
            $stubModel
        );
        File::ensureDirectoryExists(app_path('Models'));
        File::put($modelPath, $contentModel);

        $this->info("Model for {$name} created successfully at <info>{$modelPath}</info>!");
    }

    protected function generateRequest($name)
    {
        $createRequestPath = app_path("Http/Requests/{$name}/CreateRequest.php");
        $updateRequestPath = app_path("Http/Requests/{$name}/UpdateRequest.php");

        $stubCreate = file_get_contents(__DIR__ . '/../Stubs/create-request.stub');
        $contentCreate = str_replace(
            ['{{class}}', '{{namespace}}'],
            [$name, 'App\Http\Requests'],
            $stubCreate
        );

        $stubUpdate = file_get_contents(__DIR__ . '/../Stubs/update-request.stub');
        $contentUpdate = str_replace(
            ['{{class}}', '{{namespace}}'],
            [$name, 'App\Http\Requests'],
            $stubUpdate
        );

        File::ensureDirectoryExists(app_path("Http/Requests/{$name}"));
        File::put($createRequestPath, $contentCreate);
        File::put($updateRequestPath, $contentUpdate);

        $this->info("Request for {$name} created successfully at <info>{$createRequestPath}</info> and <info>{$updateRequestPath}</info>!");
    }

    protected function generateResource($name)
    {
        $resourcePath = app_path("Http/Resources/{$name}Resource.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/resource.stub');
        
        $content = str_replace(
            ['{{class}}', '{{namespace}}'],
            [$name, 'App\Http\Resources'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Http/Resources'));
        File::put($resourcePath, $content);

        $this->info("Resource for {$name} created successfully at <info>{$resourcePath}</info>!");
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

        $this->info("Service for {$name} created successfully at <info>{$servicePath}</info>!");
    }

    protected function generateBaseRepository()
    {
        $repositoryPath = app_path("Repositories/BaseRepository.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/base-repository.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Repositories'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Repositories'));
        File::put($repositoryPath, $content);

        $this->info("Base Repository created successfully at <info>{$repositoryPath}</info>!");
    }

    protected function generateInterfaceRepository($name)
    {
        $repositoryPath = app_path("Repositories/Contracts/{$name}RepositoryInterface.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/interface-repository.stub');
        
        $content = str_replace(
            ['{{class}}', '{{namespace}}'],
            [$name, 'App\Repositories\Contracts'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Repositories\Contracts'));
        File::put($repositoryPath, $content);

        $this->info("Interface Repository for {$name} created successfully at <info>{$repositoryPath}</info>!");
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

        $this->info("Service and Repository for {$name} created successfully at <info>{$repositoryPath}</info>!");
    }
}