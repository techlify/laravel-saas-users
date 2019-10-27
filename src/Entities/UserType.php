<?php

namespace Techlify\LaravelSaasUser\Entities;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{

    const BIS_ADMIN = 1;
    const CLIENT_ADMIN = 2;
    const CLIENT_USER = 3;

    public static function getUserTypeIdFromCode($code)
    {
        switch (strtolower($code)) {
            case "bis-admin":
                return self::BIS_ADMIN;
            case "client-admin":
                return self::CLIENT_ADMIN;
            default:
            case "client-user":
                return self::CLIENT_USER;
        }

        return null;
    }
}
