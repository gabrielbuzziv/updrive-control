<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('accounts');
});

Route::get('accounts/update-permissions', 'AccountController@updatePermissions');
Route::get('accounts/{account}/details', 'AccountController@details');
Route::get('accounts/{account}/backup', 'AccountController@backup');
Route::get('accounts/{account}/settings', 'AccountController@settings');
Route::patch('accounts/{account}/modules', 'AccountController@updateModules');
Route::get('accounts/{account}/toggle', 'AccountController@toggleActive');
Route::get('accounts', 'AccountController@index');
Route::get('accounts/create', 'AccountController@create');
Route::get('accounts/{account}', 'AccountController@show');
Route::get('accounts/{account}/edit', 'AccountController@edit');
Route::post('accounts', 'AccountController@store');
Route::patch('accounts/{account}', 'AccountController@update');
Route::get('accounts/{account}/destroy', 'AccountController@destroy');

Route::get('accounts/{account}/backup/{backup}/download', 'AccountBackupController@download');
Route::get('accounts/{account}/backup/create', 'AccountBackupController@backupDatabase');
Route::get('accounts/{account}/backup/databases', 'AccountBackupController@databases');

Route::patch('accounts/{account}/settings', 'AccountSettingController@update');
Route::get('accounts/{account}/settings/backup', 'AccountSettingController@restoreBackup');
Route::get('accounts/{account}/settings/default', 'AccountSettingController@restoreDefault');

Route::get('users', 'UserController@index');
Route::get('users/create', 'UserController@create');
Route::get('users/{user}/edit', 'UserController@edit');
Route::post('users', 'UserController@store');
Route::patch('users/{user}', 'UserController@update');
Route::delete('users/{user}', 'UserController@destroy');

Route::get('roles', 'RoleController@index');
Route::get('roles/create', 'RoleController@create');
Route::get('roles/{role}/edit', 'RoleController@edit');
Route::post('roles', 'RoleController@store');
Route::patch('roles/{role}', 'RoleController@update');
Route::delete('roles/{role}', 'RoleController@destroy');

Route::get('permissions', 'PermissionController@index');
Route::get('permissions/create', 'PermissionController@create');
Route::get('permissions/{permission}/edit', 'PermissionController@edit');
Route::post('permissions', 'PermissionController@store');
Route::patch('permissions/{permission}', 'PermissionController@update');
Route::delete('permissions/{permission}', 'PermissionController@destroy');

Auth::routes();
