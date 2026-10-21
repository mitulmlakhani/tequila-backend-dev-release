@extends('layouts.master')
@section('title')
    Items
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Profile</h4>
                        <a href="{{ URL::previous() }}">Back</a>
                    </div>
                </div>

                <div class="col-12 col-md-3 col-lg-3 col-xxl-3">
                    <div class="main-content p-3 ">
                        <div class="avatar-upload">
                            <div class="avatar-edit">
                                <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" data-url="{{route('profile.update-image')}}">
                                <label for="imageUpload">
                                </label>
                            </div>
                            <div class="avatar-preview" @if(!empty($user->image)) style="background-image: url('{{asset('images/profiles/'.$user->image)}}')" @endif>
                                <div id="imagePreview">
                                </div>
                            </div>
                        </div>

                        <div class="profile-details">
                            <h4 class="user_name">{{ $user->name }}</h4>
                            <p class="text-break">{{ $user->email }}</p>
                            <p class="role text-break"><span>{{ ucwords($user->userRole(true)) }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-9 col-lg-9 col-xxl-9">
                <form id="profile_details" method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="main-content p-3 ">
                        <div class="row">
                            <h5>Basic Information</h5>
                            <hr>

                            <div class="mb-3 col-12 col-md-6">
                                <label class="form-label" for="name">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}">
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label class="form-label" for="mobile">Phone</label>
                                <input type="text" id="mobile" name="mobile" class="form-control" value="{{ $user->mobile }}">
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label class="form-label" for="street">Street</label>
                                <input type="text" id="street" name="street" class="form-control" value="{{ $user->street }}">
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label class="form-label" for="city">City</label>
                                <input type="text" id="city" name="city" class="form-control" value="{{ $user->city }}">
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label class="form-label" for="state">State</label>
                                <input type="text" id="state" name="state" class="form-control" value="{{ $user->state }}">
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label class="form-label" for="zip_code">Zip Code</label>
                                <input type="text" id="zip_code" name="zip_code" class="form-control" value="{{ $user->zip_code }}">
                            </div>

                            <div class="mb-3 col-12 col-md-6">
                                <label class="form-label" for="rating_url">Rating URL</label>
                                <input type="url" id="rating_url" name="rating_url" class="form-control" value="{{ $user->rating_url }}">
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>

                    <form id="update_password" method="POST" action="{{ route('profile.update-password') }}">
                        <div class="main-content p-3 mt-3">
                            <div class="row">
                                <h5>Update Password</h5>
                                <hr class="mt-2 mb-3">
                                <div class="row p-0 m-0" id="updatepass">
                                    <div class="mb-3 col-12 col-md-4 col-lg-4 mt-2 position-relative">
                                        <label class="form-label" for="current_password">Current Password</label>
                                        <input type="password" placeholder="Current Password" id="current_password"
                                            class="form-control password" required>
                                        <img src="/assets/images/eye.png" alt="eye" class="eye_icon custom_eye"
                                            role="button">
                                        <img src="/assets/images/eye-cross.png" alt="eye"
                                            class="eye_icon_cross custom_eye d-none" role="button">
                                    </div>
                                    <div class="mb-3 col-12 col-md-4 col-lg-4 mt-2 position-relative">
                                        <label class="form-label" for="new_password">New Password</label>
                                        <input type="password" placeholder="New Password" id="new_password"
                                            class="form-control password" required>
                                        <img src="/assets/images/eye.png" alt="eye" class="eye_icon custom_eye"
                                            role="button">
                                        <img src="/assets/images/eye-cross.png" alt="eye"
                                            class="eye_icon_cross custom_eye d-none" role="button">
                                    </div>

                                    <div class="mb-3 col-12 col-md-4 col-lg-4 mt-2 position-relative">
                                        <label class="form-label" for="confirm_new_password">Confirm New Password</label>
                                        <input type="password" placeholder="Confirm New Password" id="confirm_new_password"
                                            class="form-control password" required>
                                        <img src="assets/images/eye.png" alt="eye"
                                            class="eye_icon custom_eye" role="button">
                                        <img src="assets/images/eye-cross.png" alt="eye"
                                            class="eye_icon_cross custom_eye d-none" role="button">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-12 d-flex align-items-end justify-content-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->
@endsection
@section('js')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.avatar-preview').css('background-image', 'url(' + e.target.result + ')');
                    $('.avatar-preview').hide();
                    $('.avatar-preview').fadeIn(650);
                    $('.profile-image').attr('src',e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function() {
            
            var thisObj = this;
            var formData = new FormData();
            formData.append('image', $('#imageUpload')[0].files[0]);
            formData.append('name', 'MOhit');
            var url = $(this).data('url');
            $.ajax({
                type: 'POST',
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                data: formData,
                success: function(response) {
                    if (response.status == 'success') {
                        readURL(thisObj);
                        showAlert('success','Image updated successfully');
                    }else{
                        showAlert('error',response.message);
                    }
                }
            });
            
        });

        // var changepasswordtoggle = document.getElementById("changepasswordtoggle");
        // var updatepass = document.getElementById("updatepass");
        // changepasswordtoggle.addEventListener("click", changePass);

        function changePass() {
            updatepass.classList.toggle("d-none")
        }

        $(document).on("submit", "#profile_details", function(e) {
    e.preventDefault();
    var data = {
        'name': $("#name").val(),
        'mobile': $("#mobile").val(),
        'street': $("#street").val(),
        'city': $("#city").val(),
        'state': $("#state").val(),
        'zip_code': $("#zip_code").val(),
        'rating_url': $("#rating_url").val(),
    };
    var url = this.action;

    $.ajax({
        type: 'POST',
        url: url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: data,
        success: function(response) {
            if (response.status == 'success') {
                showAlert('success', 'Profile updated successfully');
            } else {
                showAlert('error', response.message);
            }
        }
    });
});


        $(document).on("submit", "#update_password", function(e) {
            e.preventDefault();
            var currentPassword = $("#current_password").val();
            var newPassword = $("#new_password").val();
            var confirmNewPassword = $("#confirm_new_password").val();
            var data = {
                'current_password': currentPassword,
                'new_password': newPassword,
                'confirm_new_password': confirmNewPassword
            };
            var url = this.action;
            console.log(data);
            $.ajax({
                type: 'POST',
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: data,
                success: function(response) {
                    if (response.status == 'success') {
                        showAlert('success','Password updated successfully');
                        $("#current_password").val('');
                        $("#new_password").val('');
                        $("#confirm_new_password").val('');
                    }else{
                        showAlert('error',response.message);
                    }
                }
            });
        });

        $(document).on('click','.eye_icon',function(){
            $(this).addClass("d-none");
            $(this).siblings('.eye_icon_cross').removeClass("d-none")
            $(this).siblings('.password').attr('type','text');
        });

        $(document).on('click','.eye_icon_cross',function(){
            $(this).addClass("d-none");
            $(this).siblings('.eye_icon').removeClass("d-none")
            $(this).siblings('.password').attr('type','password');
        });
    </script>
@endsection
