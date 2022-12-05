<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Models\StoreBanner;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Models\Store;
use DataTables;
use Bouncer;

class StoreBannerController extends Controller
{
    protected $model;

    function __construct(StoreBanner $storebanner)
    {
        $this->middleware('permission:view-store-banner', ['index', 'getList']);
        $this->middleware('permission:create-store-banner', ['create', 'store']);
        $this->middleware('permission:edit-store-banner', ['edit', 'update']);
        $this->middleware('permission:delete-store-banner', ['destroy']);
        $this->dir = 'admin.storebanner.';
        $this->model = new Repository($storebanner);
    }
    public function index()
    {
        return view($this->dir . 'index');
    }
    public function getList(Request $request)
    {
        return DataTables::eloquent($this->model->with(['store']))
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('edit-store-banner')) {
                    $actionBtn .= '<a href="' . route('storebanner.edit', ['storebanner' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                }
                if (Bouncer::can('delete-store-banner')) {
                    $actionBtn .= '
                <form style="display:inline-block" method="POST" action="' . route('storebanner.destroy', ['storebanner' => $row->id]) . '">
                    <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                    <input type="hidden" name="_method" value="DELETE" />
                <button class="btn-circle delete btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></form>';
                }
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function create()
    {
        $stores = Store::where('status', Published())->get();
        return view($this->dir . 'create', compact('stores'));
    }
    public function store(StoreBannerRequest $request)
    {
        $image = "";
        if ($request->hasFile('image')) {
            $imageFile = $request->image;
            $file_name = uploadFile($imageFile, imagePath());
            $image = $file_name;
        }

        $storebanner = $this->model->create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'action' => $request->action,
            'store_id' => $request->store_id,
            'thumbnail' => ($image != '') ? $image : noImage(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('storebanner.index')->with('success', 'Banner Created');
    }
    public function edit($id)
    {
        $stores = Store::where('status', Published())->get();
        $storebanner = StoreBanner::find($id);
        return view($this->dir . 'edit', compact('storebanner', 'stores'));
    }
    public function update(StoreBannerRequest $request, $id)
    {
        $storebanner = StoreBanner::find($id);
        $image = "";
        if ($request->hasFile('image')) {
            $imageFile = $request->image;
            $file_name = uploadFile($imageFile, imagePath());
            $image = $file_name;
        }
        $this->model->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'action' => $request->action,
            'store_id' => $request->store_id,
            'thumbnail' => ($image != '') ? $image : $storebanner->thumbnail,
        ], $id);

        return redirect()->route('storebanner.index')->with('success', 'Banner Updated');

    }
    public function destroy($id)
    {
        $storebanner = StoreBanner::find($id);
        deleteFile(str_replace(env('FILE_URL'), '', $storebanner->thumbnail));
        $this->model->delete($id);
        return redirect()->route('storebanner.index')->with('success', 'Banner Deleted');
    }
}
