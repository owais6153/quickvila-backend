<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\StoreSetting;
use App\Models\StoreCategory;
use App\Models\User;
use Hash;

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
            'manage_able' => true,
            'user_id' => 1,
            'status' => Published(),
            'type' => 'default',
            'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
        ]);

        StoreSetting::create([
            'price' => 0,
            'radius' => 10,
            'store_id' => $store1->id
        ]);
        StoreSetting::create([
            'price' => 0,
            'radius' => 10,
            'store_id' => $store2->id
        ]);



        $store3 = Store::create([
            'name' => 'Bismillah Grocery',
            'url' => 'https://bismillahgrocery.ca/',
            'address' => 'Karachi',
            'latitude' => 23,
            'longitude' => 34,
            'is_featured' => 1,
            'logo' => 'https://bismillahgrocery.ca/wp-content/uploads/2020/08/BMAG-150x150-1.png',
            'cover' => 'https://bismillahgrocery.ca/wp-content/uploads/2020/08/rev-slider_h3-2.jpg',
            'manage_able' => true,
            'user_id' => 1,
            'status' => Published(),
            'type' => 'default',
            'description' => "Buy natural, sustainable and chemicalfree products from trusted brands all around the globe."
        ]);

        StoreSetting::create([
            'price' => 0,
            'radius' => 10,
            'store_id' => $store3->id
        ]);


        $store3->categories()->attach([1], ['type' => 'store']);

        $store1->categories()->attach([1], ['type' => 'store']);
        $store2->categories()->attach([2], ['type' => 'store']);


        $user = new User();
        $user->name =  $store1->name;
        $user->first_name =  $store1->name;
        $user->last_name =  $store1->name;
        $user->password =  Hash::make('storepwd123');
        $user->email = 'nike@quickvila.com';
        $user->email_verified_at = date("Y-m-d", time());
        $user->phone_verified_at = date("Y-m-d", time());
        $user->save();
        $user->assign(Store());
        Store::where('id', $store1->id)->update([
            'owner_id' => $user->id
        ]);



        $user = new User();
        $user->name =  $store2->name;
        $user->first_name =  $store2->name;
        $user->last_name =  $store2->name;
        $user->password =  Hash::make('storepwd123');
        $user->email = 'mart@quickvila.com';
        $user->email_verified_at = date("Y-m-d", time());
        $user->phone_verified_at = date("Y-m-d", time());
        $user->save();
        $user->assign(Store());
        Store::where('id', $store2->id)->update([
            'owner_id' => $user->id
        ]);


        $user = new User();
        $user->name =  $store3->name;
        $user->first_name =  $store3->name;
        $user->last_name =  $store3->name;
        $user->password =  Hash::make('storepwd123');
        $user->email = 'bismillahgrocery@quickvila.com';
        $user->email_verified_at = date("Y-m-d", time());
        $user->phone_verified_at = date("Y-m-d", time());
        $user->save();
        $user->assign(Store());
        Store::where('id', $store3->id)->update([
            'owner_id' => $user->id
        ]);
    }
}
