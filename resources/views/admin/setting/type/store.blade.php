<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Default Store Price</label>
                <input type="number" name="setting[default_price]" placeholder="Default Store Price" class="form-control"
                    value="{{old('setting.default_price', $setting['default_price'])}}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Default Price Condition</label>
                <select name="setting[default_price_condition]" placeholder="Default Price Condition" class="form-control"
                    value="old('setting.default_price_condition')">
                    <option value="percentage">Percentage</option>
                    <option value="price">Price</option>
                </select>
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Tax</label>
                <input type="number" name="setting[tax]" placeholder="Tax" class="form-control"
                    value="old('setting.tax')">
            </div>
        </div>
        <div class="col-md-6">

            <div class="form-group">
                <label>Default Radius</label>
                <input type="number" name="setting[default_radius]" placeholder="Default Radius" class="form-control"
                    value="old('setting.default_radius')">
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
