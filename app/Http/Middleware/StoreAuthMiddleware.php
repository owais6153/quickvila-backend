<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Store;

class StoreAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user = $request->user();
        $store = Store::where('owner_id', $user->id)->first();
        if($store->status != Published()){
            $error['errors'] = ['store' => ['Your store is not published yet.']];
            $error['status'] = 400;
            return response()->json($error, 404);
        }
        else{
            $request->merge(['mystore' => $store]);
            return $next($request);
        }
    }
}
