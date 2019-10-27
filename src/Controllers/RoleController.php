<?php

namespace Techlify\LaravelSaasUser\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Techlify\LaravelSaasUser\Entities\Role;
use Techlify\LaravelSaasUser\Entities\Permission;
use Modules\Module\Entities\Module;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filters = request([
            'module_code', 
            'client_id'
        ]);
        $roles = Role::filter($filters)
            ->orderBy("label")
            ->with('module')
            ->with('creator');

        if (request("loadPermissions") && true == request("loadPermissions")) {
            $roles = $roles->with("permissions");
        }

        return ["data" => $roles->get()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            "label" => "required|string",
            'module_code' => 'exists:modules,code',
        ]);

        $role = new Role();
        $role->label = request('label');
        $role->description = request('description', '');
        $role->is_editable = true;
        $role->client_id = auth()->user()->client_id;
        $role->creator_id = auth()->id();
        $role->slug = $role->client_id . "-" . strtolower($role->label);

        if (request('module_code')) {
            $module = Module::where('code', request('module_code'))
                ->first();

            if ($module) {
                $role->module_id = $module->id;
            }
        }

        if (!$role->save()) {
            return response()->json(['error' => "Failed to save the role. "], 422);
        }

        return ["item" => $role];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            "label" => "required|string",
        ]);

        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => "Invalid Role data sent. "], 422);
        }

        $role->label = request('label');
        $role->description = request('description', '');
        $role->slug = $role->client_id . "-" . strtolower($role->label);

        if (!$role->save()) {
            return response()->json(['error' => "Failed to save the role. "], 422);
        }

        return ["item" => $role];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => "Invalid Role data sent. "], 422);
        }

        if (!$role->delete()) {
            return response()->json(['error' => "Failed to delete the role. "], 422);
        }

        return ["item" => $role];
    }

    /**
     * Adds a permission to a role
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addPermission(Role $role, Permission $permission)
    {
        if ($role->hasPermission($permission)) {
            return response()->json(['error' => "The Role already has the specified permission. "], 422);
        }

        $role->givePermission($permission);
        $role->load('permissions');

        return ["role" => $role];
    }

    /**
     * Removes a permission from a role
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removePermission(Role $role, Permission $permission)
    {
        if (!$role->hasPermission($permission->slug)) {
            return response()->json(['error' => "The Role does not have the specified permission. "], 422);
        }

        $role->removePermission($permission);
        $role->load('permissions');
        return ["role" => $role];
    }
}
