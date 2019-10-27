<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\User;

class SetupUserClientId extends Migration
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
            $table->bigInteger("client_id")
                ->unsigned()
                ->nullable()
                ->default(null);
        });
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
            $table->dropColumn('client_id');
        });
    }
}
