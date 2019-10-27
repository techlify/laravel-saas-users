<?php

Route::group(['prefix' => 'api', 'middleware' => 'api'], function()
{
    Route::resource("roles", "\TechlifyInc\LaravelRbac\Controllers\RoleController");
    Route::patch("roles/{role}/permissions/{permission}/add", "\TechlifyInc\LaravelRbac\Controllers\RoleController@addPermission");
    Route::patch("roles/{role}/permissions/{permission}/remove", "\TechlifyInc\LaravelRbac\Controllers\RoleController@removePermission");

    Route::resource("permissions", "\TechlifyInc\LaravelRbac\Controllers\PermissionController");
});

Route::group(['prefix' => 'api', 'middleware' => 'api'], function()
{
    Route::resource("users", "TechlifyInc\LaravelRbac\Controllers\UserController");
    Route::post('/user/logout', "TechlifyInc\LaravelRbac\Controllers\SessionController@destroy");
    Route::get('/user/current', "TechlifyInc\LaravelRbac\Controllers\CurrentUserController@show");
    Route::patch("user/current/update-password", "TechlifyInc\LaravelRbac\Controllers\UserController@user_password_change_own");
    
    Route::patch("users/{id}/enable", "TechlifyInc\LaravelRbac\Controllers\UserController@enable");
    Route::patch("users/{id}/disable", "TechlifyInc\LaravelRbac\Controllers\UserController@disable");
});