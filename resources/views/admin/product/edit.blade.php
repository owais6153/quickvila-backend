@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
            <a href="{{ route('product.index', ['store' => $store->id]) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"> All
                Products</a>
        </div>
        <form class="row" action="{{ route('product.update', ['product' => $product->id,'store' => $store->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-9">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Update Product</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" id="p_name" name="name" placeholder="Product Name" class="form-control"
                                        value="{{ old('name', $product->name) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Store</label>
                                    <select name="store" class="form-control">

                                            <option
                                                {{ old('store', $product->store_id) == $store->id ? 'selected=selected' : '' }}
                                                value="{{ $store->id }}">{{ $store->name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Price</label>
                                    <input type="text"  id="p_price"  name="price"
                                        {{ !$product->manage_able ? 'readonly=readonly' : '' }}
                                        placeholder="Product Price" class="form-control"
                                        value="{{ old('price', $product->price) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Sale Price</label>
                                    <input type="text"  id="p_sale_price" name="sale_price" placeholder="Sale Price" class="form-control"
                                        {{ !$product->manage_able ? 'readonly=readonly' : '' }}
                                        value="{{ old('sale_price', $product->sale_price) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product Description</label>
                                    <textarea name="description" class="form-control" placeholder="Description">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Product Short Description</label>
                                    <textarea name="short_description" class="form-control" placeholder="Description">{{ old('short_description', $product->short_description) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Type</label>
                                    <select class="form-control" id="product_type" name="product_type">
                                        <option {{ old('product_type', $product->product_type) == 'simple' ? 'selected=selected' : '' }} value="simple">Simple</option>
                                        <option {{ old('product_type', $product->product_type) == 'variation' ? 'selected=selected' : '' }} value="variation">Variation</option>
                                    </select>
                                </div>
                            </div>

                            @include('admin.product.variation.edit')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" name="" value="Save" placeholder="Product Name"
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
                                    <option value="{{Draft()}}" {{ old('status', $product->status) == Draft() ? 'selected=selected':'' }}>Draft</option>
                                    <option value="{{Published()}}" {{ old('status', $product->status) == Published() ? 'selected=selected':'' }}>Published</option>
                                </select>
                            </div>

                            <div class="custom-control custom-checkbox small">
                                <input type="checkbox" class="custom-control-input" name="is_store_featured"
                                    value="1" id="is_store_featured"
                                    {{ old('is_store_featured', $product->is_store_featured) ? 'checked=checked' : '' }}>
                                <label class="custom-control-label"
                                    for="is_store_featured">Make it featured</label>
                            </div>
                            @can('setting-store')
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" name="is_site_featured"
                                        value="1" id="is_site_featured"
                                        {{ old('is_site_featured', $product->is_site_featured) ? 'checked=checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="is_site_featured">Make it featured on front pages</label>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Product Media</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="form-group uploader">
                                <label>Upload Logo</label>
                                <input type="file" name="image" />
                                <div class="image-uploader"
                                    style="background-image: url('{{ asset('images/camera.png') }}')">
                                    @if ($product->image)
                                        <img src={{ asset($product->image) }} />
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
                                            foreach ($product->categories as $oldcat) {
                                                $old_categories[] = $oldcat->id;
                                            }
                                        }
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

@push('afterScripts')
<script>
    $('#product_type').change(function(){
        if($(this).val() == 'simple')
            $('.variation-card').fadeOut()
        else
            $('.variation-card').fadeIn()
    })

    $('.select2').select2({
        'placeholder': $(this).attr('placeholder'),
        'width': '100%'
    });

    $('#makeVariation').on('click', function(){
        if($('#product_type').val() == 'variation' && $('#attributes').val() != ''){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '{{route('product.variations')}}',
                data :{
                    'variation_attr[]':  $('#attributes').val()
                },
                dataType: 'json',
                // contentType: 'application/json',
                success: function (data) {
                    if(data.status == 200 && data.variants.length > 0 ){
                        $('#variants').html('')
                        for(let i = 0; i < data.variants.length; i++){

                            let name = $('#p_name').val();
                            for (const key in data.variants[i]) {
                                name = `${name} - ${key}: ${data.variants[i][key].name}`;
                            }

                            let price = $('#p_price').val();
                            let saleprice = $('#p_sale_price').val();

                            let html = `<div class="card variant variant-${i} mb-4">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Variation name</label>
                                                            <input type="text" name="variations[${i}][name]" placeholder="Product Name" class="form-control"
                                                                value="${name}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Variation Price</label>
                                                            <input type="text" name="variations[${i}][price]" placeholder="Product Name" class="form-control"
                                                                value="${price}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Sale Price</label>
                                                            <input type="text" name="variations[${i}][sale_price]" placeholder="Product Name" class="form-control"
                                                                value="${saleprice}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="button" class="btn btn-danger" onclick="$(this).parents('.card.variant').remove()">Delete</button>
                                                </div>
                                            </div>
                                        </div>`;
                            let hid = $(`<input type="hidden" name="variations[${i}][variants]" class="json"/>`).val(JSON.stringify(data.variants[i]));
                            $('#variants').append($(html));
                            $(document).find(`.variant-${i}`).append($(hid))
                        }
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    })
</script>
@endpush
