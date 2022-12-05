@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Store Banner</h1>
            <a href="{{ route('storebanner.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                All
                Store Banners</a>
        </div>
        <form class="row" action="{{route('storebanner.update', ['storebanner' => $storebanner->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Update Store Banner</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Banner Title</label>
                                    <input type="text" name="title" placeholder="Title" class="form-control"
                                        value="{{ old('title', $storebanner->title) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Banner Subtitle</label>
                                    <input type="text" name="subtitle" placeholder="Subtitle" class="form-control"
                                        value="{{ old('subtitle', $storebanner->subtitle) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Action</label>
                                    <input type="url" name="action" placeholder="Action" class="form-control"
                                        value="{{ old('action', $storebanner->action) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store</label>
                                    <select name="store_id" class="form-control">
                                        @foreach ($stores as $store)
                                            <option {{$storebanner->store_id == $store->id ? 'selected=selected' : ''}} value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group uploader">
                                    <label>Upload Image</label>
                                    <input type="file" name="image" />
                                    <div class="image-uploader"
                                        style="background-image: url('{{ asset('images/camera.png') }}')">
                                        <img src="{{asset($storebanner->thumbnail)}}">
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
        </form>
    </div>
@endsection
