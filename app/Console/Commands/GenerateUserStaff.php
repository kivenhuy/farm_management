<?php

namespace App\Console\Commands;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenerateUserStaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-user-staff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // already run in server
        return null;

        $faker = \Faker\Factory::create();
        $firstNameArr = ['huy', 'khang', 'bao', 'phuc', 'anh', 'khoa', 'phat', 'dat', 'khoi'];
        $lastNameArr = ['nguyen', 'tran', 'phan', 'le', 'bui', 'dang'];
        
        for ($i=1; $i<=31; $i++) {
            $firstNameKey = array_rand($firstNameArr);
            $lastfirstNameKey = array_rand($lastNameArr);
            $phoneNumber = str_replace('+', '0', fake()->unique()->e164PhoneNumber());;

            // Username
            $user = new User();
            $user->name = $firstNameArr[$firstNameKey ] . ' ' . $lastNameArr[$lastfirstNameKey];
            $user->user_type = 'staff';
            $user->username = $firstNameArr[$firstNameKey ] . ' ' . $lastNameArr[$lastfirstNameKey] . rand(10,99);
            $user->email = $firstNameArr[$firstNameKey ] . $lastNameArr[$lastfirstNameKey] . rand(10,99) . '@gmail.com';
            $user->password = Hash::make('12345678');
            $user->phone_number = $phoneNumber;
            $user->save();

            // Staff
            $firstNameKey = array_rand($firstNameArr);
            $lastfirstNameKey = array_rand($lastNameArr);
            $staff = new Staff();
            $staff->user_id = $user->id;
            $staff->first_name = $firstNameArr[$firstNameKey ];
            $staff->last_name = $lastNameArr[$lastfirstNameKey];
            $staff->gender = 'Male';
            $staff->email = $firstNameArr[$firstNameKey ] . $lastNameArr[$lastfirstNameKey] . rand(10,99) . '@gmail.com';
            $staff->phone_number = $phoneNumber;
            $staff->status = 'active';
            $staff->save();
        }
    }
}
