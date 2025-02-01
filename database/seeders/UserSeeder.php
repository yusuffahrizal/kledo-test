<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        // * Create User with Factory
        // $user = User::factory()->create();

        // * Create Dummy User
        $super_admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $super_admin->assignRole(config('permission.attributes.role.super_admin'));

        $user = User::create([
            'name' => 'User Testing',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole(config('permission.attributes.role.user'));
        DB::commit();
    }
}
