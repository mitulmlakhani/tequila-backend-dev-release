@extends('layouts.master')
@section('title')
    Restaurant Address Settings
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12">
                    <div class="main-heading">
                        <h4>Restaurant Address Settings</h4>
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12">
                    <div class="main-content p-3">
                        <form action="{{ route('restaurant.storeAddressSettings') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Logo Section -->
                                <div class="col-md-3 text-center">
                                    @if(!empty($restaurantAddress->logo))
                                        <!-- Display the current logo -->
                                        <img src="{{ $restaurantAddress->logo_url }}" alt="Restaurant Logo" id="currentLogo" class="img-fluid mb-2" style="cursor: pointer;">
                                        <input type="hidden" name="existing_logo" value="{{ $restaurantAddress->logo }}">
                                    @endif
                                    <label for="logo">Restaurant Logo</label>
                                    <input type="file" name="logo" class="form-control" id="logoInput">
                                    <p class="text-danger" id="fileSizeError" style="display: none;">The image size must be less than 300 KB.</p>
                                </div>
                                <!-- Form Fields Section -->
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="street">Business Name</label>
                                            <input type="text" name="title" value="{{ old('title', $restaurantAddress->title ?? '') }}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="street">Street</label>
                                            <input type="text" name="street" value="{{ old('street', $restaurantAddress->street ?? '') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label for="city">City</label>
                                            <input type="text" name="city" value="{{ old('city', $restaurantAddress->city ?? '') }}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="state">State</label>
                                            <input type="text" name="state" value="{{ old('state', $restaurantAddress->state ?? '') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label for="zip_code">Zip Code</label>
                                            <input type="text" name="zip_code" value="{{ old('zip_code', $restaurantAddress->zip_code ?? '') }}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" value="{{ old('phone', $restaurantAddress->phone ?? '') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" value="{{ old('email', $restaurantAddress->email ?? '') }}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="rating_url">Website</label>
                                            <input type="url" name="rating_url" value="{{ old('rating_url', $restaurantAddress->rating_url ?? '') }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @can('restaurant.storeAddressSettings')
                            <button type="submit" class="btn btn-primary mt-4">Save</button>
                            @endcan
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!empty($restaurantAddress->logo))
    <!-- Modal for displaying larger image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Restaurant Logo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Display the logo in full size -->
                    <img src="{{ asset('storage/' . $restaurantAddress->logo) }}" alt="Restaurant Logo" id="modalLogo" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <script>
        document.getElementById('currentLogo').addEventListener('click', function() {
            // Show the modal when the logo is clicked
            var modal = new bootstrap.Modal(document.getElementById('imageModal'), {
                keyboard: true
            });
            modal.show();
        });
    </script>

    <script>
        document.getElementById('logoInput').addEventListener('change', function() {
            const file = this.files[0];
            const maxSize = 300 * 1024; // 300 KB in bytes
            const errorElement = document.getElementById('fileSizeError');

            if (file && file.size > maxSize) {
                errorElement.style.display = 'block';
                this.value = ''; // Clear the input
            } else {
                errorElement.style.display = 'none';
            }
        });
    </script>
@endsection
