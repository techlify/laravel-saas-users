<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('label')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_role', function(Blueprint $table) {
            $table->bigInteger('permission_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('role_user', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('role_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
    }
}
