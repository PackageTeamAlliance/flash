<?php

namespace Pta\Flash;

use File;
use Illuminate\Support\ServiceProvider;

class FlashServiceProvider extends ServiceProvider
{
    
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->bind('Pta\Flash\SessionStore', 'Pta\Flash\LaravelSessionStore');
        
        $this->app->bindShared('flash', function () {
            return $this->app->make('Pta\Flash\FlashNotifier');
        });
        
        $this->publishes([realpath(__DIR__ . '/config/config.php') => config_path('pta-flash.php'), 'config']);
        
        if (File::exists(config_path('pta-flash.php'))) {
            
            $mergePath = config_path('pta-flash.php');
        } 
        else {
            $mergePath = realpath(__DIR__ . '/config/config.php');
        }
        
        $this->mergeConfigFrom($mergePath, 'pta/flash');
    }
    
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->loadViewsFrom(__DIR__ . '/../../views', 'flash');
        
        $this->publishes([__DIR__ . '/../../views' => base_path('resources/views/vendor/flash') ]);
    }
}
