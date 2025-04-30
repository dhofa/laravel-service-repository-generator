<?php

namespace Moonz\Generator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InitServiceRepository extends Command
{
    protected $signature = 'init-service-repository';
    protected $description = 'Initialize service and repository classes';

    public function handle()
    {
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

    protected function generateAppException()
    {
        $repositoryPath = app_path("Exceptions/AppException.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/app-exception.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Exceptions'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Exceptions'));
        File::put($repositoryPath, $content);

        $this->info("App Exception created successfully at <info><a href='{$repositoryPath}'>" . basename($repositoryPath) . "</a></info>!");
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

        $this->info("Base Repository created successfully at <info><a href='{$repositoryPath}'>" . basename($repositoryPath) . "</a></info>!");
    }

    protected function generateApiResponse()
    {
        $repositoryPath = app_path("Helpers/ApiResponse.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/api-response.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Helpers'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Helpers'));
        File::put($repositoryPath, $content);

        $this->info("ApiResponse created successfully at <info><a href='{$repositoryPath}'>" . basename($repositoryPath) . "</a></info>!");
    }

    protected function generateAppServiceProvider()
    {
        $repositoryPath = app_path("Providers/AppServiceProvider.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/app-service-provider.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Providers'],
            $stub
        );

        File::ensureDirectoryExists(app_path('Providers'));
        File::put($repositoryPath, $content);

        $this->info("AppServiceProvider created successfully at <info><a href='{$repositoryPath}'>" . basename($repositoryPath) . "</a></info>!");
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
        $repositoryPath = base_path("bootstrap/providers.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/providers.stub');
        
        $content = str_replace(
            ['{{namespace}}'],
            ['App\Providers'],
            $stub
        );

        File::ensureDirectoryExists(base_path('bootstrap'));
        File::put($repositoryPath, $content);
        $this->info("RouteServiceProvider created successfully at <info><a href='{$repositoryPath}'>" . basename($repositoryPath) . "</a></info>!");
    }
}