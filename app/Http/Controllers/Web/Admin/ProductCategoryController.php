<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Requests\Admin\StoreCategoryRequest;
use DataTables;
use Bouncer;

class ProductCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-product-category', ['index', 'getList']);
        $this->middleware('permission:create-product-category', ['create', 'store']);
        $this->middleware('permission:edit-product-category', ['edit', 'update']);
        $this->middleware('permission:delete-product-category', ['destroy']);
        $this->dir = 'admin.productcategory.';
    }
    public function index()
    {
        return view($this->dir . 'index');
    }

    public function getList(Request $request)
    {
        $model = ProductCategory::query();
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('edit-store-category')) {
                    $actionBtn .= '<a href="' . route('productcategory.edit', ['productcategory' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                }
                if (Bouncer::can('delete-store-category')) {
                    $actionBtn .= '
                <form style="display:inline-block" method="POST" action="' . route('productcategory.destroy', ['productcategory' => $row->id]) . '">
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
    public function create()
    {
        return view($this->dir . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $cat = ProductCategory::create($request->all());
        return redirect()->route('productcategory.index')->with('success', 'Product Category Created');
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
    public function edit(ProductCategory $productcategory)
    {
        return view($this->dir . 'edit', compact('productcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoryRequest $request, ProductCategory $productcategory)
    {
        $productcategory->update($request->all());
        return redirect()->route('productcategory.index')->with('success', 'Product Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $productcategory)
    {
        $productcategory->delete();
        return redirect()->route('productcategory.index')->with('success', 'Product Category Deleted');
    }
}
