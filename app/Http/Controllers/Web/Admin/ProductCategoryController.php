<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Repositories\Repository;
use App\Http\Requests\Admin\ProductCategoryRequest;
use DataTables;
use Bouncer;
use Auth;

class ProductCategoryController extends Controller
{
    function __construct(ProductCategory $productcategory)
    {
        $this->middleware('permission:view-product-category', ['index', 'getList']);
        $this->middleware('permission:create-product-category', ['create', 'store']);
        $this->middleware('permission:edit-product-category', ['edit', 'update']);
        $this->middleware('permission:delete-product-category', ['destroy']);
        $this->dir = 'admin.productcategory.';
        $this->model = new Repository($productcategory);
    }
    public function index(Store $store)
    {
        return view($this->dir . 'index', compact('store'));
    }

    public function getList(Request $request)
    {
        $model = $this->model->query();
        $model = $model->where('store_id', $request->store);
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('edit-product-category') && (Bouncer::can('all-product-category') || $row->user_id == Auth::id())) {
                    $actionBtn .= '<a href="' . route('productcategory.edit', ['store' => $row->store_id, 'productcategory' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                }
                if (Bouncer::can('delete-product-category') && (Bouncer::can('all-product-category') || $row->user_id == Auth::id())) {
                    $actionBtn .= '<form style="display:inline-block" method="POST" action="' . route('productcategory.destroy', ['store' => $row->store_id, 'productcategory' => $row->id]) . '">
                    <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                    <input type="hidden" name="_method" value="DELETE" />
                <button class="btn-circle delete btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></form>';
                }
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Store $store)
    {
        return view($this->dir . 'create', compact('store'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCategoryRequest $request)
    {
        $cat = ProductCategory::create([
            'name' =>$request->name,
            'user_id' => auth()->id(),
            'store_id' => $request->store
        ]);
        return redirect()->route('productcategory.index', ['store' => $request->store])->with('success', 'Product Category Created');
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
    public function edit(Store $store ,$id)
    {
        $productcategory = $this->model->show($id);
        return view($this->dir . 'edit', compact('productcategory', 'store'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductCategoryRequest $request, Store $store, $id)
    {
        $this->model->update([
            'name' =>$request->name,
        ], $id);
        return redirect()->route('productcategory.index', ['store' => $request->store])->with('success', 'Product Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store, $id)
    {
        $this->model->delete($id);
        return redirect()->route('productcategory.index', ['store' => $request->store])->with('success', 'Product Category Deleted');
    }
}
