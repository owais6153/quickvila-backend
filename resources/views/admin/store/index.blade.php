@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">All Stores</h1>
            <a href="{{route('store.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"> Add New Store</a>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Basic Card Example</h6>
                    </div>
                    <div class="card-body">
                        The styling for this basic card example is created by using default Bootstrap
                        utility classes. By using utility classes, the style of the card component can be
                        easily modified with no need for any custom CSS!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
