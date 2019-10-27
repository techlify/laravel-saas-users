<?php
namespace TechlifyInc\LaravelRbac\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use TechlifyInc\LaravelRbac\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->hasPermission("user_read")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }
        
        $filters = request(['name', 'email', 'enabled', 'role_ids', 'sort_by', 'num_items']);

        $users = User::filter($filters)
            ->with('roles');
            //->get();

        return ["items" => $users->get(), "sql" => $users->toSql()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission("user_create")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }

        $this->validate(request(), [
            "name"     => "required",
            "email"    => "required|email",
            "password" => "required",
        ]);

        $user = new User();
        $user->name = request('name');
        $user->email = request('email');
        $user->password = bcrypt(request('password'));
        if (!$user->save()) {
            return response()->json(['error' => "Failed to add the new user. "], 422);
        }

        $roles = request('roles') ?: [];
        if (is_array($roles)) {
            foreach ($roles as $rid => $selected) {
                if (!$selected) {
                    continue;
                }
                $role = Role::find($rid);
                $user->assignRole($role->slug);
            }
        }

        return ["item" => $user];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (!auth()->user()->hasPermission("user_read")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }

        return ["item" => $user];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->hasPermission("user_update")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }

        $this->validate(request(), [
            "name"  => "required",
            "email" => "required",
        ]);

        $user->name = request('name');
        $user->email = request('email');

        if (request('password') && "" != trim(request("password")) && null != request("password")) {
            $user->password = bcrypt(request('password'));
        }

        if (!$user->save()) {
            return response()->json(['error' => "Failed to add the new user. "], 422);
        }

        $user->roles()->detach();

        $roles = request('roles') ?: [];
        if (is_array($roles)) {
            foreach ($roles as $rid => $selected) {
                if (!$selected) {
                    continue;
                }
                $role = Role::find($rid);
                $user->assignRole($role->slug);
            }
        }

        return ["item" => $user];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->hasPermission("user_delete")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }

        /* Delete all related objects */
        $user->roles()->detach();

        /* Delete the user object */
        $deleted = $user->delete();

        return ["item" => $user, "success" => $deleted];
    }

    /**
     * Change the the current user password
     * 
     * @todo Check if the logged in user is the same as the user changing the password
     * 
     */
    public function user_password_change_own()
    {
        $this->validate(request(), [
            "newPassword"     => "required",
            "currentPassword" => "required",
        ]);

        $user = User::find(auth()->id());

        /* Check if the current password entered is the actual current password */
        if (!Hash::check(request('currentPassword'), $user->password)) {
            return response()->json(['error' => "Invalid current password entered. "], 422);
        }

        $user->password = bcrypt(request('newPassword'));

        if (!$user->save()) {
            return response()->json(['error' => "Failed to add the new user. "], 422);
        }

        return ["item" => $user, "success" => true];
    }

    /**
     * Enable the user account
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enable($id)
    {
        if (!auth()->user()->hasPermission("user_enable")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => "No such user exists. "], 422);
        }

        $user->enabled = true;
        if (!$user->save()) {
            return response()->json(['error' => "Failed to enable the user. "], 422);
        }

        return ["item" => $user];
    }

    /**
     * Disable the user account
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disable($id)
    {
        if (!auth()->user()->hasPermission("user_disable")) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 401);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => "No such user exists. "], 422);
        }

        $user->enabled = false;
        if (!$user->save()) {
            return response()->json(['error' => "Failed to disable the user. "], 422);
        }

        return ["item" => $user];
    }
}
