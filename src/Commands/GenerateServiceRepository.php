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

        // Generate Controller
        $this->generateController($name);

        // Generate Api Route
        $this->generateApiRoute($name);

        // Register RepositoryServiceProvider
        $this->registerRepositoryServiceProvider($name);

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

        $this->info("Migration for {$name} created successfully at <info><a href='{$migrationPath}'>" . basename($migrationPath) . "</a></info>!");
    }

    protected function generateModel($name)
    {
        $modelPath = app_path("Models/{$name}.php");
        $stubModel = file_get_contents(__DIR__ . '/../Stubs/model.stub');
        $contentModel = str_replace(
            ['{{class}}', '{{namespace}}', '{{table_name}}'],
            [$name, 'App\Models', Str::plural(Str::snake($name))],
            $stubModel
        );
        File::ensureDirectoryExists(app_path('Models'));
        File::put($modelPath, $contentModel);

        $this->info("Model for {$name} created successfully at <info><a href='{$modelPath}'>" . basename($modelPath) . "</a></info>!");
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

        $this->info("Request for {$name} created successfully at <info><a href='{$createRequestPath}'>" . basename($createRequestPath) . "</a></info> and <info><a href='{$updateRequestPath}'>" . basename($updateRequestPath) . "</a></info>!");
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

        $this->info("Resource for {$name} created successfully at <info><a href='{$resourcePath}'>" . basename($resourcePath) . "</a></info>!");
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

        $this->info("Service for {$name} created successfully at <info><a href='{$servicePath}'>" . basename($servicePath) . "</a></info>!");
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

        if (!file_exists(app_path('Repositories/Contracts')) || !is_dir(app_path('Repositories/Contracts'))) {
            File::makeDirectory(app_path('Repositories/Contracts'), 0755, true);
        }
        File::put($repositoryPath, $content);

        $this->info("Interface Repository for {$name} created successfully at <info><a href='{$repositoryPath}'>" . basename($repositoryPath) . "</a></info>!");
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

        if (!File::exists(app_path('Repositories'))) {
            File::makeDirectory(app_path('Repositories'), 0755, true);
        }
        File::put($repositoryPath, $content);

        $this->info("Service and Repository for {$name} created successfully at <info><a href='{$repositoryPath}'>" . basename($repositoryPath) . "</a></info>!");
    }

    protected function generateController($name)
    {
        $controllerPath = app_path("Http/Controllers/Api/{$name}Controller.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/controller.stub');
        
        $content = str_replace(
            ['{{class}}', '{{namespace}}', '{{camel_class}}'],
            [$name, 'App\Http\Controllers\Api', Str::of($name)->camel()],
            $stub
        );

        File::ensureDirectoryExists(app_path('Http/Controllers/Api'));
        File::put($controllerPath, $content);

        $this->info("Controller for {$name} created successfully at <info><a href='{$controllerPath}'>" . basename($controllerPath) . "</a></info>!");
    }

    protected function generateApiRoute($name)
    {
        $filePath = base_path("routes/api.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/api-route.stub');
        
        $stubContent = str_replace(
            ['{{class}}', '{{slug}}'],
            [$name, Str::of($name)->slug('-')],
            $stub
        );

        File::ensureDirectoryExists(base_path('routes'));
        
        $this->insertApiRoute($filePath, $stubContent, $name);

        $insertAfter = '<?php';
        $useStatements = [
            "use App\Http\Controllers\Api\\{$name}Controller;",
        ];
        $this->insertUseCode($filePath, $stubContent, $useStatements, $insertAfter);
    }

    protected function insertApiRoute($filePath, $stubContent, $class)
    {
        $content = File::get($filePath);
        $lines = explode("\n", $content);
        $newLines = [];
    
        $insideGroup = false;
        $bracketCount = 0;
        $inserted = false;
    
        foreach ($lines as $line) {
            $trimmed = trim($line);
    
            // Deteksi awal grup Route::prefix('/v1')
            if (str_contains($trimmed, "Route::prefix('/v1')")) {
                $insideGroup = true;
            }
    
            if ($insideGroup) {
                $bracketCount += substr_count($line, '{');
                $bracketCount -= substr_count($line, '}');
    
                // Sebelum tutup grup, tambahkan route
                if ($bracketCount === 0 && !$inserted) {
                    $newLines[] = "    " . $stubContent . "\n";
                    $inserted = true;
                    $insideGroup = false;
                }
            }
    
            $newLines[] = $line;
        }
    
        File::put($filePath, implode("\n", $newLines));
        $this->info("Route for {$class} added to routes/api.php");
    }

    protected function registerRepositoryServiceProvider($name)
    {
        $filePath = app_path("Providers/RepositoryServiceProvider.php");
        $stub = file_get_contents(__DIR__ . '/../Stubs/repository-service-provider-bind.stub');
        
        $bindCode = str_replace(
            ['{{class}}', '{{namespace}}'],
            [$name, 'App\Providers'],
            $stub
        );
    
        $content = File::get($filePath);

        File::ensureDirectoryExists(app_path('Providers'));
        if (!File::exists($filePath)) {
            (new InitServiceRepository())->generateRepositoryServiceProvider();
        }

        $this->insertBindCodeProvider($filePath, $bindCode, $content);
        
        $insertAfter = 'namespace App\Providers;';
        $useStatements = [
            "use App\Models\\$name;",
            "use App\Repositories\Contracts\\{$name}RepositoryInterface;",
            "use App\Repositories\\{$name}Repository;",
        ];
        $this->insertUseCode($filePath, $content, $useStatements, $insertAfter);
    }

    protected function insertBindCodeProvider($filePath, $bindCode, $content) 
    {
        // Start Insert binding
        $lines = explode("\n", $content);

        $newLines = [];
        $insideRegister = false;
        $bracketCount = 0;

        foreach ($lines as $index => $line) {
            $trimmed = trim($line);

            // Awal method register
            if (str_contains($trimmed, 'public function register()')) {
                $insideRegister = true;
            }

            if ($insideRegister) {
                // Hitung kurung untuk tahu batas method
                $bracketCount += substr_count($line, '{');
                $bracketCount -= substr_count($line, '}');

                // Jika akan menutup method (kurung }), sisipkan sebelum ini
                if ($bracketCount === 0 && trim($trimmed) === '}') {
                    // Sisipkan binding sebelum }
                    $newLines[] = "        " . $bindCode;
                }
            }

            $newLines[] = $line;
        }

        File::put($filePath, implode("\n", $newLines));
        $this->info('Binding added to RepositoryServiceProvider.');
        // End Insert binding
    }

    protected function insertUseCode($filePath, $content, $useStatements, $insertAfter) 
    {
        $content = File::get($filePath);
        $lines = explode("\n", $content);
        $newLines = [];
    
        $inserted = false;
    
        foreach ($lines as $index => $line) {
            $newLines[] = $line;
    
            // Setelah namespace, tambahkan use baru jika belum ditambahkan
            if (trim($line) === $insertAfter && !$inserted) {
                $newLines[] = ""; // Baris kosong setelah namespace
                foreach ($useStatements as $use) {
                    // Cek dulu biar tidak duplikat
                    if (!str_contains($content, $use)) {
                        $newLines[] = $use;
                    }
                }
                $inserted = true;
            }
        }
    
        File::put($filePath, implode("\n", $newLines));
    
        $this->info('Use statements inserted successfully.');
    }
}