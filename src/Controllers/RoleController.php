<?php
namespace TechlifyInc\LaravelRbac\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TechlifyInc\LaravelRbac\Models\Role;
use TechlifyInc\LaravelRbac\Models\Permission;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->hasPermission("role_read")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }

        if (request("loadPermissions") && true == request("loadPermissions")) {
            $roles = Role::orderBy("label")->with("permissions")->get();
        } else {
            $roles = Role::orderBy("label")->get();
        }

        return array("items" => $roles, "success" => true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission("role_create")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }

        $this->validate(request(), [
            "slug"  => "required",
            "label" => "required",
        ]);

        $role = new Role();
        $role->slug = request('slug');
        $role->label = request('label');

        if (!$role->save()) {
            return response()->json(['error' => "Failed to save the role. "], 422);
        }

        return array("item" => $role, "message" => "Successfully added the new role. ");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->hasPermission("role_update")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }

        $this->validate(request(), [
            "slug"  => "required",
            "label" => "required",
        ]);

        $role->slug = request('slug');
        $role->label = request('label');

        if (!$role->save()) {
            return response()->json(['error' => "Failed to save the role. "], 422);
        }

        return array("item" => $role, "message" => "Successfully updated the role. ");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasPermission("role_delete")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }
    }

    /**
     * Adds a permission to a role
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addPermission(Role $role, Permission $permission)
    {
        if (!auth()->user()->hasPermission("role_permission_add")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }

        if ($role->hasPermission($permission)) {
            return array("success" => false, "message" => "The Role already has the specified permission. ");
        }

        $role->givePermission($permission);
        return array("success" => true, "message" => "Successfully added the permission to the role");
    }

    /**
     * Removes a permission from a role
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removePermission(Role $role, Permission $permission)
    {
        if (!auth()->user()->hasPermission("role_permission_remove")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }

        if ($role->hasPermission($permission->slug)) {
            $role->removePermission($permission);
            return array("success" => true, "message" => "Successfully removed the permission from the Role. ");
        }

        return array("success" => false, "message" => "The Role doesn't have the specified permission, hence it cannot be removed. ");
    }
}
