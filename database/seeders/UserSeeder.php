<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Admin';
        $user->password = Hash::make('secret123');
        $user->email = 'admin@trikaro.com';
        $user->email_verified_at = date("Y-m-d", time());
        $user->save();
        $user->assign('Admin');



        $user = new User();
        $user->name = 'Manager';
        $user->password = Hash::make('secret123');
        $user->email = 'manager@trikaro.com';
        $user->email_verified_at = date("Y-m-d", time());
        $user->save();

        $user->assign('Manager');
    }
}
