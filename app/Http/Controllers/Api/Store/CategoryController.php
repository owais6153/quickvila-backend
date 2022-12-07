<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Repositories\Repository;

class CategoryController extends Controller
{
    function __construct(ProductCategory $ProductCategory)
    {
        $this->model = new Repository($ProductCategory);
    }
    public function index(Request $request)
    {
        $mystore = $request->mystore;
        $data = [
            'categories' =>$mystore->product_categories,
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
            $cat = $this->model->create([
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
            $cat = $mystore->product_categories()->where('id', $id)->first();
            if(empty($cat)){
                $error['errors'] = ['category' => ['Category Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }

            $this->model->update([
                'name' =>$request->name,
            ], $cat->id);

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
            $cat =  $mystore->product_categories()->where('id', $id)->first();
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
            $cat = $mystore->product_categories()->where('id', $id)->first();
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
