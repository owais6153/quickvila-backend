<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Http\Requests\Admin\StoreSettingRequest;
use App\Http\Requests\Admin\StoreRequest;
use App\Models\StoreSetting;
use DataTables;
use Bouncer;
use Auth;
use App\Events\StoreEvents;

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
        $model =  Store::withCount(['products']);
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {

                $html = '<div class="dropdown">
                <a class="btn btn-sm btn-icon-only dropdown-toggle text-light" role="button" style="color: #ced4da !important;"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">';
                if (Bouncer::can('edit-store')) {
                    $html .= '<a  href="' . route('store.edit', ['store' => $row->id]) . '" class="dropdown-item"><i class="mr-2 fas fa-pencil-alt"></i>Edit</a>';
                }
                if(Bouncer::can('setting-store') ){
                    $html .= '<a  href="' . route('store.setting', ['store' => $row->id]) . '" class="dropdown-item">
                    <i class="mr-2 fas fa-fw fa-cog"></i>Setting</a>';
                }
                if (Bouncer::can('delete-store')  && $row->manage_able) {
                    $html .= '<form method="POST" action="' . route('store.destroy', ['store' => $row->id]) . '">
                    <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                    <input type="hidden" name="_method" value="DELETE" />
                    <button class="dropdown-item d-block mr-1 btn-link delete"><i class="mr-2 fas fa-trash-alt"></i>Delete</button></form>';
                }
                if (Bouncer::can('view-product')) {
                    $html .= '<li class="dropdown-header">Prdoucts</li>';
                    $html .= '<a  href="' . route('product.index', ['store' => $row->id]) . '" class="dropdown-item"><i class="mr-2 fas fa-archive"></i>Products</a>';
                }

                if (Bouncer::can('view-product-category')) {
                    $html .= '<a  href="' . route('productcategory.index', ['store' => $row->id]) . '" class="dropdown-item"><i class="mr-2 fas fa-archive"></i>Product Categories</a>';
                }

                if (Bouncer::can('view-attribute')) {
                    $html .= '<a  href="' . route('attribute.index', ['store' => $row->id]) . '" class="dropdown-item"><i class="mr-2 fas fa-archive"></i>Product Attributes</a>';
                }

                $html .= '</div>
            </div>';

                return $html;
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
            'type' => $request->type,
            'status' => $request->status,
            'is_featured' => $request->has('is_featured')
        ]);

        $storeSetting = StoreSetting::create([
            'store_id' => $store->id,
            'price' => $this->setting['default_price'],
            'radius' => $this->setting['default_radius'],
            'price_condition' => $this->setting['default_price_condition'],
        ]);

        if ($request->has('categories'))
            $store->categories()->attach($request->categories, ['type' => 'store']);


        event(new StoreEvents($request, $store->id));

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
    public function update(Request $request, Store $store)
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
            'type' => $request->type,
            'status' => $request->status,
            'is_featured' => $request->has('is_featured')
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
            $store->owner->delete();
            $store->setting()->delete();
            if($store->products->count() > 0)
                $store->products->delete();

            $store->delete();
            return redirect()->route('store.index')->with('success', 'Store Deleted');
        }
        abort(404);
    }
}
