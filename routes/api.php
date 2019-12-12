<?php

/*
|--------------------------------------------------------------------------
| Laravel Roles API Routes
|--------------------------------------------------------------------------
|
*/

Route::group([
    'as'            => 'laravelroles::',
    'namespace'     => '\Mistery23\LaravelRoles\App\Http\Controllers\Api',
], function () {

    // Roles
    Route::get('/roles', 'RolesController@index');
    Route::get('/roles/{roleId}/permissions', 'RolesController@withPermissions');
    Route::post('/roles', 'RolesController@store');
    Route::put('/roles/{roleId}', 'RolesController@edit');
    Route::delete('/roles/{roleId}', 'RolesController@destroy');
    Route::post('/roles/{roleId}/permissions', 'RolesController@attachPermission');
    Route::delete('/roles/{roleId}/permissions', 'RolesController@detachPermission');
    Route::post('/roles/{roleId}/copy', 'RolesController@copy');
    Route::patch('/roles/{roleId}/do-child', 'RolesController@doChild');
    Route::patch('/roles/{roleId}/do-root', 'RolesController@doRoot');

    // Permissions
    Route::get('/permissions', 'PermissionsController@index');
    Route::post('/permissions', 'PermissionsController@store');
    Route::put('/permissions/{permissionId}', 'PermissionsController@edit');
    Route::delete('/permissions/{permissionId}', 'PermissionsController@destroy');
    Route::patch('/permissions/{permissionId}/do-child', 'PermissionsController@doChild');
    Route::patch('/permissions/{permissionId}/do-root', 'PermissionsController@doRoot');

    //Users
    Route::post('/users/{userId}/roles', 'UsersController@attachRole');
    Route::delete('/users/{userId}/roles', 'UsersController@detachRole');
    Route::post('/users/{userId}/permissions', 'UsersController@attachPermission');
    Route::delete('/users/{userId}/permissions', 'UsersController@detachPermission');
});