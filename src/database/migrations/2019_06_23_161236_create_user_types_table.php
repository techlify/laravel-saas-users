<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class CreateUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('code')
                ->unique();
            $table->timestamps();
        });

        $data = [
            ["id" => 1, "title" => "System Admin", "code" => "system-admin"],
            ["id" => 2, "title" => "Client Admin", "code" => "client-admin"],
            ["id" => 3, "title" => "Client User", "code" => "client-user"],
        ];

        DB::table('user_types')
            ->insert($data);

        Schema::table((new User())->getTable(), function($table) {
            $table->unsignedBigInteger("user_type_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table((new User())->getTable(), function($table) {
            $table->dropColumn('user_type_id');
        });

        Schema::dropIfExists('user_types');
    }
}
