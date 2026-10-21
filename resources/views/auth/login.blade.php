<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/media.css') }}" rel="stylesheet">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        img.custom_eye {
            position: absolute;
            top: 42px;
            width: 30px;
            right: 10px;
        }
    </style>
</head>
<body>
<section class="login-bg">
    <div class="container-fluid m-0 h-100">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="login-md-section row">
                    <div class="col-12 col-md-6 col-lg-6 login-bg-image">
                        <div class="overlay">
                            <div class="col-12 col-md-12 col-lg-12 left-side-content">
                                <h3 class="text-white">Welcome back!</h3>
                                <p class="text-white">A restaurant POS system is both the software and hardware
                                    restaurants use to take customer's orders, accept payments, manage food inventory
                                    and ultimately manage the entire operation.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 right-form-set">
                        <div class="col-12 col-md-12 col-lg-9 logo-loginsection text-center">
                            <img alt="logo" src="{{ asset('assets/images/logo-bg.png') }}">
                        </div>
                        <div class="col-12 col-md-12 col-lg-9 login-heading mb-2">
                            <h4>Log In</h4>
                        </div>
                        <div class="col-12 col-md-12 col-lg-9">
                            @include('layouts.validation-error-msg')
                            @include('layouts.flash-msg')

                            @php
                                $envRestaurantId = env('RESTAURANT_ID');
                                $restaurant = $envRestaurantId ? \App\Models\Restaurant::find($envRestaurantId) : null;
                            @endphp

                            @if($restaurant)
                                <div class="alert alert-info small mb-3">
                                    You are logging in to: <strong>{{ $restaurant->name }}</strong>
                                </div>
                            @endif

                            <form class="mt-3" method="POST" action="{{ route('login') }}">
                                @csrf
                                @if($restaurant)
                                <input type="hidden" name="login_mode" id="login_mode" value="email">
                                <!-- Toggle -->
                                <div class="mb-3">
                                    <label>
                                        <input type="radio" name="login_mode_radio" value="email" checked onchange="toggleLoginFields()"> Login via Email
                                    </label>
                                    <label class="ms-3">
                                        <input type="radio" name="login_mode_radio" value="passcode" onchange="toggleLoginFields()"> Login via Passcode
                                    </label>
                                </div>
                                @endif
                                <!-- Email Login -->
                                <div id="emailLoginFields">
                                    <div class="mb-3 customize-input">
                                        <label for="Email1" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="Email1" name="email" placeholder="Enter Your Email">
                                    </div>
                                    <div class="mb-3 customize-input position-relative">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Your Password">
                                        <img src="{{ asset('assets/images/eye.png') }}" class="eye_icon custom_eye" role="button" alt="eye">
                                        <img src="{{ asset('assets/images/eye-cross.png') }}" class="eye_icon_cross custom_eye d-none" role="button" alt="eye">
                                    </div>
                                </div>

                                <!-- Passcode Login -->
                                <div id="passcodeLoginFields" style="display: none;">
                                    <div class="mb-3 customize-input">
                                        <label for="passcode" class="form-label">Login Passcode</label>
                                        <input type="text" class="form-control" id="passcode" name="passcode" placeholder="Enter your passcode">
                                    </div>
                                </div>

                                <div class="mb-3 form-check d-flex justify-content-between">
                                    <div class="reminder-me">
                                        <input type="checkbox" name="remember" class="form-check-input" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1">Remember Me</label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <div class="forgot-password">
                                            <a href="{{ route('password.request') }}">Forgot Password?</a>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('assets/js/jquery-3.5.1.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
    const emailFields = document.querySelectorAll('#emailLoginFields input');
    const passcodeFields = document.querySelectorAll('#passcodeLoginFields input');

    function toggleLoginFields() {
        const mode = document.querySelector('input[name="login_mode_radio"]:checked').value;

        // sync actual submitted input
        document.getElementById('login_mode').value = mode;

        // show/hide fieldsets
        document.getElementById('emailLoginFields').style.display = mode === 'email' ? 'block' : 'none';
        document.getElementById('passcodeLoginFields').style.display = mode === 'passcode' ? 'block' : 'none';

        // enable/disable fieldsets
        emailFields.forEach(el => el.disabled = mode !== 'email');
        passcodeFields.forEach(el => el.disabled = mode !== 'passcode');
    }

    // Show/hide password
    var eye_icon = document.querySelector(".eye_icon");
    var eye_icon_cross = document.querySelector(".eye_icon_cross");
    var password = document.getElementById("password");

    eye_icon?.addEventListener("click", showPass);
    eye_icon_cross?.addEventListener("click", hidePass);

    function hidePass() {
        eye_icon.classList.remove("d-none");
        eye_icon_cross.classList.add("d-none")
        password.type = "password"
    }

    function showPass() {
        eye_icon.classList.add("d-none");
        eye_icon_cross.classList.remove("d-none")
        password.type = "text"
    }

    // Initialize
    toggleLoginFields();
</script>
</body>
</html>
