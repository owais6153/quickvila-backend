@php
    // dd($product->variations);
@endphp

<div class="col-md-12 variation-card" style="{{ $product->product_type == 'simple' ?  'display:none;' :''}}">
    <div class="card  mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product Variations</h6>
        </div>
        <div class="card-body" id="product-variations">
            <div id="variations" class="row" >
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Product Attributes</label>
                        <select class="form-control select2" id="attributes" name="p_attributes[]" multiple placeholer="Select Attributes">
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>ACTION</label>
                    <button type="button" class="btn btn-block btn-primary" id="makeVariation">Make Variations out of attr</button>
                </div>
                <div class="col-12" id="variants">

                    @foreach ($product->variations as $i =>  $variation)
                    <div class="card variant variant-{{$i}} mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Variation name</label>
                                        <input type="text" name="variations[{{$i}}][name]" placeholder="Product Name" class="form-control"
                                            value="{{$variation->name}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Variation Price</label>
                                        <input type="text" name="variations[{{$i}}][price]" placeholder="Product Name" class="form-control"
                                            value="{{$variation->price}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sale Price</label>
                                        <input type="text" name="variations[{{$i}}][sale_price]" placeholder="Product Name" class="form-control"
                                            value="{{$variation->sale_price}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-danger" onclick="$(this).parents('.card.variant').remove()">Delete</button>
                            </div>
                            <input type="hidden" name="variations[{{$i}}][variants]" class="json" value="{{ json_encode($variation->variants) }}"/>
                        </div>
                    </div>
                    @endforeach


                </div>
            </div>
        </div>
    </div>
</div>
