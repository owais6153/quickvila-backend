@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Attributes Options</h1>
            <a href="{{ route('attributeoption.index', ['attribute' => $attribute->id]) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                All
                Attributes Options</a>
        </div>
        <form class="row" action="{{ route('attributeoption.update', ['attribute' => $attribute->id, 'attributeOption' => $attributesoption->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Attributes Options</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Attribute Name</label>
                                    <input type="text" name="name" placeholder="Attribute Name" class="form-control"
                                        value="{{ old('name', $attributesoption->name) }}">
                                </div>
                            </div>

                            @if($attribute->type == 'color')
                            <div class="col-md-12">
                                <div class="form-group align-items-center d-flex form-group">
                                    <div class="form-color">
                                        <input type="color" class="form-control" name="media" value="{{old('media', $attributesoption->media)}}" placeholder="Variation Color*">
                                    </div>
                                    <label class="ml-2 mb-0">Pick Color</label>
                                </div>
                            </div>
                            @endif

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
