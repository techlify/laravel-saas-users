<?php

namespace TechlifyInc\LaravelSaasUser;

use Illuminate\Support\Facades\Facade;

/**
 * Description of LaravelSaasUserFacade
 *
 * @author 
 */
class LaravelSaasUserFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'laravel-rbac';
    }

}
