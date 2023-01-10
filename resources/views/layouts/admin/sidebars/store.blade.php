@if(Bouncer::can('edit-store'))
<li class="nav-item">
    <a class="nav-link" href="{{route("store.edit", ['store' => auth()->user()->store->id])}}" >
        <i class="fas fa-store"></i>
        <span>My Stores</span>
    </a>
</li>
@endif
