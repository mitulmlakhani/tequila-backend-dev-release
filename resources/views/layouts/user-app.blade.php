
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <title>{{ config('app.name', 'Laravel') }}</title>
  
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
  
    <!-- Scripts -->
    
    <link rel="stylesheet" href="{{ asset('build/assets/app-4edc362c.css') }}"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="{{ asset('build/assets/app-4a08c204.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.26/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.26/sweetalert2.css">
    <script  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <!-- select picker css -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css" />

   <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
     <link rel="stylesheet"href="{{asset('css/customcss.css')}}" />
     <link rel="stylesheet" type="text/css" 
     href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
  
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                       
                        <select class="" id="switchRestaurant">
                            @if(isset($rest_arr) && count($rest_arr)>0)
                                @foreach($rest_arr as $k=>$v)
                                    <?php
                                        $attr='';
                                        if(\Session::get('restaurant_id')== $k){
                                            $attr= 'Selected';
                                        }
                                    ?>
                                    <option value="{{$k}}" {{$attr}}>{{$v}}</option>
                                @endforeach
                            @endif
                        </select>
                       
                    </ul>
  
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
  
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                         @if(Gate::check('restaurant-list') || Gate::check('restaurant-create')||Gate::check('restaurant-edit')||Gate::check('restaurant-delete'))
                            <li><a class="nav-link" href="{{ route('restaurants.index') }}">Restaurant</a></li>
                        @endif
                        @if(Gate::check('floor-list') || Gate::check('floor-create')||Gate::check('floor-edit')||Gate::check('floor-delete'))
                            <li><a class="nav-link" href="{{ route('floor.index') }}">Floor</a></li>
                        @endif
                        @if(Gate::check('permission-list') || Gate::check('permission-create')||Gate::check('permission-edit')||Gate::check('permission-delete'))
                           <!--  <li><a class="nav-link" href="{{ route('permissions.index') }}">Permissions</a></li> -->
                        @endif
                        @if(Gate::check('user-list') || Gate::check('user-create')||Gate::check('user-edit')||Gate::check('user-delete'))
                        
                            <li><a class="nav-link" href="{{ route('users.index') }}">Manage Users</a></li>
                        @endif
                       @if(Gate::check('role-list') || Gate::check('role-create')||Gate::check('role-edit')||Gate::check('role-delete'))
                            <li><a class="nav-link" href="{{ route('roles.index') }}">Manage Role</a></li>
                        @endif
                       
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
  
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
  
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                        
                    </ul>
                </div>
            </div>
        </nav>
  
        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
          
    </div>
</body>
@include('layouts.customjs')
</html>