<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Http\Requests\Admin\VideoRequest;
use DataTables;
use Bouncer;

class VideoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view-video', ['index', 'getList']);
        $this->middleware('permission:create-video', ['create', 'store']);
        $this->middleware('permission:edit-video', ['edit', 'update']);
        $this->middleware('permission:delete-video', ['destroy']);
        $this->dir = 'admin.video.';
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
        $model = Video::query();
        return DataTables::eloquent($model)
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Bouncer::can('edit-video')) {
                    $actionBtn .= '<a href="' . route('video.edit', ['video' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
                }
                if (Bouncer::can('delete-video') ) {
                    $actionBtn .= '
                <form style="display:inline-block" method="POST" action="' . route('video.destroy', ['video' => $row->id]) . '">
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
    public function store(VideoRequest $request)
    {
        $videoF = $thumbnail = "";
        if ($request->hasFile('video')) {
            $videoFile = $request->video;
            $file_name = uploadFile($videoFile, videoPath());
            $videoF = $file_name;
        }
        if ($request->hasFile('thumbnail')) {
            $thumbnailFile = $request->thumbnail;
            $file_name = uploadFile($thumbnailFile, imagePath());
            $thumbnail = $file_name;
        }
        $video = Video::create([
            'title' => $request->title,
            'video' => ($videoF != '') ? $videoF :  noImage(),
            'thumbnail' => ($thumbnail != '') ? $thumbnail :  noImage(),
            'sort' => $request->sort,
        ]);

        return redirect()->route('video.index')->with('success', 'Video Created');
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
    public function edit(Video $video)
    {
        return view($this->dir . 'edit', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  Video $video
     * @return \Illuminate\Http\Response
     */
    public function update(VideoRequest $request, Video $video)
    {
        $videoF = $thumbnail = "";
        if ($request->hasFile('video')) {
            $videoFile = $request->video;
            $file_name = uploadFile($videoFile, videoPath());
            $videoF = $file_name;
        }
        if ($request->hasFile('thumbnail')) {
            $thumbnailFile = $request->thumbnail;
            $file_name = uploadFile($thumbnailFile, imagePath());
            $thumbnail = $file_name;
        }
        $video->update([
            'title' => $request->title,
            'video' => ($videoF != '') ? $videoF :  $video->video,
            'thumbnail' => ($thumbnail != '') ? $thumbnail :  $video->thumbnail,
            'sort' => $request->sort,
        ]);

        return redirect()->route('video.index')->with('success', 'Video Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  Video $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        deleteFile(str_replace(env('FILE_URL'),'',$video->video) );
        $video->delete();
        return redirect()->route('video.index')->with('success', 'Video Deleted');
    }
}
