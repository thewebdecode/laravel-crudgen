<?php

namespace Thewebdecode\Crudgen;

use Illuminate\Support\ServiceProvider;

class CrudgenServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'laravel-crudgen');
        $this->mergeConfigFrom(
            __DIR__.'/config/laravel-crudgen.php', 'laravel-crudgen'
        );

        // Publishes
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/Thewebdecode/laravel-crudgen'),
        ], 'crudgen_public');
        $this->publishes([
            __DIR__.'/config/laravel-crudgen.php' => config_path('laravel-crudgen.php'),
        ], 'crudgen_config');
        $this->publishes([
            __DIR__.'/resources/lang/en/crudgen.php' => base_path('resources/lang/en/crudgen.php'),
            __DIR__.'/resources/lang/hi/crudgen.php' => base_path('resources/lang/hi/crudgen.php'),
        ], 'crudgen_lang');
    }


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 
    }

}
