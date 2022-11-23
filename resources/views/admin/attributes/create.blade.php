@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Attributes</h1>
            <a href="{{ route('attribute.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                All
                Attributes</a>
        </div>
        <form class="row" action="{{ route('attribute.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Attributes</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Attribute Name</label>
                                    <input type="text" name="name" placeholder="Attribute Name" class="form-control"
                                        value="{{ old('name') }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Attribute Type</label>
                                    <select name="type" name="type" class="form-control">
                                        <option value="custom">Custom</option>
                                        <option value="size">Size</option>
                                        <option value="color">Color</option>
                                    </select>
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
