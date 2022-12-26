@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Store Setting</h1>
            <a href="{{ route('store.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"> All
                Stores</a>
        </div>
        <form class="row" action="{{ route('store.setting.update', ['store' => $storeSetting->store_id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-9">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{$storeSetting->store->name}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store Price</label>
                                    <input type="number" name="price" placeholder="Store Price" class="form-control"
                                        value="{{ old('price', $storeSetting->price) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Default Price Condition</label>
                                    <select name="price_condition" placeholder="Default Price Condition" class="form-control">
                                        <option {{ old('price_condition', $storeSetting->price_condition) == 'percentage' ? 'selected=selected' : ''}} value="percentage">Percentage</option>
                                        <option  {{ old('price_condition', $storeSetting->price_condition) == 'price' ? 'selected=selected' : ''}} value="price">Price</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store Radius</label>
                                    <input type="number" name="radius" placeholder="Store Radius" class="form-control"
                                        value="{{ old('radius', $storeSetting->radius) }}">
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" name="" value="Save Setting" placeholder="Store Name"
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
