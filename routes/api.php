<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['namespace' => 'Admin'], function () {
    Route::post("auth/login", "UserControllers@login");
});

Route::group(['namespace' => 'Admin', 'middleware' => 'auth:sanctum',], function () {
    Route::get("user/detail", "UserControllers@detail");
});

Route::group(['namespace' => 'Admin', 'middleware' => 'auth:sanctum',], function () {
    Route::post("role/list", "RoleControllers@list");
    Route::get("role/detail", "RoleControllers@detail");
    Route::post("role/add", "RoleControllers@add");
    Route::post("role/update", "RoleControllers@update");
    Route::post("role/delete", "RoleControllers@delete");

    Route::post("permission/list", "PermissionControllers@list");
    Route::get("permission/detail", "PermissionControllers@detail");
    Route::post("permission/add", "PermissionControllers@add");
    Route::post("permission/update", "PermissionControllers@update");
    Route::post("permission/delete", "PermissionControllers@delete");
    Route::post("permission/all", "PermissionControllers@all");
});

