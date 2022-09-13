<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Store::create([
            'name' => 'Nike',
            'url' => 'https://nike.ca/',
            'address' => 'Karachi',
            'latitude' => 23,
            'longitude' => 34,
            'logo' => 'images/no-image.png',
            'cover' => 'images/no-image.png',
            'manage_able' => false,
            'user_id' => 1
        ]);
    }
}
