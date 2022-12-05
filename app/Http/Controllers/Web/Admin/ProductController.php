<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Http\Requests\Admin\ProductRequest;
use DataTables;
use App\Models\Attribute;
use App\Models\Variation;
use App\Repositories\Repository;
use Bouncer;
use Auth;
use App\Services\ProductServices\VariationService;

class ProductController extends Controller
{
    function __construct(Product $product)
    {
        $this->middleware('permission:view-product', ['index', 'getList']);
        $this->middleware('permission:create-product', ['create', 'store']);
        $this->middleware('permission:edit-product', ['edit', 'update']);
        $this->middleware('permission:delete-product', ['destroy']);
        $this->dir = 'admin.product.';
        $this->model = new Repository($product);
        $this->variationservice = new VariationService();
    }
    public function getList(Request $request)
    {
        $model = $this->model->getModel();
        $model = $model->where('store_id', $request->store_id);

        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('edit-product')) {
                    $actionBtn .= '<a href="' . route('product.edit', ['product' => $row->id, 'store' => $row->store_id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                }
                if (Bouncer::can('delete-product') && $row->manage_able) {
                    $actionBtn .= '
                    <form style="display:inline-block" method="POST" action="' . route('product.destroy', ['product' => $row->id, 'store' => $row->store_id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                        <input type="hidden" name="_method" value="DELETE" />
                    <button class="btn-circle delete btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></form>';
                }
                return $actionBtn;
            })
            ->addColumn('store', function ($row) {
                return $row->store->name;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Store $store)
    {
        return view($this->dir . 'index', compact('store'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Store $store)
    {
        $categories = ProductCategory::where('store_id', $store->id)->get();
        $attributes = Attribute::where('store_id', $store->id)->get();
        return view($this->dir . 'create', compact('categories', 'store', 'attributes'));
    }

    /**
     * Product a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $store, ProductRequest $request)
    {
        $image = "";
        if ($request->hasFile('image')) {
            $imageFile = $request->image;
            $file_name = uploadFile($imageFile, imagePath());
            $image = $file_name;
        }


        $product = $this->model->create([
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'store_id' => $store->id,
            'product_type' => $request->product_type,
            'price' => $request->price,
            'status' => $request->status,
            'is_store_featured' => $request->has('is_store_featured') ? $request->is_store_featured : false ,
            'is_site_featured' => Bouncer::can('setting-store') && $request->has('is_site_featured') ? $request->is_site_featured  : false,
            'sale_price' => $request->sale_price,
            'user_id' => $request->user_id,
            'image' => ($image != '') ? $image : noImage(),
        ]);

        if ($request->has('categories'))
            $product->categories()->attach($request->categories, ['type' => 'product']);


        if($request->product_type == 'variation'){
            $this->variationservice->create($request->variations, $product);
        }

        return redirect()->route('product.index', ['store'=>$product->store_id])->with('success', 'Product Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store, Product $product)
    {
        $categories = ProductCategory::where('store_id', $store->id)->get();
        $attributes = Attribute::where('store_id', $store->id)->get();
        return view($this->dir . 'edit', compact('product', 'categories', 'store', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Store $store, Product $product, ProductRequest $request)
    {

        $image = "";
        if ($request->hasFile('image')) {

            $imageFile = $request->image;
            $file_name = uploadFile($imageFile, imagePath());
            $image = $file_name;
        }

        $this->model->update([
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'product_type' => $request->product_type,
            'store_id' => $store->id,
            'status' => $request->status,
            'is_store_featured' => $request->has('is_store_featured') ? $request->is_store_featured : false ,
            'is_site_featured' => Bouncer::can('setting-store') && $request->has('is_site_featured') ? $request->is_site_featured  : false,
            'price' => $product->manage_able ? $request->price : $product->price,
            'sale_price' => $product->manage_able ? $request->sale_price :  $product->sale_price,
            'image' => ($image != '') ? $image : str_replace(env('FILE_URL'), '', $product->image),
        ], $product->id);

        if ($request->has('categories')) {
            $categories  = (array) $request->get('categories'); // related ids
            $pivotData = array_fill(0, count($categories), ['type' => 'product']);
            $syncData  = array_combine($categories, $pivotData);
            $product->categories()->sync($syncData);
        } else {
            $product->categories()->sync(array(), ['type' => 'product']);
        }

        if($request->product_type == 'variation'){
            $product->variations()->delete();
            $this->variationservice->create($request->variations, $product);
        }

        return redirect()->route('product.index', ['store'=>$product->store_id])->with('success', 'Product Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store,Product $product)
    {
        if ($product->manage_able) {
            $product->variations()->delete();
            $product->delete();
            return redirect()->route('product.index', ['store'=>$product->store_id])->with('success', 'Product Deleted');
        }
        abort(404);
    }

    public function listForVariations(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'variation_attr' => 'required',
        ]);
        if ($validator->fails()) {
            $error['errors'] = $validator->messages();
            $error['status'] = 400;
            return response()->json($error, 400);
        }
        $variants = [];


        $variants = $this->variationservice->getAllPossibleVariants($request);

        return response()->json(['variants' => $variants, 'status' => 200], 200);
    }
}
