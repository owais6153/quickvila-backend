<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreCategory;

class StoreCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $cats = [
            [
                'name' => 'Apparel',
                'image' => 'images/apparel.png',
            ],
            [
                'name' => 'Grocery',
                'image' => 'images/grocery.png',
            ],
            [
                'name' => 'Sports',
                'image' => 'images/sports.png',
            ],
            [
                'name' => 'Toys',
                'image' => 'images/toy.png',
            ],
            [
                'name' => 'Vape',
                'image' => 'images/vape.png',
            ],
            [
                'name' => 'Electronics',
                'image' => 'images/electronics.png',
            ],
            [
                'name' => 'Cosmetics',
                'image' => 'images/cosmetic.png',
            ],
            [
                'name' => 'Pharmacy',
                'image' => 'images/pharmacy.png',
            ],
        ];


        foreach($cats as $cat){
            StoreCategory::create([
                'name' => $cat['name'],
                'image' => $cat['image'],
                'user_id' => 1,
            ]);
        }



    }
}
