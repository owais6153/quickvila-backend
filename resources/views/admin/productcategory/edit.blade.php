@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Update Product Category</h1>
            <a href="{{ route('productcategory.index', ['store' => $store->id]) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                All
                Product Categories</a>
        </div>
        <form class="row" action="{{ route('productcategory.update', ['productcategory' => $productcategory->id, 'store' => $store->id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Update Product Categories</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Category Name</label>
                                    <input type="text" name="name" placeholder="Category Name" class="form-control"
                                        value="{{ old('name', $productcategory->name) }}">
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
