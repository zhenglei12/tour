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
    Route::get("order/exports1", "OrderControllers@exports");
    Route::post("public/agent/list", "AgentControllers@list");
    Route::post("public/trip/list", "TripControllers@list");
    Route::post("pub/role/user_list", "RoleControllers@userList");
});

Route::group(['namespace' => 'Admin', 'middleware' => 'auth:sanctum',], function () {
    Route::get("user/detail", "UserControllers@detail");
    Route::get("user/permission", "UserControllers@permission");
    Route::post("auth/logout", "UserControllers@logout");
    Route::post("permission/all", "PermissionControllers@all");
    Route::post("role/all", "RoleControllers@all");
});

Route::group(['namespace' => 'Admin', 'middleware' => ['auth:sanctum', 'ly.permission']], function () {
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


    Route::post("order/list", "OrderControllers@list")->name('order-list');
    Route::get("order/detail", "OrderControllers@detail")->name('order-detail');
    Route::post("order/delete", "OrderControllers@delete")->name('order-delete');
    Route::post("order/add", "OrderControllers@add")->name('order-add');
    Route::post("order/update", "OrderControllers@update")->name('order-update');
    Route::post("order/audit", "OrderControllers@audit")->name('order-audit');
    Route::post("order/statistics", "OrderControllers@statistics")->name('order-statistics');
    Route::post("order/exports", "OrderControllers@exports")->name('order-exports');
    Route::post("order/edit", "OrderControllers@edit")->name('order-edit');


    Route::post("agent/list", "AgentControllers@list")->name('agent-list');
    Route::post("agent/order/list", "AgentControllers@orderlist")->name('agent-order.list');
    Route::get("agent/detail", "AgentControllers@detail")->name('agent-detail');
    Route::post("agent/update", "AgentControllers@update")->name('agent-update');
    Route::post("agent/add", "AgentControllers@add")->name('agent-add');
    Route::post("agent/delete", "AgentControllers@delete")->name('agent-delete');

    Route::post("resources/import", "ResourcesController@import")->name('resources-import');
    Route::post("resources/list", "ResourcesController@list")->name('resources-list');
    Route::get("resources/detail", "ResourcesController@detail")->name('resources-detail');
    Route::post("resources/update", "ResourcesController@update")->name('resources-update');
    Route::post("resources/add", "ResourcesController@add")->name('resources-add');
    Route::post("resources/delete", "ResourcesController@delete")->name('resources-delete');
    Route::post("resources/distribute", "ResourcesController@distribute")->name('resources-distribute');
    Route::post("resources/distribute/list", "ResourcesController@distributeList")->name('resources-distribute.list');
});

