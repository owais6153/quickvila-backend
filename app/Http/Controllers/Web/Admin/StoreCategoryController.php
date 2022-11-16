<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreCategory;
use App\Http\Requests\Admin\StoreCategoryRequest;
use DataTables;
use Bouncer;

class StoreCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-store-category', ['index', 'getList']);
        $this->middleware('permission:create-store-category', ['create', 'store']);
        $this->middleware('permission:edit-store-category', ['edit', 'update']);
        $this->middleware('permission:delete-store-category', ['destroy']);
        $this->dir = 'admin.storecategory.';
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

    public function getList(Request $request)
    {
        $model = Bouncer::can('all-store-category') ? StoreCategory::query() : StoreCategory::where('user_id', Auth::id());
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('edit-store-category')) {
                    $actionBtn .= '<a href="' . route('storecategory.edit', ['storecategory' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                }
                if (Bouncer::can('delete-store-category')) {
                    $actionBtn .= '
                <form style="display:inline-block" method="POST" action="' . route('storecategory.destroy', ['storecategory' => $row->id]) . '">
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
        $cat = StoreCategory::create([
            'name' =>$request->name,
            'user_id' => auth()->id()
        ]);
        return redirect()->route('storecategory.index')->with('success', 'Store Category Created');
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
    public function edit(StoreCategory $storecategory)
    {
        return view($this->dir . 'edit', compact('storecategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoryRequest $request, StoreCategory $storecategory)
    {
        $storecategory->update([
            'name' =>$request->name,
            'user_id' => auth()->id()
        ]);
        return redirect()->route('storecategory.index')->with('success', 'Store Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreCategory $storecategory)
    {
        $storecategory->delete();
        return redirect()->route('storecategory.index')->with('success', 'Store Category Deleted');
    }
}
