<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            StoreCategorySeeder::class,
            StoreSeeder::class,
            ProductSeeder::class,
            TestimonialAndVideoSeeder::class,
        ]);
    }
}
