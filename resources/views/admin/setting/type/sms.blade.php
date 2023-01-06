<div class="card-body">
    <div class="row">        <div class="col-md-6">
        <div class="form-group">
            <label>Allow Sending Phone</label>

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


            <select name="setting[should_send]" placeholder="Allow Sending Phone" class="form-control"
                value="old('setting.should_send')">
                <option {{$should_send == 0 ? 'selected=selected' : ''}} value="0">Disable</option>
                <option  {{$should_send == 1 ? 'selected=selected' : ''}} value="1">Enable</option>
            </select>
        </div>        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>TWILIO_SID</label>
                <input type="text" name="setting[sid]" placeholder="TWILIO_SID" class="form-control"
                @if(old('setting.sid'))
                value="{{old('setting.sid')}}"
                @elseif (isset($setting['sid']))
                value="{{$setting['sid']}}"
                @endif
                    >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Token</label>
                <input type="text" name="setting[token]" placeholder="Token" class="form-control"
                @if(old('setting.token'))
                value="{{old('setting.token')}}"
                @elseif (isset($setting['token']))
                value="{{$setting['token']}}"
                @endif
                    >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Number</label>
                <input type="text" name="setting[number]" placeholder="Number" class="form-control"
                @if(old('setting.number'))
                value="{{old('setting.number')}}"
                @elseif (isset($setting['number']))
                value="{{$setting['number']}}"
                @endif
                    >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Whatsapp Number</label>
                <input type="text" name="setting[whatsapp]" placeholder="Number" class="form-control"
                @if(old('setting.whatsapp'))
                value="{{old('setting.whatsapp')}}"
                @elseif (isset($setting['whatsapp']))
                value="{{$setting['whatsapp']}}"
                @endif
                    >
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Messagin Service</label>
                <input type="text" name="setting[messaging_service]" placeholder="Password" class="form-control"
                @if(old('setting.messaging_service'))
                value="{{old('setting.messaging_service')}}"
                @elseif (isset($setting['messaging_service']))
                value="{{$setting['messaging_service']}}"
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
