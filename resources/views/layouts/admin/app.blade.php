<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{env('APP_NAME')}}</title>
        <link href="{{asset('css/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
        <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
        <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="{{asset('js/jquery.toaster.js')}}"></script>
        <!--Page Level Head-->
        @stack('afterCSS')
    </head>
    <body  id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('layouts.admin.sidebar')
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                    @include('layouts.admin.topbar')
                <!-- End of Topbar -->
               @yield('content')
            </div>
        </div>
    </div>
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
        <!-- Core plugin JavaScript-->
        <script src="{{asset('js/jquery-easing/jquery.easing.min.js')}}"></script>
        <!-- Custom scripts for all pages-->
        <script src="{{asset('js/sb-admin-2.min.js')}}"></script>

        <!-- Page Level Script -->
        @stack('afterScripts')
    </body>
</head>
