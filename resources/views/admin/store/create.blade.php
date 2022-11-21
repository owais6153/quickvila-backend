@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Store</h1>
            <a href="{{ route('store.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"> All
                Stores</a>
        </div>
        <form class="row" action="{{ route('store.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-md-9">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Add Store</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store Name</label>
                                    <input type="text" name="name" placeholder="Store Name" class="form-control"
                                        value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" placeholder="Store Address" class="form-control"
                                        value="{{ old('address') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="text" name="latitude" placeholder="Store Latitude" class="form-control"
                                        value="{{ old('latitude') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" name="longitude" placeholder="Store Longitude"
                                        class="form-control" value="{{ old('longitude') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Store Description</label>
                                    <textarea name="description" class="form-control" placeholder="Description">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Store Credentials</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store Email</label>
                                    <input type="text" name="email" placeholder="Store Email" class="form-control"
                                        value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store Password</label>
                                    <input type="password" name="password" placeholder="Store Password" class="form-control"
                                        value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control"
                                        value="">
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
                        <h6 class="m-0 font-weight-bold text-primary">Addition Store Info</h6>
                    </div>
                    <div class="card-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="{{Draft()}}" {{ old('status') == Draft() ? 'selected=selected':'' }}>Draft</option>
                                        <option value="{{Published()}}" {{ old('status') == Published() ? 'selected=selected':'' }}>Published</option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label>Type</label>
                                    <select name="type" class="form-control">
                                        <option value="default" {{ old('status') == 'default' ? 'selected=selected':'' }}>Default</option>
                                        <option value="adult" {{ old('status') == 'adult' ? 'selected=selected':'' }}>Adult</option>
                                        <option value="pharmacy" {{ old('status') == 'pharmacy' ? 'selected=selected':'' }}>Pharmacy</option>
                                    </select>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Store Media</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="form-group uploader">
                                <label>Upload Logo</label>
                                <input type="file" name="logo" />
                                <div class="image-uploader"
                                    style="background-image: url('{{ asset('images/camera.png') }}')">
                                </div>
                            </div>
                            <div class="form-group uploader">
                                <label>Upload Cover</label>
                                <input type="file" name="cover" />
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
