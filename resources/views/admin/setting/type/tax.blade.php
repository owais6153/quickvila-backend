<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Platform Fees</label>
                <input type="number" name="setting[platform_fees]" placeholder="Platform Fees" class="form-control"
                @if(old('setting.platform_fees'))
                value="{{old('setting.platform_fees')}}"
                @elseif (isset($setting['platform_fees']))
                value="{{$setting['platform_fees']}}"
                @endif
                    >
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
                @endif
                    >
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
