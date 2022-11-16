<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Http\Requests\Admin\StoreRequest;
use App\Http\Requests\Admin\StoreSettingRequest;
use App\Models\StoreSetting;
use DataTables;
use Bouncer;
use Auth;

class StoreController extends Controller
{
    protected $setting = false;
    function __construct()
    {
        $this->middleware('permission:view-store', ['index', 'getList']);
        $this->middleware('permission:create-store', ['create', 'store']);
        $this->middleware('permission:edit-store', ['edit', 'update']);
        $this->middleware('permission:delete-store', ['destroy']);
        $this->middleware('permission:setting-store', ['setting', 'updateSetting']);
        $this->dir = 'admin.store.';
        $this->setting = getSetting('store');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $model = (Bouncer::can('all-store')) ? Store::query() : Store::where('user_id', Auth::id());
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('edit-store')) {
                    $actionBtn .= '<a href="' . route('store.edit', ['store' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                }
                if (Bouncer::can('view-product')) {
                    $actionBtn .= '<a href="' . route('product.index', ['store' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }

                if (Bouncer::can('delete-store')  && $row->manage_able) {
                    $actionBtn .= '<form style="display:inline-block" method="POST" action="' . route('store.destroy', ['store' => $row->id]) . '">
                    <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                    <input type="hidden" name="_method" value="DELETE" />
                <button class="btn-circle delete btn btn-sm btn-danger mr-1"><i class="fas fa-trash-alt"></i></button></form>';
                }
                if(Bouncer::can('setting-store') ){
                    $actionBtn .= '<a href="' . route('store.setting', ['store' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info">
                    <i class="fas fa-fw fa-cog"></i></a>';
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
        $categories = StoreCategory::all();
        return view($this->dir . 'create', compact('categories'));
    }


    public function setting(Store $store)
    {
        $storeSetting = $store->setting;
        return view($this->dir . 'setting', compact('storeSetting'));
    }
    public function updateSetting(Store $store, StoreSettingRequest $request)
    {
        $storeSetting = StoreSetting::where('store_id', $store->id)->update([
            'price' => $request->price,
            'radius' => $request->radius,
            'tax' => $request->tax,
            'price_condition' => $request->price_condition,
        ]);

        return redirect()->route('store.index')->with('success', 'Store Setting Updated');
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
            'logo' => ($logo != '') ? $logo :  noImage(),
            'cover' => ($cover != '') ? $cover :  noImage(),
            'user_id' => $request->user_id,
        ]);

        $storeSetting = StoreSetting::where('store_id', $store->id)->update([
            'price' => $this->setting['default_price'],
            'radius' => $this->setting['default_radius'],
            'tax' => $this->setting['tax'],
            'price_condition' => $this->setting['default_price_condition'],
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
        $categories = StoreCategory::all();
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
            'logo' => ($logo != '') ? $logo : str_replace(env('FILE_URL'),'',$store->logo),
            'cover' => ($cover != '') ? $cover : str_replace(env('FILE_URL'),'',$store->cover),
            'user_id' => $request->user_id,
        ]);

        if ($request->has('categories')) {
            $categories  = (array) $request->get('categories');
            $pivotData = array_fill(0, count($categories), ['type' => 'store']);
            $syncData  = array_combine($categories, $pivotData);
            $store->categories()->sync($syncData);
        } else {
            $store->categories()->sync(array(), ['type' => 'store']);
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
        if ($store->manage_able) {
            deleteFile(str_replace(env('FILE_URL'),'',$store->logo) );
            deleteFile(str_replace(env('FILE_URL'),'',$store->cover) );
            $store->delete();
            return redirect()->route('store.index')->with('success', 'Store Deleted');
        }
        abort(404);
    }
}
