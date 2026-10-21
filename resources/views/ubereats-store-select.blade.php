<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{--
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">

        <!-- Page Content -->
        <main class="container m-5">
            <form class="form" action="{{ route('ubereats-store-pos-update') }}" method="post">
                @csrf
                <input type="hidden" name="restaurant_id" value="{{ $restaurant_id }}">
                <div class="mb-3">
                    <label for="store_id" class="form-label">Select UberEats Store</label>
                    <select class="form-select" name="store_id" id="store_id" required aria-describedby="storeHelp">
                        <option value="">Select UberEats Store</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store['id'] }}">
                                {{ $store['name'] }} - {{ $store['address'] }}
                            </option>
                        @endforeach
                    </select>
                    <div id="storeHelp" class="form-text">This Uber Eats account is linked to multiple stores. Please select the store you want to connect.</div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>

            </form>
        </main>
    </div>
</body>

</html>