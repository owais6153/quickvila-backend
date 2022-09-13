<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ env('APP_NAME') }}</div>
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
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        CATALOG
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#store" aria-expanded="true"
            aria-controls="store">
            <i class="fas fa-fw fa-cog"></i>
            <span>Stores</span>
        </a>
        <div id="store" class="collapse" aria-labelledby="" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Stores</h6>
                <a class="collapse-item" href="{{ route('store.create') }}">Add New Store</a>
                <a class="collapse-item" href="{{ route('store.index') }}">All Stores</a>
                <h6 class="collapse-header">Categories</h6>
                <a class="collapse-item" href="{{ route('storecategory.create') }}">Add New Category</a>
                <a class="collapse-item" href="{{ route('storecategory.index') }}">All Categories</a>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#product" aria-expanded="true"
            aria-controls="product">
            <i class="fas fa-fw fa-cog"></i>
            <span>Products</span>
        </a>
        <div id="product" class="collapse" aria-labelledby="" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Products</h6>
                <a class="collapse-item" href="{{ route('product.create') }}">Add New Product</a>
                <a class="collapse-item" href="{{ route('product.index') }}">All Products</a>
                <h6 class="collapse-header">Categories</h6>
                <a class="collapse-item" href="{{ route('productcategory.create') }}">Add New Category</a>
                <a class="collapse-item" href="{{ route('productcategory.index') }}">All Categories</a>
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Addons
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#testimonial" aria-expanded="true"
            aria-controls="testimonial">
            <i class="fas fa-fw fa-cog"></i>
            <span>Testimnonials & Videos</span>
        </a>
        <div id="testimonial" class="collapse" aria-labelledby="" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Testimnonials</h6>
                <a class="collapse-item" href="{{ route('testimonial.create') }}">Add New Testimnonials</a>
                <a class="collapse-item" href="{{ route('testimonial.index') }}">All Testimnonial</a>

                <h6 class="collapse-header">Videos</h6>
                <a class="collapse-item" href="{{ route('video.create') }}">Add New Videos</a>
                <a class="collapse-item" href="{{ route('video.index') }}">All Video</a>
            </div>
        </div>
    </li>
    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Login Screens:</h6>
                <a class="collapse-item" href="login.html">Login</a>
                <a class="collapse-item" href="register.html">Register</a>
                <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="404.html">404 Page</a>
                <a class="collapse-item" href="blank.html">Blank Page</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
