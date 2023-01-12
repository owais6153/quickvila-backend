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
                        from stores WHERE `stores`.`status` = '$status' having distance <= radius order by distance limit 10");

        return $nearbystores;
    }

    public function getNearbyStoresID($latitude, $longitude)
    {
        $nearbystores = $this->getNearbyStores($latitude, $longitude);

        $ids=[];
        foreach($nearbystores as $nearbystore){
            $ids[]= $nearbystore->id;
        }

        return $ids;
    }
}
