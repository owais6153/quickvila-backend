<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{asset('images/Logo.png')}}" style="    max-width: 100%;
            margin-top: 20px;    height: 60px;">
        </div>
    </a>
    <hr class="sidebar-divider ">
    <div class="sidebar-heading">
        {{ auth()->user()->name }}
    </div>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    @if(Bouncer::is(auth()->user())->an(Admin()) || Bouncer::is(auth()->user())->an(Manager()))
        @include('layouts.admin.sidebars.admin')
    @elseif(Bouncer::is(auth()->user())->an(Store()))
        @include('layouts.admin.sidebars.store')
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span></a>
    </li>
    <hr class="sidebar-divider d-none d-md-block">
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
