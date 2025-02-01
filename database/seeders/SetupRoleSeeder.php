<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetupRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $roles = [
                [
                    'name' => config('permission.attributes.role.super_admin'),
                    'guard_name' => config('permission.default_guard_name'),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => config('permission.attributes.role.admin_approval'),
                    'guard_name' => config('permission.default_guard_name'),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => config('permission.attributes.role.user'),
                    'guard_name' => config('permission.default_guard_name'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            Role::insert($roles);

            $permissions = [
                [
                    'name' => config('permission.attributes.permission.approve_expense'),
                    'guard_name' => config('permission.default_guard_name'),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => config('permission.attributes.permission.manage_user'),
                    'guard_name' => config('permission.default_guard_name'),
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => config('permission.attributes.permission.create_expense'),
                    'guard_name' => config('permission.default_guard_name'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];

            Permission::insert($permissions);

            $super_admin = Role::where('name', config('permission.attributes.role.super_admin'))->first();
            $super_admin->givePermissionTo(Permission::all());

            $admin_approval = Role::where('name', config('permission.attributes.role.admin_approval'))->first();
            $admin_approval->givePermissionTo(Permission::whereIn('name', [
                config('permission.attributes.permission.approve_expense'),
                config('permission.attributes.permission.create_expense')
            ])->get());

            $user = Role::where('name', config('permission.attributes.role.user'))->first();
            $user->givePermissionTo(Permission::where('name', config('permission.attributes.permission.create_expense'))->first());

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
