@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Update Store Category</h1>
            <a href="{{ route('storecategory.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                All
                Store Categories</a>
        </div>
        <form class="row" action="{{ route('storecategory.update', ['storecategory' => $storecategory->id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Update Store Categories</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Category Name</label>
                                    <input type="text" name="name" placeholder="Category Name" class="form-control"
                                        value="{{ old('name', $storecategory->name) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group uploader">
                                    <label>Upload Image</label>
                                    <input type="file" name="image" />
                                    <div class="image-uploader"
                                        style="background-image: url('{{ asset('images/camera.png') }}')">
                                        @if ($storecategory->image)
                                            <img src={{ asset($storecategory->image) }} />
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" name="" value="Save" class="btn btn-primary btn-block">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
