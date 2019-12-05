<?php

/*
|--------------------------------------------------------------------------
| Laravel Roles API Routes
|--------------------------------------------------------------------------
|
*/

Route::group([
    'middleware'    => ['web'],
    'as'            => 'laravelroles::',
    'namespace'     => '\jeremykenedy\LaravelRoles\App\Http\Controllers\Api',
], function () {

    // Roles
    Route::get('/roles', 'RolesController@index');
    Route::post('/roles', 'RolesController@store');
    Route::put('/roles/{roleId}', 'RolesController@edit');
    Route::delete('/roles/{roleId}', 'RolesController@destroy');
    Route::post('/roles/{roleId}/permissions', 'RolesController@attachPermission');
    Route::delete('/roles/{roleId}/permissions', 'RolesController@detachPermission');

    // Permissions
    Route::get('/permissions', 'PermissionsController@index');
    Route::post('/permissions', 'PermissionsController@store');
    Route::put('/permissions/{permissionId}', 'PermissionsController@edit');
    Route::delete('/permissions/{permissionId}', 'PermissionsController@destroy');
    Route::put('/permissions/{permissionId}/do-child', 'PermissionsController@doChild');
    Route::put('/permissions/{permissionId}/do-root', 'PermissionsController@doRoot');

    //Users
    Route::post('/users/{userId}/roles', 'UsersController@attachRole');
    Route::delete('/users/{userId}/roles', 'UsersController@detachRole');
    Route::post('/users/{userId}/permissions', 'UsersController@attachPermission');
    Route::delete('/users/{userId}/permissions', 'UsersController@detachPermission');
});
