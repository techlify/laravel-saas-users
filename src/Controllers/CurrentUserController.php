<?php
namespace TechlifyInc\LaravelRbac\Controllers;

use App\Http\Controllers\Controller;
use App\User;

class CurrentUserController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\CaloricSetting  $caloricSetting
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $id = auth()->id();
        $user = \Illuminate\Support\Facades\Auth::user();

        if (null == $user) {
            return ["user" => new User()];
        }

        $permissions = new \Illuminate\Database\Eloquent\Collection();
        if (!count($user->roles)) {
            foreach ($user->roles as $role) {
                $permissions = $permissions->merge($role->permissions);
            }
        }

        $user->permissions = $permissions->unique();

        return ["user" => $user, "id" => $id];
    }
}
