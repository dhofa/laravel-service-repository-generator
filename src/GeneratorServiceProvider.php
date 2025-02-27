<?php

namespace Moonz\Generator;

use Illuminate\Support\ServiceProvider;
use Moonz\Generator\Commands\GenerateServiceRepository;

class GeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateServiceRepository::class,
            ]);
        }
    }
}