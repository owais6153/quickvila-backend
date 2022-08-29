<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Category;
use App\Http\Requests\Admin\StoreRequest;
use DataTables;
use Bouncer;

class StoreController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-store', ['index', 'getList']);
        $this->middleware('permission:create-store', ['create', 'store']);
        $this->middleware('permission:edit-store', ['edit', 'update']);
        $this->middleware('permission:delete-store', ['destroy']);
        $this->dir = 'admin.store.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $model = Store::query();
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('edit-store')) {
                    $actionBtn .= '<a href="' . route('store.edit', ['store' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                }
                if (Bouncer::can('delete-store')) {
                    $actionBtn .= '
                <form style="display:inline-block" method="POST" action="' . route('store.destroy', ['store' => $row->id]) . '">
                    <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                    <input type="hidden" name="_method" value="DELETE" />
                <button class="btn-circle delete btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></form>';
                }
                return $actionBtn;
            })
            ->addColumn('products', function ($row) {
                return $row->products->count();
            })
            ->rawColumns(['action'])
            ->toJson();
    }

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
        $categories = Category::where('type', 'store')->get();
        return view($this->dir . 'create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
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

        $store = Store::create([
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'logo' => ($logo != '') ? $logo : null,
            'cover' => ($cover != '') ? $cover : null,
        ]);

        if ($request->has('categories'))
            $store->categories()->attach($request->categories, ['type' => 'store']);

        return redirect()->route('store.index')->with('success', 'Store Created');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        $categories = Category::where('type', 'store')->get();
        return view($this->dir . 'edit', compact('store', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, Store $store)
    {

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
            'logo' => ($logo != '') ? $logo : $store->logo,
            'cover' => ($cover != '') ? $cover : $store->cover,
        ]);

        if ($request->has('categories')) {
            $categories  = (array) $request->get('categories'); // related ids
            $pivotData = array_fill(0, count($categories), ['type' => 'hotel']);
            $syncData  = array_combine($categories, $pivotData);
            $skatepark->categories()->sync($syncData);
        }

        return redirect()->route('store.index')->with('success', 'Store Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('store.index')->with('success', 'Store Deleted');
    }
}
