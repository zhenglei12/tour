<?php

use Illuminate\Database\Seeder;

class UserPermissionSeeder extends Seeder
{
    private $permission = [
        ["name" => "user-list", "guard_name" => "admin", "alias" => "用户列表"],
        ["name" => "user-personal.detail", "guard_name" => "admin", "alias" => "用户详情"],
        ["name" => "user-update", "guard_name" => "admin", "alias" => "用户更新"],
        ["name" => "user-add", "guard_name" => "admin", "alias" => "用户添加"],
        ["name" => "user-delete", "guard_name" => "admin", "alias" => "用户删除"],
        ["name" => "user-role.list", "guard_name" => "admin", "alias" => "用户角色"],
        ["name" => "user-add.role", "guard_name" => "admin", "alias" => "用户角色更新"],

        ["name" => "role-list", "guard_name" => "admin", "alias" => "角色列表"],
        ["name" => "role-detail", "guard_name" => "admin", "alias" => "角色详情"],
        ["name" => "role-add", "guard_name" => "admin", "alias" => "角色添加"],
        ["name" => "role-update", "guard_name" => "admin", "alias" => "角色更新"],
        ["name" => "role-delete", "guard_name" => "admin", "alias" => "角色删除"],
        ["name" => "role-permission", "guard_name" => "admin", "alias" => "角色的权限"],
        ["name" => "role-add.permission", "guard_name" => "admin", "alias" => "角色添加权限"],

        ["name" => "permission-list", "guard_name" => "admin", "alias" => "权限列表"],
        ["name" => "permission-detail", "guard_name" => "admin", "alias" => "权限详情"],
        ["name" => "permission-add", "guard_name" => "admin", "alias" => "权限添加"],
        ["name" => "permission-update", "guard_name" => "admin", "alias" => "权限更新"],
        ["name" => "permission-delete", "guard_name" => "admin", "alias" => "权限删除"],

        ["name" => "trip-list", "guard_name" => "admin", "alias" => "行程列表"],
        ["name" => "trip-detail", "guard_name" => "admin", "alias" => "行程详情"],
        ["name" => "trip-add", "guard_name" => "admin", "alias" => "行程添加"],
        ["name" => "trip-update", "guard_name" => "admin", "alias" => "行程更新"],
        ["name" => "trip-delete", "guard_name" => "admin", "alias" => "行程删除"],

        ["name" => "order-list", "guard_name" => "admin", "alias" => "订单列表"],
        ["name" => "order-detail", "guard_name" => "admin", "alias" => "订单详情"],
        ["name" => "order-add", "guard_name" => "admin", "alias" => "订单添加"],
        ["name" => "order-update", "guard_name" => "admin", "alias" => "订单更新"],
        ["name" => "order-audit", "guard_name" => "admin", "alias" => "订单审核"],
        ["name" => "order-statistics", "guard_name" => "admin", "alias" => "订单统计"],
        ["name" => "order-exports", "guard_name" => "admin", "alias" => "订单导出"],

        ["name" => "agent-list", "guard_name" => "admin", "alias" => "代理列表"],
        ["name" => "agent-order.list", "guard_name" => "admin", "alias" => "代理订单列表"],
        ["name" => "agent-detail", "guard_name" => "admin", "alias" => "代理详情"],
        ["name" => "agent-update", "guard_name" => "admin", "alias" => "代理更新"],
        ["name" => "agent-add", "guard_name" => "admin", "alias" => "代理添加"],
        ["name" => "agent-delete", "guard_name" => "admin", "alias" => "代理删除"],

        ["name" => "resources-import", "guard_name" => "admin", "alias" => "资源导入"],
        ["name" => "resources-list", "guard_name" => "admin", "alias" => "资源列表"],
        ["name" => "resources-detail", "guard_name" => "admin", "alias" => "资源详情"],
        ["name" => "resources-update", "guard_name" => "admin", "alias" => "资源更新"],
        ["name" => "resources-add", "guard_name" => "admin", "alias" => "资源添加"],
        ["name" => "resources-distribute", "guard_name" => "admin", "alias" => "资源分配"],
        ["name" => "resources-delete", "guard_name" => "admin", "alias" => "资源删除"],
        ["name" => "resources-distribute.list", "guard_name" => "admin", "alias" => "历史资源列表"],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdmin();
        $this->createRole();
        $this->createPermission();
        $this->associateRolePermissions();
    }

    public function createAdmin()
    {
        \App\Http\Model\User::truncate();
        \App\Http\Model\User::create([
            'name' => 'admin',
            'email' => 'admin@qq.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin'), // secret
        ]);
    }

    public function createRole()
    {
        \App\Http\Model\Role::query()->delete();
        \App\Http\Model\Role::create([
            'name' => 'admin',
            'guard_name' => 'admin'
        ]);
    }

    public function createPermission()
    {
        \App\Http\Model\Permission::query()->delete();
        foreach ($this->permission as $permission) {
            \App\Http\Model\Permission::create($permission);
        }
    }

    private function associateRolePermissions()
    {
        $role = \App\Http\Model\Role::first();

        \App\Http\Model\User::first()->assignRole($role->name);

        foreach ($this->permission as $permission) {
            $role->givePermissionTo($permission['name']);
        }
    }
}
