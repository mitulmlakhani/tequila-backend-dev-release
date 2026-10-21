<span type="button" class="hide" id="sweetAlert"></span>

<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid ps-0">
        <div class="logo-section text-center">
            <a class="navbar-brand" href="#"><img alt="{{ config('app.name', 'Laravel') }}"
                    src="{{ asset('assets/images/logo-bg.png') }}"></a>
        </div>
        <div class="sidebar-toggle ms-3" onclick="sidebar()">
            <img alt="toggle" src="{{ asset('assets/images/toggle.png') }}">
        </div>
        <h4 class="restro_name">
            @if (Auth::user()->user_type == 2)
                @if(empty(Auth::user()->restaurant->parent_id))
                    {{ Auth::user()->restaurant->name }}
                @else
                    {{Auth::user()->restaurant->headRestaurant()->name}} - {{Auth::user()->restaurant->branch_name}}
                @endif
            @endif
        </h4>
        <div class="d-flex ms-auto">
            <div>
                @include('partials.keyboard')
            </div>
            <div class="btn-group user-dropdown">
                <button type="button" class="btn btn-secondary dropdown-toggle bg-white border-0"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    @if (empty(Auth::user()->image))
                        <img class="profile-image" alt="user" src="{{ asset('assets/images/default-user.jpg') }}">
                    @else
                        <img class="profile-image" alt="user"
                            src="{{ asset('images/profiles/' . Auth::user()->image) }}">
                    @endif
                    <span class="text-dark"> {{ isset(Auth::user()->name) ? Auth::user()->name : '' }} </span> <i
                        class="fas fa-chevron-down text-dark"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end dbdeco">
                    <li>
                        <a class="dropdown-item userdb" href="{{ url('profile') }}">
                            Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item userdb"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            style="cursor:pointer">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                    @impersonating($guard = null)
                    <li>
                        <a class="dropdown-item userdb" href="{{ route('impersonate.leave') }}">
                            Logout Location
                        </a>
                    </li>
                    @endImpersonating
                </ul>
            </div>
        </div>
    </div>
</nav>
