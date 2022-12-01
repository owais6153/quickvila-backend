<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Http\Requests\Admin\StoreRequest;


class StoreController extends Controller
{
    public function index(Request $request){
        return response()->json([
            'mystore' => Store::with('categories')->where('id', $request->mystore->id)->first(),
            'categories' => StoreCategory::all(),
            'status' => 200
        ], 200);
    }
    public function update(Request $request){

        try {

            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3|max:255',
                'logo' => 'nullable|file|mimes:png,jpg,jpeg,gif',
                'cover' => 'nullable|file|mimes:png,jpg,jpeg,gif',
                'description' => 'nullable',
                'url' => 'nullable|min:5',
                'address' => 'nullable|min:3',
                'latitude' => 'required|between:-90,90',
                'longitude' => 'required|between:-180,180',
                'category.*' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;

                return response()->json($error, 400);
            }


            $store = $request->mystore;
            $logo = $cover = "";
            if ($request->hasFile('logo')) {

                $logoFile = $request->logo;
                $file_name = uploadFile($logoFile, imagePath());
                $logo = $file_name;
            }
            if ($request->hasFile('cover')) {

                $coverFile = $request->cover;
                $file_name = uploadFile($coverFile, imagePath());
                $cover = $file_name;
            }

            $store->update([
                'name' => $request->name,
                'description' => $request->description,
                'url' => $request->url,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'logo' => ($logo != '') ? $logo : str_replace(env('FILE_URL'),'',$store->logo),
                'cover' => ($cover != '') ? $cover : str_replace(env('FILE_URL'),'',$store->cover),
            ]);

            if ($request->has('categories')) {
                $categories  = (array) $request->get('categories');
                $pivotData = array_fill(0, count($categories), ['type' => 'store']);
                $syncData  = array_combine($categories, $pivotData);
                $store->categories()->sync($syncData);
            } else {
                $store->categories()->sync(array(), ['type' => 'store']);
            }

            return response()->json([
                'status' => 200,
                'mystore' => $store,
                'message' => 'Store Updated',
            ], 200);

        } catch (\Throwable $th) {
            $error['errors'] = ['Server error' => [$th->getMessage()]];
            $error['status'] = 500;

            return response()->json($error, 500);
        }
    }
}
