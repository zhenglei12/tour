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
    Route::get("user/permission", "UserControllers@permission");
});

Route::group(['namespace' => 'Admin', 'middleware' => ['auth:sanctum']], function () {
    Route::post("role/list", "RoleControllers@list")->name('role-list');
    Route::get("role/detail", "RoleControllers@detail")->name('role-detail');
    Route::post("role/add", "RoleControllers@add")->name('role-add');
    Route::post("role/update", "RoleControllers@update")->name('role-update');
    Route::post("role/delete", "RoleControllers@delete")->name('role-delete');
    Route::post("role/permission", "RoleControllers@permission")->name('role-permission');
    Route::post("role/add/permission", "RoleControllers@addPermission")->name('role-add.permission');

    Route::post("permission/list", "PermissionControllers@list")->name('permission-list');
    Route::get("permission/detail", "PermissionControllers@detail")->name('permission-detail');
    Route::post("permission/add", "PermissionControllers@add")->name('permission-add');
    Route::post("permission/update", "PermissionControllers@update")->name('permission-update');
    Route::post("permission/delete", "PermissionControllers@delete")->name('permission-delete');
    Route::post("permission/all", "PermissionControllers@all")->name('permission-all');


    Route::post("user/list", "UserControllers@list")->name('user-list');
    Route::get("user/personal/detail", "UserControllers@personalDetail")->name('user-personal.detail');
    Route::post("user/update", "UserControllers@update")->name('user-update');
    Route::post("user/add", "UserControllers@add")->name('user-add');
    Route::post("user/delete", "UserControllers@delete")->name('user-delete');
    Route::post("user/role/list", "UserControllers@roleList")->name('user-role.list');
    Route::post("user/add/role", "UserControllers@addRole")->name('user-add.role');


    Route::post("trip/list", "TripControllers@list")->name('trip-list');
    Route::post("trip/detail", "TripControllers@detail")->name('trip-detail');
    Route::post("trip/update", "TripControllers@update")->name('trip-update');
    Route::post("trip/add", "TripControllers@add")->name('trip-add');
    Route::post("trip/delete", "TripControllers@delete")->name('trip-delete');


    Route::post("order/list", "OrderControllers@list")->name('trip-list');
    Route::get("order/detail", "OrderControllers@detail")->name('trip-detail');
    Route::post("order/add", "OrderControllers@add")->name('trip-add');
    Route::post("order/update", "OrderControllers@update")->name('trip-update');
    Route::post("order/audit", "OrderControllers@audit")->name('trip-audit');
    Route::post("order/statistics", "OrderControllers@statistics")->name('trip-statistics');

});

