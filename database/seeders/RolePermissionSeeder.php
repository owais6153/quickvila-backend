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
        $adminPermissions = config('trikaro.permissions.admin');

        foreach ($adminPermissions as $adminPer) {
            foreach ($adminPer as $adminPermission) {
                Bouncer::allow(Admin())->to($adminPermission);
            }
        }

        $managerPermissions = config('trikaro.permissions.manager');

        foreach ($managerPermissions as $managerPer) {
            foreach ($managerPer as $managerPermission) {
                Bouncer::allow(Manager())->to($managerPermission);
            }
        }

        $storePermissions = config('trikaro.permissions.store');
        foreach ($storePermissions as $storePer) {
            foreach ($storePer as $storePermission) {
                Bouncer::allow(Store())->to($storePermission);
            }
        }
    }
}
