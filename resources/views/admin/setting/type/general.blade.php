<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Default Account Verification Method</label>

                @php
                    $default_verification_method ='';
                @endphp
                @if (old('setting.default_verification_method'))
                    @php
                        $default_verification_method = old('setting.default_verification_method');
                    @endphp
                @elseif (isset($setting['default_verification_method']))
                    @php
                        $default_verification_method = $setting['default_verification_method'];
                    @endphp
                @endif


                <select name="setting[default_verification_method]" placeholder="Default Authentication Method" class="form-control"
                    value="old('setting.default_verification_method')">
                    <option {{$default_verification_method == 0 ? 'selected=selected' : ''}} value="email">Email</option>
                    <option  {{$default_verification_method == 1 ? 'selected=selected' : ''}} value="phone">Phone</option>
                </select>
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
