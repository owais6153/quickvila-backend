<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\StoreCategory;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = Store::create([
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
        StoreCategory::create([
            'name' => 'Shoes & Sneakers'
        ]);
        StoreCategory::create([
            'name' => 'Caps'
        ]);
        $store->categories()->attach([1], ['type' => 'store']);
    }
}
