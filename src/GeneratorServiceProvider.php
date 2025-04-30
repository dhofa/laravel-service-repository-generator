<?php

namespace Moonz\Generator;

use Illuminate\Support\ServiceProvider;
use Moonz\Generator\Commands\GenerateServiceRepository;
use Moonz\Generator\Commands\InitServiceRepository;

class GeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InitServiceRepository::class,
                GenerateServiceRepository::class,
            ]);
        }
    }
}