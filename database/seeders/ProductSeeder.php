<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Variation;
use App\Models\Product;
use App\Models\Review;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Product::create([
            'name' => 'Watch',
            'price' => 250,
            'price_to_display' => 260,
            'short_description' => 'Good Bag',
            'description' => 'Good watch',
            'store_id' => 2,
            'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=989&q=80',
            'manage_able' => true,
            'user_id' => 1,
            'is_site_featured' => true,
            'is_store_featured' => true,
            'status' => Published(),
        ]);
        Product::create([
            'name' => 'Bag',
            'price' => 350,
            'price_to_display' => 360,
            'short_description' => 'Good Bag',
            'description' => 'Good Bag',
            'store_id' => 2,
            'image' => 'https://images.unsplash.com/photo-1491637639811-60e2756cc1c7?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=669&q=80',
            'manage_able' => true,
            'user_id' => 1,
            'is_site_featured' => true,
            'is_store_featured' => true,
            'status' => Published(),
        ]);
        Product::create([
            'name' => 'Arthur Dotson',
            'short_description' => 'Good Bag',
            'price' => 100,
            'price_to_display' => 110,
            'store_id' => 2,
            'description' => 'Good perfume',
            'image' => 'https://images.unsplash.com/photo-1528740561666-dc2479dc08ab?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1868&q=80',
            'manage_able' => true,
            'product_type' => 'variation',
            'user_id' => 1,
            'is_site_featured' => true,
            'is_store_featured' => true,
            'status' => Published(),
        ]);


        Variation::create([
            'name' => 'Arthur Dotson - Size: Small - Color: Red',
            'price' => '110',
            'variants' => '{"Size":{"name":"Small","id":4,"media":null,"attr_id":1},"Color":{"name":"Red","id":1,"media":"#ff0000","attr_id":2}}',
            'product_id' => 3,
        ]);
        Variation::create([
            'name' => 'Arthur Dotson - Size: Large - Color: Red',
            'price' => '110',
            'variants' => '{"Size":{"name":"Large","id":5,"media":null,"attr_id":1},"Color":{"name":"Red","id":1,"media":"#ff0000","attr_id":2}}',
            'product_id' => 3,
        ]);

        Variation::create([
            'name' => 'Arthur Dotson - Size: Small - Color: Black',
            'price' => '110',
            'variants' => '{"Size":{"name":"Small","id":4,"media":null,"attr_id":1},"Color":{"name":"Black","id":2,"media":"#2f2d2d","attr_id":2}}',
            'product_id' => 3,
        ]);
        Variation::create([
            'name' => 'Arthur Dotson - Size: Large - Color: Black',
            'price' => '110',
            'variants' => '{"Size":{"name":"Large","id":5,"media":null,"attr_id":1},"Color":{"name":"Black","id":2,"media":"#2f2d2d","attr_id":2}}',
            'product_id' => 3,
        ]);
        Variation::create([
            'name' => 'Arthur Dotson - Size: Small - Color: Blue',
            'price' => '110',
            'variants' => '{"Size":{"name":"Small","id":4,"media":null,"attr_id":1},"Color":{"name":"Blue","id":3,"media":"#2b00ff","attr_id":2}}',
            'product_id' => 3,
        ]);
        Variation::create([
            'name' => 'Arthur Dotson - Size: Large - Color: Blue',
            'price' => '110',
            'variants' => '{"Size":{"name":"Large","id":5,"media":null,"attr_id":1},"Color":{"name":"Blue","id":3,"media":"#2b00ff","attr_id":2}}',
            'product_id' => 3,
        ]);
        Review::create([
            'rating' => 4,
            'comment' => "Very Good Product.",
            'product_id' => 3,
            'store_id' => 2,
            'user_id' => 1,
        ]);
        Review::create([
            'rating' => 4,
            'comment' => "Good Quality.",
            'product_id' => 3,
            'store_id' => 2,
            'user_id' => 1,
        ]);
        Review::create([
            'rating' => 4,
            'comment' => "Best service",
            'product_id' => 3,
            'store_id' => 2,
            'user_id' => 1,
        ]);
    }
}
