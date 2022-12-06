<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $mystore = $request->mystore;
        $data = [
            'products' => Product::where('store_id', $mystore->id)->get(),
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    public function create(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3|max:255',
                'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
                'short_description' => 'nullable|max:255',
                'price' => 'required|numeric|min:1',
                'sale_price' => 'nullable|numeric|min:1',
                'description' => 'nullable',
                'categories.*' => 'nullable|numeric',
                'product_type' => 'required',
                'status' => 'required',
                'p_attributes' =>  'nullable',
                // Variations
                'variations' => 'required_if:product_type,variation',
                'variations.*.name' => 'required_if:product_type,variation|max:255',
                'variations.*.price' => 'required_if:product_type,variation|numeric|min:1',
                'variations.*.sale_price' => 'nullable|numeric|min:1',
                'variations.*.variants' => 'required_if:product_type,variation',
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }
            $image = "";
            if ($request->hasFile('image')) {
                $imageFile = $request->image;
                $file_name = uploadFile($imageFile, imagePath());
                $image = $file_name;
            }
            $mystore = $request->mystore;
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'store_id' => $mystore->id,
                'product_type' => $request->product_type,
                'price' => $request->price,
                'status' => $request->status,
                'is_store_featured' => $request->has('is_store_featured') ? $request->is_store_featured : false ,
                'sale_price' => $request->sale_price,
                'user_id' =>auth()->id(),
                'image' => ($image != '') ? $image : noImage(),
            ]);

            if ($request->has('categories'))
                $product->categories()->attach($request->categories, ['type' => 'product']);

            return response()->json(['status' => 200, 'message' => 'Product Created'], 200);

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
                'name' => 'required|min:3|max:255',
                'image' => 'nullable|file|mimes:png,jpg,jpeg,gif',
                'short_description' => 'nullable|max:255',
                'price' => 'required|numeric|min:1',
                'sale_price' => 'nullable|numeric|min:1',
                'description' => 'nullable',
                'categories.*' => 'nullable|numeric',
                'product_type' => 'required',
                'status' => 'required',
                'p_attributes' =>  'nullable',
                // Variations
                'variations' => 'required_if:product_type,variation',
                'variations.*.name' => 'required_if:product_type,variation|max:255',
                'variations.*.price' => 'required_if:product_type,variation|numeric|min:1',
                'variations.*.sale_price' => 'nullable|numeric|min:1',
                'variations.*.variants' => 'required_if:product_type,variation',
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }

            $mystore = $request->mystore;
            $product = Product::where('id', $id)->where('store_id', $mystore->id)->with(['categories'])->first();
            if(empty($product)){
                $error['errors'] = ['product' => ['Product Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }
            $image = "";
            if ($request->hasFile('image')) {
                $imageFile = $request->image;
                $file_name = uploadFile($imageFile, imagePath());
                $image = $file_name;
            }

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'product_type' => $request->product_type,
                'price' => $request->price,
                'status' => $request->status,
                'is_store_featured' => $request->has('is_store_featured') ? $request->is_store_featured : false ,
                'sale_price' => $request->sale_price,
                'image' => ($image != '') ? $image : $product->image,
            ]);

            if ($request->has('categories')) {
                $categories  = (array) $request->get('categories'); // related ids
                $pivotData = array_fill(0, count($categories), ['type' => 'product']);
                $syncData  = array_combine($categories, $pivotData);
                $product->categories()->sync($syncData);
            } else {
                $product->categories()->sync(array(), ['type' => 'product']);
            }


            return response()->json(['status' => 200, 'message' => 'Product Updated'], 200);

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
            $product = Product::where('id', $id)->where('store_id', $mystore->id)->with(['categories'])->first();
            if(empty($product)){
                $error['errors'] = ['product' => ['Product Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }

            return response()->json(['status' => 200, 'product' => $product], 200);

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
            $product = Product::where('id', $id)->where('store_id', $mystore->id)->with(['categories'])->first();
            if(empty($product)){
                $error['errors'] = ['product' => ['Product Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }

            if ($product->manage_able) {
                $product->variations()->delete();
                $product->delete();
            }

            return response()->json(['status' => 200, 'message' => 'Product Deleted'], 200);

        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
}
