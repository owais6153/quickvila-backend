@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
            <a href="{{ route('product.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"> All
                Products</a>
        </div>
        <form class="row" action="{{ route('product.update', ['product' => $product->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-9">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Update Product</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" name="name" placeholder="Product Name" class="form-control"
                                        value="{{ old('name', $product->name) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store</label>
                                    <select name="store" class="form-control">
                                        @foreach ($stores as $store)
                                            <option
                                                {{ old('store', $product->store_id) == $store->id ? 'selected=selected' : '' }}
                                                value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product Description</label>
                                    <textarea name="description" class="form-control" placeholder="Description">{{ old('description', $store->description) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product Short Description</label>
                                    <textarea name="short_description" class="form-control" placeholder="Description">{{ old('short_description', $store->short_description) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" name="" value="Save" placeholder="Product Name"
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
                        <h6 class="m-0 font-weight-bold text-primary">Additional Product Info</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="form-group uploader">
                                <label>Upload Logo</label>
                                <input type="file" name="image" />
                                <div class="image-uploader"
                                    style="background-image: url('{{ asset('images/camera.png') }}')">
                                    @if ($product->image)
                                        <img src={{ asset($product->image) }} />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($categories->count())
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
                        </div>
                        <div class="card-body scrollable-card-body">
                            <div class="col-md-12">
                                <div class="form-group uploader">
                                    <div class="custom-control custom-checkbox small">
                                        @php
                                            $old_categories = old('categories') ? old('categories') : [];
                                        @endphp
                                        @foreach ($categories as $cat)
                                            <input type="checkbox" class="custom-control-input" name="categories[]"
                                                value="{{ $cat->id }}" id="customCheck{{ $cat->id }}"
                                                {{ in_array($cat->id, $old_categories) ? 'checked=checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="customCheck{{ $cat->id }}">{{ $cat->name }}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>
@endsection
