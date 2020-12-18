<?php

namespace Gusmanson\Localtinymce;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         $this->publishes([
            base_path('vendor/tinymce') => public_path('vendor/localtinymce'),
        ], 'public');

        Nova::serving(function (ServingNova $event) {
            Nova::script('localtinymce', __DIR__.'/../dist/js/field.js');
            Nova::style('localtinymce', __DIR__.'/../dist/css/field.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRoutes();
    }

    /**
     * Registers field routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::middleware(['nova'])
             ->prefix('nova-vendor/gusmanson/localtinymce')
             ->group(__DIR__.'/../routes/api.php');
    }
}
