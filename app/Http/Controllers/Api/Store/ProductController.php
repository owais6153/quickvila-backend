<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductServices\VariationService;
use App\Repositories\Repository;
use App\Models\Attribute;

class ProductController extends Controller
{
    function __construct(Product $product)
    {
        $this->model = new Repository($product);
        $this->variationservice = new VariationService();
    }
    public function index(Request $request)
    {
        $mystore = $request->mystore;
        $data = [
            'products' => $mystore->products,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    public function create(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name' => 'required|min:3|max:255',
                'image' => 'nullable',
                'short_description' => 'nullable|max:255',
                'price' => 'required|numeric|min:1',
                'sale_price' => 'nullable|numeric|min:1',
                'description' => 'nullable',
                'categories.*' => 'nullable|numeric',
                'product_type' => 'required',
                'status' => 'required',
                'p_attributes' =>  'nullable',
                'variations' => 'required_if:product_type,variation',
                'gallery' =>  'nullable',
                'gallery.*'=>'nullable|file|mimes:png,jpg,jpeg,gif',
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

            $gallery = [];
            if ($request->hasFile('gallery')) {
                $files = $request->file('gallery');
                foreach($files as $img){
                    $galleryImage = $img;
                    $galleryImageName = uploadFile($galleryImage, imagePath());
                    $gallery[] = $galleryImageName;
                }
            }




            $mystore = $request->mystore;
            $product = $this->model->create([
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
                'is_taxable' => $request->has('is_taxable'),
                'gallery' => !empty($gallery) ? json_encode( $gallery) : null,
            ]);

            if($request->product_type == 'variation'){

                $this->variationservice->createFromJson($request->variations, $product);
            }

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
                'image' => 'nullable',
                'short_description' => 'nullable|max:255',
                'price' => 'required|numeric|min:1',
                'sale_price' => 'nullable|numeric|min:1',
                'description' => 'nullable',
                'categories.*' => 'nullable|numeric',
                'product_type' => 'required',
                'status' => 'required',
                'p_attributes' =>  'nullable',
                'variations' => 'required_if:product_type,variation',
                'gallery' =>  'nullable',
                'gallery.*'=>'nullable|file|mimes:png,jpg,jpeg,gif',
            ]);

            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }

            $mystore = $request->mystore;
            $product = $mystore->products()->where('id', $id)->with(['categories'])->first();
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
            $gallery = [];
            if ($request->hasFile('gallery')) {
                $files = $request->file('gallery');
                foreach($files as $img){
                    $galleryImage = $img;
                    $galleryImageName = uploadFile($galleryImage, imagePath());
                    $gallery[] = $galleryImageName;
                }
            }

            $this->model->update([
                'name' => $request->name,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'product_type' => $request->product_type,
                'price' => $request->price,
                'status' => $request->status,
                'is_store_featured' => $request->has('is_store_featured') ? $request->is_store_featured : false ,
                'sale_price' => $request->sale_price,
                'image' => ($image != '') ? $image : $product->image,
                'gallery' => !empty($gallery) ? json_encode( $gallery) : json_encode($product->gallery),
            ], $product->id);

            if($request->product_type == 'variation'){
                $product->variations()->delete();
                $this->variationservice->createFromJson($request->variations, $product);
            }

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
            $product = $mystore->products()->where('id', $id)->with(['categories', 'variations'])->first();
            if(empty($product)){
                $error['errors'] = ['product' => ['Product Not Found.']];
                $error['status'] = 400;
                return response()->json($error, 404);
            }


            $data = [];
            $data['product'] = $product;
            $data['reviews'] = $product->reviews;
            $data['status'] = 200;

            if($product->product_type == 'variation'){
                $data['product_options'] = $this->variationservice->getAllOptions($product->variations);
            }


            return response()->json($data, 200);

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
            $product = $mystore->products()->where('id', $id)->with(['categories'])->first();;
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
    public function listForVariations(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'variation_attr' => 'required',
            ]);
            if ($validator->fails()) {
                $error['errors'] = $validator->messages();
                $error['status'] = 400;
                return response()->json($error, 400);
            }
            $variants = [];


            $variants = $this->variationservice->getAllPossibleVariants(explode(',', $request->variation_attr));
            $options = Attribute::whereIn('id', explode(',', $request->variation_attr))->whereHas('options')->get();
            $op = [];

            foreach($options as $option){
                $op[$option->name] = $option->options;
            }



            return response()->json(['options' => $op, 'variants' => $variants, 'status' => 200], 200);
        } catch (\Throwable $th) {
            $error['errors'] = ['error' => [$th->getMessage()]];
            $error['status'] = 500;
            return response()->json($error, 500);
        }
    }
}
