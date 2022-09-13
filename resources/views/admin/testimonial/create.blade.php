@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Testimonial</h1>
            <a href="{{ route('testimonial.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                All
                Testimonials</a>
        </div>
        <form class="row" action="{{ route('testimonial.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-md-9">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Testimonials</h6>
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
                                    <label>Subtitle</label>
                                    <input type="text" name="subtitle" placeholder="Subtitle" class="form-control"
                                    value="{{ old('subtitle') }}">
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
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" placeholder="Description">{{ old('description') }}</textarea>
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
                                <label>Upload Testimonial Image</label>
                                <input type="file" name="image" />
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
