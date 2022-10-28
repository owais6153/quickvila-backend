<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>
        @include('layouts.head')
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
                                            <img src="{{asset('/images/Logo.png')}}" style="width: 200px">
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
