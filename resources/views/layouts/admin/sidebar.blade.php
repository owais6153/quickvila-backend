<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">QuickVila</div>
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

    @if(Bouncer::can('view-store') || Bouncer::can('create-store') || Bouncer::can('view-store-category') || Bouncer::can('edit-store-category'))
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#store" aria-expanded="true"
            aria-controls="store">
            <i class="fas fa-store"></i>
            <span>Stores</span>
        </a>
        <div id="store" class="collapse" aria-labelledby="" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              @if(Bouncer::can('view-store') || Bouncer::can('create-store'))
                <h6 class="collapse-header">Stores</h6>
                @can('create-store')
                    <a class="collapse-item" href="{{ route('store.create') }}">Add New Store</a>
                @endcan
                @can('view-store')
                    <a class="collapse-item" href="{{ route('store.index') }}">All Stores</a>
                @endcan
              @endif

              @if(Bouncer::can('view-store-category') || Bouncer::can('create-store-category'))
                <h6 class="collapse-header">Store Categories</h6>
                @can('create-store-category')
                    <a class="collapse-item" href="{{ route('storecategory.create') }}">Add New Category</a>
                @endcan
                @can('view-store-category')
                    <a class="collapse-item" href="{{ route('storecategory.index') }}">All Categories</a>
                @endcan
              @endif

              @if(Bouncer::can('view-product-category') || Bouncer::can('create-product-category'))
              <h6 class="collapse-header">Product Categories</h6>

              @can('create-product-category')
                  <a class="collapse-item" href="{{ route('productcategory.create') }}">Add New Category</a>
              @endcan
              @can('view-product-category')
                  <a class="collapse-item" href="{{ route('productcategory.index') }}">All Categories</a>
              @endcan
          @endif
            </div>
        </div>
    </li>
    @endif


    @if(Bouncer::can('view-order'))
    <!-- Divider -->
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
       Orders
    </div>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('order.index') }}" ><i class="fas fa-shopping-cart"></i>
            <span>Orders</span>
        </a>
    </li>
    @endif

    @if(Bouncer::can('view-user'))
    <!-- Divider -->
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
       {{Bouncer::can('all-users') ? 'Users / Customers' : 'Customers'}}
    </div>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('user.index') }}" ><i class="fas fa-user"></i>
            <span>
                {{Bouncer::can('all-users') ? 'Users / Customers' : 'Customers'}}
            </span>
        </a>
    </li>
    @endif


    @if(Bouncer::can('view-testimonial') || Bouncer::can('create-testimonial') || Bouncer::can('view-video') || Bouncer::can('edit-video'))
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

                @if(Bouncer::can('view-testimonial') || Bouncer::can('create-testimonial'))
                    <h6 class="collapse-header">Testimnonials</h6>
                    @can('create-testimonial')
                        <a class="collapse-item" href="{{ route('testimonial.create') }}">Add New Testimnonials</a>
                    @endcan
                    @can('view-testimonial')
                        <a class="collapse-item" href="{{ route('testimonial.index') }}">All Testimnonial</a>
                    @endcan
                @endif
                @if(Bouncer::can('view-video') || Bouncer::can('create-video'))
                    <h6 class="collapse-header">Videos</h6>
                    @can('create-video')
                    <a class="collapse-item" href="{{ route('video.create') }}">Add New Videos</a>
                    @endcan
                    @can('create-video')
                    <a class="collapse-item" href="{{ route('video.index') }}">All Video</a>
                    @endcan
                @endif
            </div>
        </div>
    </li>
    @endif


    @if(Bouncer::can('view-setting') || Bouncer::can('edit-setting'))
    <!-- Divider -->
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Site Settings
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#settings" aria-expanded="true"
            aria-controls="settings">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span>
        </a>
        <div id="settings" class="collapse" aria-labelledby="" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('setting.index', ['key' => 'email']) }}">Email</a>
                <a class="collapse-item" href="{{ route('setting.index', ['key' => 'store']) }}">Store</a>
            </div>
        </div>
    </li>
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
