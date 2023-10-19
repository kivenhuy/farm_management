<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //\App\Models\User::factory(10)->create();

        // supper admin
        $admin = new User();
        $admin->name = "Supper Admin";
        $admin->username = "farmangel";
        $admin->email = "farmangel@farm-angel.com";
        $admin->password = Hash::make('123456789abc');
        $admin->phone_number = "";
        $admin->user_type = "super_admin";
        $admin->email_verified_at = "";
        $admin->save();

        // farmer
        $user = new User();
        $ser->name = "Farmer";
        $user->username = "farmer";
        $user->email = "farmer@farm-angel.com";
        $user->password = Hash::make('12345678');
        $user->phone_number = "";
        $user->user_type = "farmer";
        $user->email_verified_at = "";
        $user->save();
u

        // \App\Models\User::factory()->create([
        //     'name' => 'Supper Admin',
        //     'username' => 'farmangel',
        //     'email' => 'farmangel@farm-angel.com',
        //     'password' => Hash::make('123456789abc'),
        //     'phone_number' => '',
        //     'user_type'=> 'super_admin',
        // ]);
    }
}
