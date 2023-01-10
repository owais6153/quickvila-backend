@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Store</h1>
            <a href="{{ route('store.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"> All
                Stores</a>
        </div>
        <form class="row" action="{{ route('store.update', ['store' => $store->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-9">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Store</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store Name</label>
                                    <input type="text" name="name" placeholder="Store Name" class="form-control"
                                        value="{{ old('name', $store->name) }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" placeholder="Store Address" class="form-control"
                                        value="{{ old('address', $store->address) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="text" name="latitude" placeholder="Store Latitude" class="form-control"
                                        value="{{ old('latitude', $store->latitude) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" name="longitude" placeholder="Store Longitude"
                                        class="form-control" value="{{ old('longitude', $store->longitude) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Store Description</label>
                                    <textarea name="description" class="form-control" placeholder="Description">{{ old('description', $store->description) }}</textarea>
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

                @can('setting-store')
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Addition Store Info</h6>
                    </div>
                    <div class="card-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="{{Draft()}}" {{ old('status', $store->status) == Draft() ? 'selected=selected':'' }}>Draft</option>
                                        <option value="{{Published()}}" {{ old('status',  $store->status) == Published() ? 'selected=selected':'' }}>Published</option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label>Type</label>
                                    <select name="type" class="form-control">
                                        <option value="default" {{ old('status', $store->type) == 'default' ? 'selected=selected':'' }}>Default</option>
                                        <option value="vape" {{ old('status',  $store->type) == 'vape' ? 'selected=selected':'' }}>Vape</option>
                                        <option value="pharmacy" {{ old('status',  $store->type) == 'pharmacy' ? 'selected=selected':'' }}>Pharmacy</option>
                                    </select>

                            </div>
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" name="is_featured"
                                        value="1" id="is_featured"
                                        {{ old('is_featured', $store->is_featured) ? 'checked=checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="is_featured">Make it featured on front pages</label>
                                </div>
                        </div>
                    </div>
                </div>
                @endcan
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
                                    @if ($store->logo)
                                        <img src={{ asset($store->logo) }} />
                                    @endif

                                </div>
                            </div>
                            <div class="form-group uploader">
                                <label>Upload Cover</label>
                                <input type="file" name="cover" />
                                <div class="image-uploader"
                                    style="background-image: url('{{ asset('images/camera.png') }}')">
                                    @if ($store->cover)
                                        <img src={{ asset($store->cover) }} />
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
                                    @php
                                        $old_categories = old('categories') ? old('categories') : [];
                                        if (empty($old_categories)) {
                                            foreach ($store->categories as $oldcat) {
                                                $old_categories[] = $oldcat->id;
                                            }
                                        }

                                        // dd($store->categories);

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
