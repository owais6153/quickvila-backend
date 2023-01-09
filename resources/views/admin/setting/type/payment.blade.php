<div class="card-body">
    <div class="row">
        <div class="col-12"><h3>Paypal</h3></div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Paypal Mode</label>
                @php
                    $paypal_mode ='';
                @endphp
                @if (old('setting.paypal_mode'))
                    @php
                        $paypal_mode = old('setting.paypal_mode');
                    @endphp
                @elseif (isset($setting['paypal_mode']))
                    @php
                        $paypal_mode = $setting['paypal_mode'];
                    @endphp
                @endif


                <select name="setting[paypal_mode]" placeholder="" class="form-control"
                    value="old('setting.paypal_mode')">
                    <option {{$paypal_mode == 0 ? 'selected=selected' : ''}} value="sandbox">Sandbox</option>
                    <option  {{$paypal_mode == 1 ? 'selected=selected' : ''}} value="live">Live</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Paypal Account</label>
                <input type="text" name="setting[paypal_account]" placeholder="Account" class="form-control"
                @if(old('setting.paypal_account'))
                value="{{old('setting.paypal_account')}}"
                @elseif (isset($setting['paypal_account']))
                value="{{$setting['paypal_account']}}"
                @endif
                    >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Paypal Secret</label>
                <input type="text" name="setting[paypal_secret]" placeholder="Secret" class="form-control"
                @if(old('setting.paypal_secret'))
                value="{{old('setting.paypal_secret')}}"
                @elseif (isset($setting['paypal_secret']))
                value="{{$setting['paypal_secret']}}"
                @endif
                    >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Paypal Client ID</label>
                <input type="text" name="setting[paypal_client_id]" placeholder="Client ID" class="form-control"
                @if(old('setting.paypal_client_id'))
                value="{{old('setting.paypal_client_id')}}"
                @elseif (isset($setting['paypal_client_id']))
                value="{{$setting['paypal_client_id']}}"
                @endif
                    >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Paypal Currency</label>
                <input  readonly="readonly" type="text" name="setting[paypal_currency]" placeholder="Currency" class="form-control"
                @if(old('setting.paypal_currency'))
                value="{{old('setting.paypal_currency')}}"
                @elseif (isset($setting['paypal_currency']))
                value="{{$setting['paypal_currency']}}"
                @endif
                    >
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Paypal Return Url</label>
                <input readonly="readonly" type="url" name="setting[success_url]" placeholder="Return Url" class="form-control"
                @if(old('setting.success_url'))
                value="{{old('setting.success_url')}}"
                @elseif (isset($setting['success_url']))
                value="{{$setting['success_url']}}"
                @endif
                    >
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Paypal Cancel Url</label>
                <input readonly="readonly" type="url" name="setting[cancel_url]" placeholder="Cancel Url" class="form-control"
                @if(old('setting.cancel_url'))
                value="{{old('setting.cancel_url')}}"
                @elseif (isset($setting['cancel_url']))
                value="{{$setting['cancel_url']}}"
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
