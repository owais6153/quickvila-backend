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
        $adminPermissions = config('trikaro.permissions.all');

        foreach ($adminPermissions as $adminPer) {
            foreach ($adminPer as $adminPermission) {
                Bouncer::allow('Admin')->to($adminPermission);
            }
        }

        $managerPermissions = config('trikaro.permissions.manager');

        foreach ($managerPermissions as $managerPer) {
            foreach ($managerPer as $managerPermission) {
                Bouncer::allow('Manager')->to($managerPermission);
            }
        }



        Bouncer::allow('Customer')->to('');
    }
}
