<?php
namespace TechlifyInc\LaravelRbac\Traits;

use TechlifyInc\LaravelRbac\Models\Role;

/**
 * Description of LaravelRbac
 *
 * @author 
 */
trait LaravelRbac
{

    public function findForPassport($username)
    {
        return $this->where('email', $username)
                ->where("enabled", true)
                ->first();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function attachRole($role)
    {
        return $this->assignRole($role);
    }

    public function assignRole($role)
    {
        $slug = (is_string($role)) ? $role : $role->slug;

        return $this->roles()->save(
                Role::where("slug", $slug)->firstOrFail()
        );
    }

    public function detachRole($role)
    {
        return $this->removeRole($role);
    }

    /**
     * Detach role from user.
     * @param int|Role $role
     */
    public function removeRole($role)
    {
        $slug = (is_string($role)) ? $role : $role->slug;
        $this->roles()->detach(Role::where("slug", $slug)->firstOrFail());
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }

        return !!$role->intersect($this->roles)->count();
    }

    /**
     * Check if user has permission to current operation
     * @param string $slug
     * @return bool
     * 
     * @todo Make this more efficient
     */
    public function hasPermission($permission)
    {
        if (1 == $this->id) {
            return true;
        }
        $slug = (is_string($permission)) ? $permission : $permission->slug;

        foreach ($this->roles as $role) {
            if ($role->hasPermission($slug)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Adding Filtering to users
     * 
     * @param type $query
     * @param type $filters
     * 
     * Filters: 'name', 'email', 'enabled', 'role_ids', 'sort_by', 'num_items'
     */
    public function scopeFilter($query, $filters)
    {
        if (isset($filters['name']) && "" != trim($filters['name'])) {
            $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
        }

        if (isset($filters['email']) && "" != trim($filters['email'])) {
            $query->where('email', 'LIKE', '%' . $filters['email'] . '%');
        }

        if (isset($filters['enabled']) && (true === $filters['enabled'] || "true" === $filters['enabled'])) {
            $query->where('enabled', true);
        }

        if (isset($filters['sort_by']) && "" != trim($filters['sort_by'])) {
            $sort = explode("|", $filters['sort_by']);
            $query->orderBy($sort[0], $sort[1]);
        }

        if (isset($filters['num_items']) && is_numeric($filters['num_items'])) {
            $query->limit($filters['num_items']);
        }
    }
}
