@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Product</h1>
            <a href="{{ route('product.index', ['store' => $store->id]) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"> All
                Products</a>
        </div>
        <form class="row" action="{{ route('product.store', ['store' => $store->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-md-9">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Product</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" name="name" placeholder="Product Name" class="form-control"
                                        value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store</label>
                                    <select name="store" class="form-control">
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Price</label>
                                    <input type="text" name="price" placeholder="Product Price" class="form-control"
                                        value="{{ old('price') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Sale Price</label>
                                    <input type="text" name="sale_price" placeholder="Sale Price" class="form-control"
                                        value="{{ old('sale_price') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product Description</label>
                                    <textarea name="description" class="form-control" placeholder="Description">{{ old('description') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product Short Description</label>
                                    <textarea name="short_description" class="form-control" placeholder="Description">{{ old('short_description') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Type</label>
                                    <select class="form-control" id="product_type" name="product_type">
                                        <option value="simple">Simple</option>
                                        <option value="variation">Variation</option>
                                    </select>
                                </div>
                            </div>

                            @include('admin.product.variation.create')

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
                        <h6 class="m-0 font-weight-bold text-primary">Addition Store Info</h6>
                    </div>
                    <div class="card-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected=selected':'' }}>Draft</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected=selected':'' }}>Published</option>
                                    </select>
                                </div>

                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" name="is_store_featured"
                                        value="1" id="is_store_featured"
                                        {{ old('is_store_featured') ? 'checked=checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="is_store_featured">Make it featured</label>
                                </div>
                                @can('setting-store')
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" name="is_site_featured"
                                            value="1" id="is_site_featured"
                                            {{ old('is_site_featured') ? 'checked=checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="is_site_featured">Make it featured on front pages</label>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Product Media</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="form-group uploader">
                                <label>Upload Logo</label>
                                <input type="file" name="image" />
                                <div class="image-uploader"
                                    style="background-image: url('{{ asset('images/camera.png') }}')">
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
                                    @php
                                        $old_categories = old('categories') ? old('categories') : [];
                                    @endphp
                                    @foreach ($categories as $cat)
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" name="categories[]"
                                                value="{{ $cat->id }}" id="customCheck{{ $cat->id }}"
                                                {{ in_array($cat->id, $old_categories) ? 'checked=checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="customCheck{{ $cat->id }}">{{ $cat->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>
@endsection
