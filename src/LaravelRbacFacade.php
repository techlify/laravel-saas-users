<?php

namespace TechlifyInc\LaravelRbac;

use Illuminate\Support\Facades\Facade;

/**
 * Description of LaravelRbacFacade
 *
 * @author 
 */
class LaravelRbacFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'laravel-rbac';
    }

}
