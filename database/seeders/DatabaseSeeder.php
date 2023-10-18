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

        $user = new User();
        $user->name = "Supper Admin";
        $user->username = "Supper Admin";
        $user->email = "farmangel@farm-angel.com";
        $user->password = Hash::make('123456789abc');
        $user->phone_number = "";
        $user->user_type = "super_admin";
        $user->email_verified_at = "";
        $user->save();

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
