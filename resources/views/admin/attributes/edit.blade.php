@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Update Attribute</h1>
            <a href="{{ route('attribute.index', ['store'=>$store->id]) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                All
                Attribute</a>
        </div>
        <form class="row" action="{{ route('attribute.update', ['attribute' => $attribute->id, 'store'=>$store->id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Update Attribute</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Attribute Name</label>
                                    <input type="text" name="name" placeholder="Attribute Name" class="form-control"
                                        value="{{ old('name', $attribute->name) }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Attribute Type</label>
                                    <select name="type" name="type" class="form-control">
                                        <option {{old('type', $attribute->type) == 'custom' ? "selected=selected" : ''}}  value="custom">Custom</option>
                                        <option {{old('type', $attribute->type) == 'size' ? "selected=selected" : ''}} value="size">Size</option>
                                        <option {{old('type', $attribute->type) == 'color' ? "selected=selected" : ''}} value="color">Color</option>
                                    </select>
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
