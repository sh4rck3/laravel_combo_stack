<?php

namespace Sh4rck3\LaravelComboStack;

use Illuminate\Support\ServiceProvider;

class ComboStackServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallComboStackCommand::class,
            ]);
            $this->publishes([
            __DIR__.'/../config/combo-stack.php' => config_path('combo-stack.php'),
        ], 'combo-stack-config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/combo-stack.php', 'combo-stack'
        );
    }
}
