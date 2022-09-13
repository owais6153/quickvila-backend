@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Video</h1>
            <a href="{{ route('video.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                All
                Videos</a>
        </div>
        <form class="row" action="{{ route('video.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-md-9">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Videos</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" placeholder="Title" class="form-control"
                                        value="{{ old('title') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sort</label>
                                    <input type="number" name="sort" placeholder="Sort" class="form-control"
                                        value="{{ old('sort') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group uploader">
                                    <label>Upload Video</label>
                                    <input type="file" name="video" />
                                    <div class="image-uploader video-uploader"
                                        style="background-image: url('{{ asset('images/camera.png') }}')">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" name="" value="Save" placeholder="Store Name"
                                        class="btn btn-primary btn-block">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Image</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="form-group uploader">
                                <label>Upload Video Thumbnail</label>
                                <input type="file" name="thumbnail" />
                                <div class="image-uploader"
                                    style="background-image: url('{{ asset('images/camera.png') }}')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
