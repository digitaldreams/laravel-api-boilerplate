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

$router = app('Dingo\Api\Routing\Router');
$router->version('v1', ['namespace' => 'App\Http\Controllers\Api'], function ($api) {
    $api->group(['middleware' => ['auth:api', 'bindings']], function ($api) {

        $api->resource('users', 'UserController');

        $api->group(['prefix' => 'permissions'], function ($api) {
            $api->post('sync/{permission}', 'PermissionController@roleSync')->name('permissions.roles.sync');
            $api->post('attach/{permission}', 'PermissionController@roleAttach')->name('permissions.roles.attach');
            $api->post('detach/{permission}', 'PermissionController@roleDetach')->name('permissions.roles.detach');
        });
        $api->resource('permissions', 'PermissionController');

        $api->group(['prefix' => 'roles'], function ($api) {
            $api->post('sync/{role}', 'RoleController@permissionSync')->name('roles.permissions.sync');
            $api->post('attach/{role}', 'RoleController@permissionAttach')->name('roles.permissions.attach');
            $api->post('detach/{role}', 'RoleController@permissionDetach')->name('roles.permissions.detach');
        });
        $api->resource('roles', 'RoleController');


        $api->group(['prefix' => 'profile'], function ($api) {
            $api->get('', ['as' => 'profile.show', 'uses' => 'ProfileController@show']);
            $api->put('', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);

        });

        $api->group(['prefix' => 'password'], function ($api) {
            $api->post('change', ['as' => 'password.change', 'uses' => 'Auth\PasswordController@change']);
        });
        $api->resource('loans', 'LoanController');

    });
    $api->post('auth/register', 'Auth\RegisterController@store')->name('auth.register');
});
