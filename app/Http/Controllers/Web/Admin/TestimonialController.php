<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Http\Requests\Admin\TestimonailRequest;
use DataTables;
use Bouncer;

class TestimonialController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-testimonial', ['index', 'getList']);
        $this->middleware('permission:create-testimonial', ['create', 'store']);
        $this->middleware('permission:edit-testimonial', ['edit', 'update']);
        $this->middleware('permission:delete-testimonial', ['destroy']);
        $this->dir = 'admin.testimonial.';
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
        $model = Testimonial::query();
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('edit-testimonial')) {
                    $actionBtn .= '<a href="' . route('testimonial.edit', ['testimonial' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                }
                if (Bouncer::can('delete-testimonial') ) {
                    $actionBtn .= '
                <form style="display:inline-block" method="POST" action="' . route('testimonial.destroy', ['testimonial' => $row->id]) . '">
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
    public function store(TestimonailRequest $request)
    {
        $image = "";
        if ($request->hasFile('image')) {
            $imageFile = $request->image;
            $file_name = uploadFile($imageFile, imagePath());
            $image = $file_name;
        }
        $testimonial = Testimonial::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => ($image != '') ? $image :  noImage(),
            'description' => $request->description,
            'sort' => $request->sort,
        ]);

        return redirect()->route('testimonial.index')->with('success', 'Testimonial Created');
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
    public function edit(Testimonial $testimonial)
    {
        return view($this->dir . 'edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TestimonailRequest $request, Testimonial $testimonial)
    {

        $image = "";
        if ($request->hasFile('image')) {
            $imageFile = $request->image;
            $file_name = uploadFile($imageFile, imagePath());
            $image = $file_name;
        }
        $testimonial->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image' => ($image != '') ? $image :  str_replace(env('FILE_URL'),'',$testimonial->image),
            'description' => $request->description,
            'sort' => $request->sort,
        ]);

        return redirect()->route('testimonial.index')->with('success', 'Testimonial Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimonial $testimonial)
    {
        deleteFile(str_replace(env('FILE_URL'),'',$testimonial->image) );
        $testimonial->delete();
        return redirect()->route('testimonial.index')->with('success', 'Testimonial Deleted');
    }
}
