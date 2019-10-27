<?php

use Illuminate\Database\Migrations\Migration;
use TechlifyInc\LaravelRbac\Models\Permission;

class CreateLaravelUserManagementPermissions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Call the down method to remove any of these permissions */
        $this->down();

        $models = [
            ['slug' => 'user_create', 'label' => "User: Add"],
            ['slug' => 'user_read', 'label' => "User: View"],
            ['slug' => 'user_update', 'label' => "User: Edit"],
            ['slug' => 'user_delete', 'label' => "User: Delete"],
            ['slug' => 'role_create', 'label' => "Role: Add"],
            ['slug' => 'role_read', 'label' => "Role: View"],
            ['slug' => 'role_update', 'label' => "Role: Edit"],
            ['slug' => 'role_delete', 'label' => "Role: Delete"],
            ['slug' => 'role_permission_add', 'label' => "Role Permission: Delete"],
            ['slug' => 'role_permission_remove', 'label' => "Role Permission: Delete"],
        ];

        $model = new Permission;
        $table = $model->getTable();
        DB::table($table)->insert($models);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * 
     * @todo
     */
    public function down()
    {
        $model = new Permission;
        $table = $model->getTable();

        $slugs = [
            "user_create",
            "user_read",
            "user_update",
            "user_delete",
            "role_create",
            "role_read",
            "role_update",
            "role_delete",
            "role_permission_add",
            "role_permission_remove",
        ];
        DB::table($table)
            ->whereIn("slug", $slugs)
            ->delete();
    }
}
