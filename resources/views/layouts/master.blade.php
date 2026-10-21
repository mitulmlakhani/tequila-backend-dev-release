<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title> @yield('title') | TequilasPOS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--CSS Section start-->
        @include('layouts.partials.css')
    <!--CSS Section end-->
    
    @yield('css')
    
    <script src="{{ asset('assets/js/jquery-3.5.1.js') }}"></script>
</head>

<body style="zoom: 70%">
    <!--Header section start-->
        @include('layouts.partials.header')
    <!--Header section end-->

    <!--Sidebar Section start-->
        @include('layouts.partials.sidebar')
    <!--Sidebar Section end-->

    @yield('content')

    <!--js Section start-->
        @include('layouts.partials.js')
    <!--js Section end-->

    @yield('js')
</body>

</html>

<div id="loading" class="hideImg">
    <img id="loading-image" src="{{asset('img/loader-images/05.svg')}}" alt="Loading..." />
</div>
