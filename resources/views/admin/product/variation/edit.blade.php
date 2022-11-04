<div class="col-md-12 variation-card" style="{{$product->product_type == 'simple' ? 'display:none;' : ''}}">
    <div class="card  mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product Variations</h6>
        </div>
        <div class="card-body" id="product-variations">
            <div class="row">
                <div class="col-md-12 ">
                    <button type="button" id="addNewVariation" class="mb-4 b btn btn-info">Add New Variation</button>
                    <div id="variations">
                        @foreach ($product->variations as $variationKey => $variation)
                        <div class="card variation-parent mb-3 bg-light">
                            <div class="card-body">
                                <div class="row " >
                                    <input type="hidden" name="variation[{{$variationKey}}][id]" class="variation_id" value="{{$variation->id}}" />
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                        <input type="text" class="form-control" name="variation[{{$variationKey}}][name]" placeholder="Variation Name*" value="{{ $variation->name }}"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <select readonly="readonly" class="form-control variationtype" name="variation[{{$variationKey}}][type]">
                                                <option {{$variation->type == 'custom' ? 'selected=selected' : '' }} value="custom">Custom</option>
                                                <option {{$variation->type == 'color' ? 'selected=selected' : '' }} value="color">Color</option>
                                                <option {{$variation->type == 'size' ? 'selected=selected' : '' }} value="size">Size</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" name="variation[{{$variationKey}}][is_required]"
                                                value="1" {{ $variation->is_required ? 'checked=checked' : '' }}>
                                            <label class="custom-control-label">Is required</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn  btn-danger deleteVariation" type="button"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                    <div class="col-md-3  mb-3">
                                        <button class="btn  btn-info addOption" type="button">Add Option</button>
                                    </div>
                                    <div class="col-md-12 variation_options" data-count="{{$variation->options->count() - 1}}">
                                        @foreach ($variation->options as $optionKey => $option)
                                            @if ($variation->type == 'color')
                                            <div class="card mb-1 variation_options-row">
                                                <input type="hidden" name="variation[{{$variationKey}}][options][{{$optionKey}}][id]" class="option_id" value="{{$option->id}}">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="variation[{{$variationKey}}][options][{{$optionKey}}][value]" placeholder="Variation Value*"  value="{{$option->value}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group align-items-center d-flex form-group">
                                                                <div class="form-color">
                                                                    <input type="color" class="form-control" name="variation[{{$variationKey}}][options][{{$optionKey}}][media]" placeholder="Variation Color*"  value="{{$option->media}}"/>
                                                                </div>
                                                                <label class="ml-2 mb-0">Pick Color</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="variation[{{$variationKey}}][options][{{$optionKey}}][price]" placeholder="Variation Price"  value="{{$option->price}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger deleteOption"><i class="fas fa-trash-alt"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="card mb-1 variation_options-row">
                                                <input type="hidden" name="variation[{{$variationKey}}][options][{{$optionKey}}][id]" class="option_id" value="{{$option->id}}">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="variation[{{$variationKey}}][options][{{$optionKey}}][value]" placeholder="Variation Value*"  value="{{$option->value}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="variation[{{$variationKey}}][options][{{$optionKey}}][price]" placeholder="Variation Price"  value="{{$option->price}}"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger deleteOption"><i class="fas fa-trash-alt"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('afterScripts')
<script>
    var variationCount = {{ $product->variations->count() != 0 ? $product->variations->count() - 1 : 0}};
    $(document).on('click', '#addNewVariation', function(){
        variationCount = variationCount + 1;
        var html = `<div class="card variation-parent mb-3 bg-light">
                        <div class="card-body">
                            <div class="row " >
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                    <input type="text" class="form-control" name="variation[${variationCount}][name]" placeholder="Variation Name*"/>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="form-group">
                                        <select class="form-control variationtype" name="variation[${variationCount}][type]">
                                            <option value="custom">Custom</option>
                                            <option value="color">Color</option>
                                            <option value="size">Size</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" name="variation[${variationCount}][is_required]"
                                            value="1" >
                                        <label class="custom-control-label">Is required</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn  btn-danger deleteVariation" type="button"><i class="fas fa-trash-alt"></i></button>
                                </div>
                                <div class="col-md-3  mb-3">
                                    <button class="btn  btn-info addOption" type="button">Add Option</button>
                                </div>

                                <div class="col-md-12 variation_options" data-count="0">
                                </div>
                            </div>
                        </div>
                    </div>`;
        $('#variations').append($(html));
    })

    $(document).on('click', '.deleteVariation', function(){
        if(variationCount != 0){
            variationCount = variationCount - 1;

            if($(this).parents('.variation-parent').find('.variation_id').length > 0){
                $(this).parents('form').append($(`<input type="hidden" name="delete_variation[]" value="${$(this).parents('.variation-parent').find('.variation_id').val()}" />`))
            }
            $(this).parents('.variation-parent').remove();
        }
    })
    $(document).on('click', '.addOption', function(){

        if(variationCount != 0){

            $(this).parents('.variation-parent').find('.variationtype').attr('readonly', 'readonly');
            var variationType = $(this).parents('.variation-parent').find('.variationtype').val();
            var optionContainer = $(this).parents('.variation-parent').find('.variation_options');
            var variationOptionsCount = $(optionContainer).attr('data-count');
            variationOptionsCount = parseInt(variationOptionsCount) + 1;
            $(optionContainer).attr('data-count', variationOptionsCount);

            var optionColor = `<div class="card mb-1 variation_options-row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="variation[${variationCount}][options][${variationOptionsCount}][value]" placeholder="Variation Value*"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group align-items-center d-flex form-group">
                                            <div class="form-color">
                                                <input type="color" class="form-control" name="variation[${variationCount}][options][${variationOptionsCount}][media]" placeholder="Variation Color*"/>
                                            </div>
                                            <label class="ml-2 mb-0">Pick Color</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="variation[${variationCount}][options][${variationOptionsCount}][price]" placeholder="Variation Price"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger deleteOption"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
            var optionCustom = `<div class="card mb-1 variation_options-row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="variation[${variationCount}][options][${variationOptionsCount}][value]" placeholder="Variation Value*"/>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="variation[${variationCount}][options][${variationOptionsCount}][price]" placeholder="Variation Price"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger deleteOption"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>`;

            if(variationType == 'color'){
                $(optionContainer).append($(optionColor));
            }
            else{
                $(optionContainer).append($(optionCustom));
            }
        }
    })

    $(document).on('click', '.deleteOption', function(){
        var optionContainer = $(this).parents('.variation-parent').find('.variation_options');
            var variationOptionsCount = $(optionContainer).attr('data-count');
            variationOptionsCount = parseInt(variationOptionsCount) - 1;
            $(optionContainer).attr('data-count', variationOptionsCount);

            if($(this).parents('.variation_options-row').find('.option_id').length > 0){
                $(this).parents('form').append($(`<input type="hidden" name="delete_option[]" value="${$(this).parents('.variation_options-row').find('.option_id').val()}" />`))
            }

            if(variationOptionsCount == 0){
                $(this).parents('.variation-parent').find('.variationtype').removeAttr('readonly');
            }
        $(this).parents('.variation_options-row').remove();
    })

    $('#product_type').change(function(){
        if($(this).val() == 'simple')
            $('.variation-card').fadeOut()
        else
            $('.variation-card').fadeIn()
    })


</script>
@endpush
