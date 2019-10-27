<?php

namespace TechlifyInc\LaravelSaasUser;

use Illuminate\Support\ServiceProvider;
use TechlifyInc\LaravelSaasUser\Middleware\LaravelSaasUserEnforcePermission;

/**
 * Description of RbacServiceProvider
 *
 * @author 
 */
class LaravelSaasUserServiceProvider extends ServiceProvider
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
        
        $router->aliasMiddleware('LaravelSaasUserEnforcePermission', LaravelSaasUserEnforcePermission::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LaravelSaasUser::class, function ()
        {
            return new LaravelSaasUser();
        });

        $this->app->alias(LaravelSaasUser::class, 'laravel-rbac');
    }

}
