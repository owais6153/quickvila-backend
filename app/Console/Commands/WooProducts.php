<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Automattic\WooCommerce\Client;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\WPCron;

class WooProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'woocommerce:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $woocommerce = new Client(
            'https://bismillahgrocery.ca',
            'ck_005333bd2521f1f32278e8848334b5ccd0dc75c9',
            'cs_ce84184d292a14faccbf158c394545e847a2d1e0',
            [
              'version' => 'wc/v3',
            ]
        );

        $WPCron = WPCron::where('error',  null)->orderBy('created_at', 'desc')->first();
        $posts_per_page = 60;
        $page = 1;
        $store_id = 3;

        if (!empty($WPCron)) {
            $page = intval($WPCron->page) + 1;
        } else {
            $this->info('First Time Cron');
        }

       $args = ['per_page' => $posts_per_page, 'status'=>'publish', 'page'=>$page, 'type' => 'simple'];
       $products = $woocommerce->get('products', $args);

    //    dd($products);
       if(empty($products)){
        $page = 0;
       }
       else{
            foreach($products as $p){
                $product = Product::where('product_id', $p->id)->where('store_id', $store_id)->first();
                if(empty($product)){
                    $product = Product::create([
                        'product_id' => $p->id,
                        'name' => $p->name,
                        'short_description' => $p->short_description,
                        'description' => $p->description,
                        'store_id' => $store_id,
                        'image' =>  (isset($p->images) && !empty($p->images)) ? $p->images[0]->src : 'images/no-image.png',
                        'manage_able' => false,
                        'status' => Published(),
                        'user_id' => 1,
                        'price' => $p->price,
                        'sale_price' => $p->sale_price,
                        'product_type' => 'simple',
                        'is_store_featured' => $p->featured,
                        "is_taxable" => $p->tax_status == 'taxable' ? true: false,
                    ]);
                }
                else{
                    $product->update([
                        'name' => $p->name,
                        'short_description' => $p->short_description,
                        'description' => $p->description,
                        'image' =>  (isset($p->images) && !empty($p->images)) ? $p->images[0]->src : 'images/no-image.png',
                        'price' => $p->price,
                        'sale_price' => $p->sale_price,
                        'product_type' => 'simple',
                        'is_store_featured' => $p->featured,
                        "is_taxable" => $p->tax_status == 'taxable' ? true: false,
                    ]);
                }

                $cats = [];
                foreach($p->categories as $c){
                    $cat = ProductCategory::where('name', $c->name)->where('store_id', $store_id)->first();
                    if(empty($cat)){
                        $cat = ProductCategory::create([
                            'store_id' => $store_id,
                            'user_id' => 1,
                            'name' => $c->name,
                        ]);
                    }
                    $cats[] = $cat->id;
                }

                $product->categories()->attach($cats, ['type' => 'product']);
            }
       }
       WPCron::create([
        'posts_per_page' => $posts_per_page,
        'page' =>$page ,
        'error'=> null,
       ]);

    }
}
