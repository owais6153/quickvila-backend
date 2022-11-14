<div class="card-body">
    <div class="row">
        <div class="col-md-6">
        <div class="form-group">
            <label>Allow Sending Email</label>

            @php
                $should_send ='';
            @endphp
            @if (old('setting.should_send'))
                @php
                    $should_send = old('setting.should_send');
                @endphp
            @elseif (isset($setting['should_send']))
                @php
                    $should_send = $setting['should_send'];
                @endphp
            @endif


            <select name="setting[should_send]" placeholder="Allow Sending Email" class="form-control"
                value="old('setting.should_send')">
                <option {{$should_send == 0 ? 'selected=selected' : ''}} value="0">Disable</option>
                <option  {{$should_send == 1 ? 'selected=selected' : ''}} value="1">Enable</option>
            </select>
        </div>        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Host</label>
                <input type="text" name="setting[host]" placeholder="Host" class="form-control"
                @if(old('setting.host'))
                value="{{old('setting.host')}}"
                @elseif (isset($setting['host']))
                value="{{$setting['host']}}"
                @endif
                    >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Port</label>
                <input type="number" name="setting[port]" placeholder="Port" class="form-control"
                @if(old('setting.port'))
                value="{{old('setting.port')}}"
                @elseif (isset($setting['port']))
                value="{{$setting['port']}}"
                @endif
                    >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="setting[username]" placeholder="Username" class="form-control"
                @if(old('setting.username'))
                value="{{old('setting.username')}}"
                @elseif (isset($setting['username']))
                value="{{$setting['username']}}"
                @endif
                    >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="setting[password]" placeholder="Password" class="form-control"
                @if(old('setting.password'))
                value="{{old('setting.password')}}"
                @elseif (isset($setting['password']))
                value="{{$setting['password']}}"
                @endif
                    >
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Encryption</label>

                @php
                    $encryption ='';
                @endphp
                @if (old('setting.encryption'))
                    @php
                        $encryption = old('setting.encryption');
                    @endphp
                @elseif (isset($setting['encryption']))
                    @php
                        $encryption = $setting['encryption'];
                    @endphp
                @endif


                <select name="setting[encryption]" placeholder="Encryption" class="form-control"
                    value="old('setting.encryption')">
                    <option {{$encryption == 'ssl' ? 'selected=selected' : ''}} value="ssl">SSL</option>
                    <option  {{$encryption == 'tls' ? 'selected=selected' : ''}} value="tls">TLS</option>
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
