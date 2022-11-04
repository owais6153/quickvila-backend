<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Default Store Price</label>
                <input type="number" name="setting[default_price]" placeholder="Default Store Price" class="form-control"
                @if(old('setting.default_price'))
                value="{{old('setting.default_price')}}"
                @elseif (isset($setting['default_price']))
                value="{{$setting['default_price']}}"
                @endif
                    >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Default Price Condition</label>

                @php
                    $default_price_condition ='';
                @endphp
                @if (old('setting.default_price_condition'))
                    @php
                        $default_price_condition = old('setting.default_price_condition');
                    @endphp
                @elseif (isset($setting['default_price_condition']))
                    @php
                        $default_price_condition = $setting['default_price_condition'];
                    @endphp
                @endif


                <select name="setting[default_price_condition]" placeholder="Default Price Condition" class="form-control"
                    value="old('setting.default_price_condition')">
                    <option {{$default_price_condition == 'percentage' ? 'selected=selected' : ''}} value="percentage">Percentage</option>
                    <option  {{$default_price_condition == 'price' ? 'selected=selected' : ''}} value="price">Price</option>
                </select>
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Tax</label>
                <input type="number" name="setting[tax]" placeholder="Tax" class="form-control"
                @if(old('setting.tax'))
                value="{{old('setting.tax')}}"
                @elseif (isset($setting['tax']))
                value="{{$setting['tax']}}"
                @endif >
            </div>
        </div>
        <div class="col-md-6">

            <div class="form-group">
                <label>Default Radius</label>
                <input type="number" name="setting[default_radius]" placeholder="Default Radius" class="form-control"
                @if(old('setting.default_radius'))
                value="{{old('setting.default_radius')}}"
                @elseif (isset($setting['default_radius']))
                value="{{$setting['default_radius']}}"
                @endif >
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
