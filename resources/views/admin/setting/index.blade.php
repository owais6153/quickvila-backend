@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ Str::ucfirst($key) }} Settings</h1>
        </div>
        <form class="row" action="{{ route('setting.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="key" value="{{$key}}">
            <div class="col-md-9">
                <div class="card shadow mb-4">
                    @include('admin.setting.type.' . $key, ['setting', $setting])
                </div>
            </div>
        </form>
    </div>
@endsection
