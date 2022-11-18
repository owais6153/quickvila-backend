<?php

namespace App\Listners;

use App\Events\StoreEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\Store;
use Hash;

class StoreCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setting = getSetting('hidden');
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\StoreEvents  $event
     * @return void
     */
    public function handle(StoreEvents $event)
    {
        $store = $event->request;
        $user = new User();
        $user->name =  $store->name;
        $user->first_name =  $store->name;
        $user->last_name =  $store->name;
        $user->password =  Hash::make($store->password);
        $user->email = $store->email;
        $user->email_verified_at = date("Y-m-d", time());
        $user->phone_verified_at = date("Y-m-d", time());
        $user->save();
        $user->assign(Store());
        Store::where('id', $event->store_id)->update([
            'owner_id' => $user->id
        ]);
    }
}
