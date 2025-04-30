<?php

namespace Moonz\Generator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class InitServiceRepository extends Command
{
    protected $signature = 'init-service-repository';
    protected $description = 'Initialize service and repository classes';

    public function handle()
    {
        // Install Packages Spatie\QueryBuilder\QueryBuilder
        $this->installDependencies();

        // Initialize Traits
        $this->generateTraits();

        // Generate ApiResponse
        $this->generateApiResponse();

        // Generate AppException
        $this->generateAppException();

        // Generate AppServiceProvider
        $this->generateAppServiceProvider();

        // Generate Base Repository
        $this->generateBaseRepository();

        // Generate RepositoryServiceProvider
        $this->generateRepositoryServiceProvider();

        // Generate Providers
        $this->generateProviders();

        $this->info("Base Service Repository generated successfully!");
    }

    protected function installDependencies()
    {
        $this->info('Installing spatie/laravel-query-builder...');

        $process = new Process(['composer', 'require', 'spatie/laravel-query-builder']);
        $process->setTimeout(300); // optional, in seconds
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->info('spatie/laravel-query-builder installed successfully.');
    }

    protected function generateTraits()
    {
        $uuidPath = app_path("Traits/Uuid.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/uuid.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Traits'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Traits'));
        File::put($uuidPath, $content);

        $this->info("Traits created successfully at <info><a href='{$uuidPath}'>" . basename($uuidPath) . "</a></info>!");
    }

    protected function generateAppException()
    {
        $exceptionPath = app_path("Exceptions/AppException.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/app-exception.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Exceptions'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Exceptions'));
        File::put($exceptionPath, $content);

        $this->info("App Exception created successfully at <info><a href='{$exceptionPath}'>" . basename($exceptionPath) . "</a></info>!");
    }

    protected function generateBaseRepository()
    {
        $baseRepositoryPath = app_path("Repositories/BaseRepository.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/base-repository.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Repositories'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Repositories'));
        File::put($baseRepositoryPath, $content);

        $this->info("Base Repository created successfully at <info><a href='{$baseRepositoryPath}'>" . basename($repositoryPath) . "</a></info>!");
    }

    protected function generateApiResponse()
    {
        $apiResponsePath = app_path("Helpers/ApiResponse.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/api-response.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Helpers'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Helpers'));
        File::put($apiResponsePath, $content);

        $this->info("ApiResponse created successfully at <info><a href='{$apiResponsePath}'>" . basename($apiResponsePath) . "</a></info>!");
    }

    protected function generateAppServiceProvider()
    {
        $appProviderPath = app_path("Providers/AppServiceProvider.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/app-service-provider.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Providers'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Providers'));
        File::put($appProviderPath, $content);

        $this->info("AppServiceProvider created successfully at <info><a href='{$appProviderPath}'>" . basename($appProviderPath) . "</a></info>!");
    }

    public function generateRepositoryServiceProvider()
    {
        $repositoryPath = app_path("Providers/RepositoryServiceProvider.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/repository-service-provider.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Providers'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Providers'));
        if (File::exists($repositoryPath)) {
            $this->warn("File RepositoryServiceProvider.php already exists at <info><a href='{$repositoryPath}'>" . basename($repositoryPath) . "</a></info>. Skipping...");
            return;
        } else {
            File::put($repositoryPath, $content);
            $this->info("RepositoryServiceProvider created successfully at <info><a href='{$repositoryPath}'>" . basename($repositoryPath) . "</a></info>!");
        }
    }

    protected function generateProviders()
    {
        $providersPath = base_path("bootstrap/providers.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/providers.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Providers'],
            $stub
        );

        File::ensureDirectoryExists(base_path('bootstrap'));
        File::put($providersPath, $content);
        $this->info("RouteServiceProvider created successfully at <info><a href='{$providersPath}'>" . basename($providersPath) . "</a></info>!");
    }
}