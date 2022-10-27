<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronDetial;
use App\Models\Product;
use App\Models\StoreCategory;
use App\Models\Store;


class NikeProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nike:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Nike Products';

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
        $page = 1;
        $store = Store::where('name', 'Nike')->first();
        $CronDetial = CronDetial::where('errors',  null)->orderBy('created_at', 'desc')->first();
        if (!empty($store)) {
            if (!empty($CronDetial)) {
                if ($page < intval($CronDetial->totalPages)) {
                    $page = intval($CronDetial->currentPage) + 1;
                }
            } else {

                $this->info('First Time Cron');
            }

            $cron = new CronDetial();
            $cron->currentPage = $page;
            $cron->store_id = $store->id;


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.nike.com/cic/browse/v2?queryid=products&anonymousId=23B0372A5EB4CA9BE5173EFEE951FB24&country=ca&endpoint=%2Fproduct_feed%2Frollup_threads%2Fv2%3Ffilter%3Dmarketplace(CA)%26filter%3Dlanguage(en-GB)%26filter%3DemployeePrice(true)%26anchor%3D$page%26consumerChannelId%3Dd9a5bc42-4b9c-4976-858a-f159cf99c647%26count%3D24&language=en-GB&localizedRangeStr=%7BlowestPrice%7D%E2%80%94%7BhighestPrice%7D",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Cookie: _abck=411DB18200D109B51C0EB11648B36F4A~-1~YAAQSWrcF6pDm+2DAQAAXtyrGwgYuIY3ohZeJC+paq92ElgzOtAxn1eL7BuObrDPzjo6NVKyyjyDdE1UG+tdyA1ckUWRafakdqB5DUt2UGYV9BvYdkSnEi0i+Up3UUGzhQ0pUWX/Qlc4sHjrD7APgFjq4bHXNtq/E5PXcpwvQFhNLFtAMbouBA/Od6E5TvO+6w7deV8e46XJHPiHTNh6tp9wJDz+5LrPItqRJdcb/7mLz6Tb/SFIWUluEGQFbW2iGBBQYJM33c0rJ/0VzCWZGorUc8TT9G9vRWLjZPYsvx3fvtdvPeD+n9MVxOKWRFC4eEkJ5dp/peUid1VfnGqravNES/EzEOTG+0peg0yfQvEV8EqEjqg1qX461sh3mNWtbJ/dln8ussAqK20v2rJyaMwtOFIPaEYGBX4Ybz3QqwsSqcVZFxiLaaUUccveeEEKimHUPEF3wQ2WsmM=~-1~-1~-1; bm_sz=38B708CE8D5ADDE06612FC5501A6DA7E~YAAQNb7CF3H1ORuEAQAAo55XGxFuwHqObqJujZjr+iU8ZPoZ2d6gdAK2N6y9JHT4DSa/pEUma+bD9DuJTSOCX6UCQ3G8xrpIKZ7smUMYwaXQA2tCfAA3YbDe7HFxvbB6BX4VXqZAc4jq6CTbKpdyO2HYAsOlCOq8CDOsJhUdvdj6h48hHmnZxAHnvKi9aF0m4RKOZYk2OJa4N5a9gXGua6nLJC2+Z5N1MN5HspDq2r3nDC6CayNCOWy6HfwT4A59VlWfHSUfEWFE9cYDhul0+XGgQF5s52VO/nk8t6VbpMdez+YTusI2p4/tWmBCg44X4OX8inP2sh8AxvwaV8SR6M1QKgFN7nGdKdj+Smhm7ELHCZ+t1DISo6URPHDW~4538928~4407873'
                ),
            ));

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                $cron->errors = 'Curl Error' . curl_error($curl);
                $cron->totalPages = 'N/A';
                $cron->save();

                $this->info('Curl Error' . curl_error($curl));
            } else {

                $products_data = json_decode($response);
                if (isset($products_data->data) && isset($products_data->data->products)) {
                    if ($products_data->data->products->errors != null) {
                        $cron->errors = json_encode($products_data->data->products->errors);
                        $cron->totalPages = 'N/A';
                        $cron->save();
                    } else {
                        $products = $products_data->data->products->products;
                        foreach ($products as $p) {
                            $pr = Product::where('product_id', $p->id)->first();
                            if (!empty($pr)) {
                                $pr->update([
                                    'name' => $p->title,
                                    'image' => (isset($p->images) && !empty($p->images)) ? $p->images->portraitURL : 'images/no-image.png',
                                    'manage_able' => false,
                                    'is_featured' => false,
                                    'user_id' => 1,
                                    'price' => $p->price->fullPrice,
                                    'sale_price' => ($p->price->fullPrice !=  $p->price->currentPrice) ? $p->price->currentPrice : null,
                                    'product_type' => 'simple'
                                ]);
                            } else {
                                $product = Product::create([
                                    'product_id' => $p->id,
                                    'name' => $p->title,
                                    'store_id' => $store->id,
                                    'image' => (isset($p->images) && !empty($p->images)) ? $p->images->portraitURL : 'images/no-image.png',
                                    'manage_able' => false,
                                    'is_featured' => false,
                                    'user_id' => 1,
                                    'price' => $p->price->fullPrice,
                                    'sale_price' => ($p->price->fullPrice !=  $p->price->currentPrice) ? $p->price->currentPrice : null,
                                    'product_type' => 'simple'
                                ]);
                            }
                        }

                        $cron->totalResources = $products_data->data->products->pages->totalResources;
                        $cron->totalPages = $products_data->data->products->pages->totalPages;
                        $cron->traceId = $products_data->data->products->traceId;

                        $cron->save();
                    }
                } else if (isset($products_data->error_id)) {
                    $errors = [];
                    $this->info('Error Id: ' . $products_data->error_id);
                    foreach ($products_data->errors as $er) {

                        $this->info('Code: ' . $er->code);
                        $this->info('Error: ' . $er->message);
                        $errors[] = $er->message;
                    }

                    $cron->errors = json_encode($errors);
                    $cron->totalPages = 'N/A';
                    $cron->save();
                } else {
                    $this->info(json_encode($products_data->data->products));
                    $this->info('Product Not Found');
                }
            }
            curl_close($curl);
        } else {
            $this->info('Store Not Found');
        }
    }
}
