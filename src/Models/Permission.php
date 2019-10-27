<?php

namespace TechlifyInc\LaravelRbac\Models;

use Illuminate\Database\Eloquent\Model;

use TechlifyInc\LaravelRbac\Models\Role;

class Permission extends Model
{
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    
}
