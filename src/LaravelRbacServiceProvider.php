<?php

namespace TechlifyInc\LaravelRbac;

use Illuminate\Support\ServiceProvider;
use TechlifyInc\LaravelRbac\Middleware\LaravelRbacEnforcePermission;

/**
 * Description of RbacServiceProvider
 *
 * @author 
 */
class LaravelRbacServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        
        $router->aliasMiddleware('LaravelRbacEnforcePermission', LaravelRbacEnforcePermission::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LaravelRbac::class, function ()
        {
            return new LaravelRbac();
        });

        $this->app->alias(LaravelRbac::class, 'laravel-rbac');
    }

}
