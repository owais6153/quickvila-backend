@extends('layouts.auth.app')

@section('content')
    <form class="user"  action="{{route('authenticate')}}" method="POST">
        @csrf
        <div class="form-group">
            <input class="form-control form-control-user" type="email" name="email" placeholder="Enter Email*"/>
        </div>
        <div class="form-group">
                <input id="password"  class="form-control  form-control-user" placeholder="Password" name="password" type="password" />
        </div>
        <button class="btn btn-primary btn-user btn-block">Login</button>
    </form>
@endsection

