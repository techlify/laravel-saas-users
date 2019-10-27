<?php

namespace Techlify\LaravelSaasUser\Entities;

use Illuminate\Database\Eloquent\Model;

use Techlify\LaravelSaasUser\Entities\Role;

class Permission extends Model
{
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['module_code']) && "" != $filters['module_code']) {
            $query->where("module_code", $filters['module_code']);
        }

        if (isset($filters['sort_by']) && "" != trim($filters['sort_by'])) {
            $sort = explode("|", $filters['sort_by']);
            $query->orderBy($sort[0], $sort[1]);
        }
    }

}
