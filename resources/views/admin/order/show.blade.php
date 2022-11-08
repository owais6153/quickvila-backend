@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Order Detail</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Order</h6>
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>
                                Customer Name:
                            </strong>
                                {{$order->customer->name}}
                        </p>
                        <p>
                            <strong>
                                Customer Email:
                            </strong>
                                {{$order->customer->email}}
                        </p>
                        <p>
                            <strong>
                                Customer Phone:
                            </strong>
                                {{$order->customer->phone}}
                        </p>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead class="bg-primary text-light">
                                    <tr>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Product Price</th>
                                        <th scope="col">Product Quantity</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($my_store_items as $item)
                                        <tr>
                                            <td>
                                                {{$item->product->name}}
                                            </td>
                                            <td>
                                                {{$item->product->price}}
                                            </td>
                                            <td>
                                                {{$item->qty}}
                                            </td>
                                            <td><button class="btn btn-danger disabled" disabled="disabled">Refund</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
