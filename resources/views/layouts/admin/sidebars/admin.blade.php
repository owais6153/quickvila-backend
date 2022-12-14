<hr class="sidebar-divider">
<div class="sidebar-heading">
    CATALOG
</div>

@if(Bouncer::can('view-store') || Bouncer::can('create-store') || Bouncer::can('view-store-category') || Bouncer::can('edit-store-category') )
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


          @if(Bouncer::can('view-store-banner') || Bouncer::can('create-store-banner'))
          <h6 class="collapse-header">Product Attributes</h6>

            @can('create-store-banner')
                <a class="collapse-item" href="{{ route('storebanner.create') }}">Add New Banner</a>
            @endcan
            @can('view-store-banner')
                <a class="collapse-item" href="{{ route('storebanner.index') }}">All Banners</a>
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
   All Users
</div>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#users" aria-expanded="true"
        aria-controls="settings"><i class="fas fa-user"></i>
        <span>Users</span>
    </a>
    <div id="users" class="collapse" aria-labelledby="" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            @can('view-user')
            <a class="collapse-item" href="{{ route('user.index') }}">All Users</a>
            <a class="collapse-item" href="{{ route('user.requests') }}">Verification Requests</a>
            @endcan
        </div>
    </div>
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
            <a class="collapse-item" href="{{ route('setting.index', ['key' => 'general']) }}">General</a>
            <a class="collapse-item" href="{{ route('setting.index', ['key' => 'email']) }}">Email</a>
            <a class="collapse-item" href="{{ route('setting.index', ['key' => 'sms']) }}">SMS</a>
            <a class="collapse-item" href="{{ route('setting.index', ['key' => 'store']) }}">Store</a>
            <a class="collapse-item" href="{{ route('setting.index', ['key' => 'tax']) }}">Tax</a>
            <a class="collapse-item" href="{{ route('setting.index', ['key' => 'payment']) }}">Payment</a>
        </div>
    </div>
</li>
@endif
