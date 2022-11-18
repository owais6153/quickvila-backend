<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\StoreSetting;
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
        $store1 = Store::create([
            'name' => 'Nike',
            'url' => 'https://nike.ca/',
            'address' => 'Karachi',
            'latitude' => 23,
            'longitude' => 34,
            'logo' => 'https://fullstop360.com/blog/wp-content/uploads/2021/11/Nike-Logos-Principles-of-Design.jpg',
            'cover' => 'https://wallpaperaccess.com/full/545360.jpg',
            'manage_able' => false,
            'user_id' => 1,
            'status' => 'published',
            'type' => 'default'
        ]);
        $store2 = Store::create([
            'name' => 'Mart',
            'url' => 'https://mart.ca/',
            'address' => 'Karachi',
            'latitude' => 23,
            'longitude' => 34,
            'logo' => 'images/no-image.png',
            'cover' => 'images/no-image.png',
            'manage_able' => false,
            'user_id' => 1,
            'status' => 'published',
            'type' => 'default'
        ]);

        StoreSetting::create([
            'price' => 0,
            'radius' => 10,
            'tax' => 0,
            'store_id' => $store1->id
        ]);
        StoreSetting::create([
            'price' => 0,
            'radius' => 10,
            'tax' => 0,
            'store_id' => $store2->id
        ]);
        StoreCategory::create([
            'name' => 'Shoes & Sneakers',
            'user_id' => 1,
        ]);
        StoreCategory::create([
            'name' => 'Caps',
            'user_id' => 1,
        ]);
        $store1->categories()->attach([1], ['type' => 'store']);
        $store2->categories()->attach([1], ['type' => 'store']);
    }
}
