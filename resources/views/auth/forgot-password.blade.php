<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Eagle+Lake&family=Poppins:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/media.css" rel="stylesheet">
    <title>TequilasPOS</title>
</head>

<body>
    <!--login section start-->
    <section class="login-bg">
        <div class="container-fluid m-0 h-100">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12 ">
                    <div class="login-md-section row">
                        <div class="col-12 col-md-6 col-lg-6 login-bg-image">
                            <div class="overlay">
                                <div class="col-12 col-md-12 col-lg-12 left-side-content">
                                    <h3 class="text-white">Welcome back!</h3>
                                    <p class="text-white">A restaurant POS system is both the software and hardware
                                        restaurants use to take customer's orders, accept payments, manage food
                                        inventory and ultimately manage the entire operation.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 right-form-set">
                            <div class="col-12 col-md-12 col-lg-9 login-heading mb-2">
                                <h4>Forgot Your Password?</h4>
                            </div>
                            @if ($message = Session::get('status'))
                                <div class="alert alert-success alert-dismissible fade show response-msg"
                                    role="alert">
                                    <strong>{{ $message }}</strong>
                                    <button type="button" class="btn-close " data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="col-12 col-md-12 col-lg-9">
                                <form class="mt-3" method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="mb-3 customize-input">
                                        <label for="Email1" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="Email1" type="email"
                                            name="email" :value="old('email')" placeholder="Enter Your Email"
                                            aria-describedby="emailHelp">
                                            @error('email')
                                                <div class="text-danger validation-error">{{ $message }}</div>
                                            @enderror
                                            
                                    </div>
                                    <div class="col-12 col-md-12 col-lg-12">
                                        <button type="submit" class="btn btn-primary float-end w-100  mt-3 mb-4">Reset
                                            Password</button>
                                        <div class="forgot-password text-center">
                                            <a href="{{ route('login') }}">Back to login</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--login section end-->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.0.1/js/all.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>
