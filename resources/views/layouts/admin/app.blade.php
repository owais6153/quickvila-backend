<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QuickVila Dashboard</title>

    @include('layouts.head')
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('layouts.admin.sidebar')
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content" class="mt-5">
                <!-- Topbar -->
                {{-- @include('layouts.admin.topbar') --}}
                <!-- End of Topbar -->
                @yield('content')
            </div>
        </div>
    </div>
    @include('layouts.scripts')

</body>
</head>
