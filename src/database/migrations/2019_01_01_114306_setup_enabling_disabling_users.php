<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use TechlifyInc\LaravelRbac\Models\Permission;
use App\User;

class SetupEnablingDisablingUsers extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Lets add an enabled field to the users table */
        Schema::table((new User())->getTable(), function($table) {
            $table->boolean("enabled")->default(true);
        });

        /* Add the enable/disable users permissions */
        $models = [
            ['slug' => 'user_enable', 'label' => "User: Enable"],
            ['slug' => 'user_disable', 'label' => "User: Disable"]
        ];

        DB::table((new Permission())->getTable())->insert($models);
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
        Schema::table((new User())->getTable(), function($table) {
            $table->dropColumn('enabled');
        });

        $slugs = [
            "user_enable",
            "user_disable"
        ];
        DB::table((new Permission())->getTable())
            ->whereIn("slug", $slugs)
            ->delete();
    }
}
