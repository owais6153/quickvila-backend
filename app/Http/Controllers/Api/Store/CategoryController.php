<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $mystore = $request->mystore;
        $data = [
            'categories' => ProductCategory::where('store_id', $mystore->id)->get(),
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    public function create(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3',
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }

            $mystore = $request->mystore;
            $cat = ProductCategory::create([
                'name' =>$request->name,
                'user_id' => auth()->id(),
                'store_id' => $mystore->id
            ]);

            return response()->json(['status' => 200, 'message' => 'Product Category Created'], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function update(Request $request, $id)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3',
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }

            $mystore = $request->mystore;
            $cat = ProductCategory::where('id', $id)->where('store_id', $mystore->id)->first();
            if(empty($cat)){
                $error['errors'] = ['category' => ['Category Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }

            $cat->update([
                'name' =>$request->name,
            ]);

            return response()->json(['status' => 200, 'message' => 'Product Category Updated'], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function destroy(Request $request, $id)
    {
        try{
            $mystore = $request->mystore;
            $cat = ProductCategory::where('id', $id)->where('store_id', $mystore->id)->first();
            if(empty($cat)){
                $error['errors'] = ['category' => ['Category Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }
            $cat->delete();

            return response()->json(['status' => 200, 'message' => 'Product Category Deleted'], 200);

        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
    public function show(Request $request, $id)
    {
        try{
            $mystore = $request->mystore;
            $cat = ProductCategory::where('id', $id)->where('store_id', $mystore->id)->first();
            if(empty($cat)){
                $error['errors'] = ['category' => ['Category Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }

            return response()->json(['status' => 200, 'category' => $cat], 200);

        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
}
