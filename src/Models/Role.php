<?php

namespace TechlifyInc\LaravelRbac\Models;

use Illuminate\Database\Eloquent\Model;

use TechlifyInc\LaravelRbac\Models\Permission;

class Role extends Model
{

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermission($slug)
    {
        foreach ($this->permissions as $perm)
        {
            if ($perm->slug == $slug)
            {
                return true;
            }
        }

        return false;
    }

    public function givePermission(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }

    public function removePermission(Permission $permission)
    {
        return $this->permissions()->detach($permission);
    }

}
