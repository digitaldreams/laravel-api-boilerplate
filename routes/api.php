<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {

    Route::apiResource('users', 'UserController');

    Route::group(['prefix' => 'permissions'], function ($api) {
        Route::post('sync/{permission}', 'PermissionController@roleSync')->name('permissions.roles.sync');
        Route::post('attach/{permission}', 'PermissionController@roleAttach')->name('permissions.roles.attach');
        Route::post('detach/{permission}', 'PermissionController@roleDetach')->name('permissions.roles.detach');
    });
    Route::apiResource('permissions', 'PermissionController');

    Route::group(['prefix' => 'roles'], function ($api) {
        Route::post('sync/{role}', 'RoleController@permissionSync')->name('roles.permissions.sync');
        Route::post('attach/{role}', 'RoleController@permissionAttach')->name('roles.permissions.attach');
        Route::post('detach/{role}', 'RoleController@permissionDetach')->name('roles.permissions.detach');
    });
    Route::apiResource('roles', 'RoleController');


    Route::group(['prefix' => 'profile'], function () {
        Route::get('', ['as' => 'profile.show', 'uses' => 'ProfileController@show']);
        Route::put('', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);

    });

    Route::group(['prefix' => 'password'], function ($api) {
        Route::post('change', ['as' => 'password.change', 'uses' => 'Auth\PasswordController@change']);
    });
});