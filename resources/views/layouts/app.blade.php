<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="{{asset('js/jquery.toaster.js')}}"></script>
        <!--Page Level Head-->
        @stack('afterCSS')
    </head>
    <body>
        @yield('content')
        <!-- Global Script -->
        <script>
            @if($errors->any())
                @foreach($errors->all() as $error)
                    $.toaster({
                        priority: 'danger',
                        title: 'Error',
                        message: '{{$error}}'
                    });
                @endforeach
            @endif
            @if (Session::has('success'))
                $.toaster({
                    priority: 'success',
                    title: 'Success',
                    message: '{{session()->get('success')}}'
                });
            @endif
        </script>
        <!-- Page Level Script -->
        @stack('afterScripts')
    </body>
</head>
