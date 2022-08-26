<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Bouncer;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = config('trikaro.permissions');

        foreach($permissions as $per){
           foreach($per as $permission){
               Bouncer::allow('Admin')->to($permission);
           }
        }

        Bouncer::allow('Manager')->to('');
        Bouncer::allow('Customer')->to('');
    }
}
