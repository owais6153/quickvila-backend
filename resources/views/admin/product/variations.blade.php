<div class="col-md-12">
    <div class="card  mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product Variations</h6>
        </div>
        <div class="card-body" id="product-variations">
            <div class="row">
                <div class="col-md-12 ">
                    <button type="button" id="addNewVariation" class="mb-4 b btn btn-info">Add New Variation</button>
                    <div id="variations"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('afterScripts')
<script>
    var variationCount =0;
    $(document).on('click', '#addNewVariation', function(){
        variationCount = variationCount + 1;
        var html = `<div class="card variation-parent mb-3 bg-light">
                        <div class="card-body">
                            <div class="row " >
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                    <input type="text" class="form-control" name="variation[0]['name']" placeholder="Variation Name*"/>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="form-group">
                                        <select class="form-control variationtype" name="variation[0]['type']">
                                            <option value="custom">Custom</option>                                            <option value="custom">Custom</option>
                                            <option value="size">Size</option>
                                            <option value="color">Color</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" name="variation[0]['is_required']"
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

                                <div class="col-md-12 variation_options">
                                </div>
                            </div>
                        </div>
                    </div>`;
        $('#variations').append($(html));
    })

    $(document).on('click', '.deleteVariation', function(){
        if(variationCount != 0){
            variationCount = variationCount - 1;
            $(this).parents('.variation-parent').remove();
        }
    })
    $(document).on('click', '.addOption', function(){

        if(variationCount != 0){
            var variationType = $(this).parents('.variation-parent').find('.variationtype').val();
            var optionContainer = $(this).parents('.variation-parent').find('.variation_options');
            var optionColor = `<div class="card mb-1 variation_options-row">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="variation[0]['options'][0]['value']" placeholder="Variation Value*"/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group align-items-center d-flex form-group">
                                            <div class="form-color">
                                                <input type="color" class="form-control" name="variation[0]['options'][0]['media']" placeholder="Variation Color*"/>
                                            </div>
                                            <label class="ml-2 mb-0">Pick Color</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="variation[0]['options'][0]['price']" placeholder="Variation Price*"/>
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
                                            <input type="text" class="form-control" name="variation[0]['options'][0]['value']" placeholder="Variation Value*"/>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="variation[0]['options'][0]['price']" placeholder="Variation Price*"/>
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
        $(this).parents('.variation_options-row').remove();
    })



</script>
@endpush
