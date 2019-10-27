<?php

Route::group(['prefix' => 'api', 'middleware' => 'api'], function()
{
    Route::resource("roles", "\TechlifyInc\LaravelSaasUser\Controllers\RoleController");
    Route::patch("roles/{role}/permissions/{permission}/add", "\TechlifyInc\LaravelSaasUser\Controllers\RoleController@addPermission");
    Route::patch("roles/{role}/permissions/{permission}/remove", "\TechlifyInc\LaravelSaasUser\Controllers\RoleController@removePermission");

    Route::resource("permissions", "\TechlifyInc\LaravelSaasUser\Controllers\PermissionController");
});

Route::group(['prefix' => 'api', 'middleware' => 'api'], function()
{
    Route::resource("users", "TechlifyInc\LaravelSaasUser\Controllers\UserController");
    Route::post('/user/logout', "TechlifyInc\LaravelSaasUser\Controllers\SessionController@destroy");
    Route::get('/user/current', "TechlifyInc\LaravelSaasUser\Controllers\CurrentUserController@show");
    Route::patch("user/current/update-password", "TechlifyInc\LaravelSaasUser\Controllers\UserController@user_password_change_own");
    
    Route::patch("users/{id}/enable", "TechlifyInc\LaravelSaasUser\Controllers\UserController@enable");
    Route::patch("users/{id}/disable", "TechlifyInc\LaravelSaasUser\Controllers\UserController@disable");
});