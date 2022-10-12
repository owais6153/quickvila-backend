<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href="{{ asset('css/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">
        <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="{{asset('js/jquery.toaster.js')}}"></script>
        <!--Page Level Head-->
        @stack('afterCSS')
        <style>
        .h-100-vh {
            height: 100vh;
        }
        </style>
    </head>
    <body class="bg-gradient-primary">

        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-md-6 mx-auto h-100-vh align-items-center col-md-6 d-flex flex-wrap justify-content-center mx-auto">

                    <div class="card w-100 o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12 mx-auto">
                                    <div class="p-5">
                                        <div class="text-center mb-4">
                                            <img src="{{asset('/images/Logo.png')}}">
                                        </div>
                                        @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>


    @include('layouts.scripts')
        <!-- Page Level Script -->
        @stack('afterScripts')
    </body>
</head>
