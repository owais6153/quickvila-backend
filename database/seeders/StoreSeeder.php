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
            'status' => Published(),
            'is_featured' => 1,
            'type' => 'default',
            'description' => 'Nike is an American multinational corporation that is engaged in the design, development, manufacturing, and worldwide marketing and sales of footwear, apparel, equipment, accessories, and services.'
        ]);
        $store2 = Store::create([
            'name' => 'Mart',
            'url' => 'https://mart.ca/',
            'address' => 'Karachi',
            'latitude' => 23,
            'longitude' => 34,
            'is_featured' => 1,
            'logo' => 'images/no-image.png',
            'cover' => 'images/no-image.png',
            'manage_able' => false,
            'user_id' => 1,
            'status' => Published(),
            'type' => 'default',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
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
