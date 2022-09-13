<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Http\Requests\Admin\ProductRequest;
use DataTables;
use Bouncer;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-product', ['index', 'getList']);
        $this->middleware('permission:create-product', ['create', 'store']);
        $this->middleware('permission:edit-product', ['edit', 'update']);
        $this->middleware('permission:delete-product', ['destroy']);
        $this->dir = 'admin.product.';
    }
    public function getList(Request $request)
    {
        $model = Product::query();
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('edit-product')) {
                    $actionBtn .= '<a href="' . route('product.edit', ['product' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                }
                if (Bouncer::can('delete-product') && $row->manage_able) {
                    $actionBtn .= '
                    <form style="display:inline-block" method="POST" action="' . route('product.destroy', ['product' => $row->id]) . '">
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
    public function index()
    {
        return view($this->dir . 'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::all();
        $stores = Store::all();
        return view($this->dir . 'create', compact('categories', 'stores'));
    }

    /**
     * Product a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $image = "";
        if ($request->hasFile('image')) {

            $imageFile = $request->image;
            $file_name = uploadFile($imageFile, imagePath());
            $image = $file_name;
        }


        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'store_id' => $request->store,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'user_id' => $request->user_id,
            'image' => ($image != '') ? $image : noImage(),
        ]);

        if ($request->has('categories'))
            $product->categories()->attach($request->categories, ['type' => 'product']);

        return redirect()->route('product.index')->with('success', 'Product Created');
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
    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        $stores = Store::all();
        return view($this->dir . 'edit', compact('product', 'categories', 'stores'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {

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
            'store_id' => $request->store,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'user_id' => $request->user_id,
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



        return redirect()->route('product.index')->with('success', 'Product Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->manage_able) {
            deleteFile(str_replace(env('FILE_URL'),'',$product->image) );
            $product->delete();
            return redirect()->route('product.index')->with('success', 'Product Deleted');
        }
        abort(404);
    }
}
