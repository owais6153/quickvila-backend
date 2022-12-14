@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <form action="{{route('signup')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" type="email" name="email" />
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" type="text" name="name" />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" type="pasword" name="password" />
                    </div>
                    <div class="form-group">
                        <button class="btn btn-block btn-primary">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

