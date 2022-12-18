<?php
namespace App\Services\StoreServices;
use DB;

class StoreGeoLocationService
{
   public function getNearbyStores($latitude, $longitude){
        $status = Published();
        $nearbystores = DB::select("SELECT `stores`.* , (6371 * 2 * ASIN(SQRT( POWER(SIN(( {$latitude} - stores.latitude) * pi()/180 / 2), 2)
                        +COS( {$latitude} * pi()/180)
                        * COS(stores.latitude * pi()/180)
                        * POWER(SIN(( {$longitude} - stores.longitude)
                        * pi()/180 / 2), 2) )))
                        as distance, (select radius from `store_settings` where `stores`.`id` = `store_settings`.`store_id`) as radius
                        , (select count(*) from `products` where `stores`.`id` = `products`.`store_id` and `products`.`deleted_at` is null and `products`.`status` = '$status') as `products_count` from stores WHERE `stores`.`status` = '$status' having distance <= radius order by distance limit 10");

        return $nearbystores;
    }

    // public function FunctionName(Type $var = null)
    // {
    //     # code...
    // }
}