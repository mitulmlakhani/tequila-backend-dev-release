@extends('layouts.master')
@section('title', 'Menu Import/Export Management')

@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="main-heading">
                <h4>Menu Import/Export Management</h4>
            </div>

            <!-- Restaurant Selection -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="restaurant_select" class="form-label">Select Restaurant</label>
                    <select id="restaurant_select" class="form-select">
                        <option value="">Select a Restaurant</option>
                        @foreach ($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}">{{ ucwords($restaurant->parentRestaurant ? ($restaurant->parentRestaurant->name . ': ' . $restaurant->branch_name) : $restaurant->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="main-content p-3 bg-white shadow-sm rounded">
                <!-- Import Menu -->
                <h5>Import Menu</h5>
                <form id="import_menu_form" enctype="multipart/form-data" method="POST">
                    @csrf
                    <input type="hidden" name="restaurant_id" id="import_restaurant_id">

                    <div class="row">
                        <div class="col-md-6">
                            <input type="file" name="menufile" id="menufile" class="form-control mb-2" required>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" value="1" name="force_import" id="force_import">
                                <label class="form-check-label" for="force_import">
                                    Force Import (Delete Existing Data and Impoert Fresh)
                                </label>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <button type="submit" id="import_menu_btn" class="btn btn-primary">
                                <span class="spinner-border spinner-border-sm d-none" id="import_loader"></span>
                                Import Menu
                            </button>
                        </div>
                    </div>
                </form>

                <hr>

                <!-- Export Menu -->
                <h5>Export Menu</h5>
                <button id="export_menu_btn" class="btn btn-success">
                    <span class="spinner-border spinner-border-sm d-none" id="export_loader"></span>
                    Export Menu
                </button>

                <button id="export_to_kwickpos_menu_btn" class="btn btn-info ms-2">
                    <span class="spinner-border spinner-border-sm d-none" id="export_to_kwickpos_loader"></span>
                    Export Menu to KP Structure
                </button>

                <hr />

                <h5>Convert Other's Excel to TequilaPOS</h5>
                <form action="{{ route('super-admin.menu.convert') }}" id="menu-convert" enctype="multipart/form-data" method="POST">
                    @csrf
                    <input type="hidden" name="restaurant_id" id="import_restaurant_id">

                    <div class="row">
                        <div class="col-md-6">
                            <input type="file" name="menufile" id="menufile" class="form-control mb-2" required>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" id="import_menu_btn" class="btn btn-primary">
                                <span class="spinner-border spinner-border-sm d-none" id="import_loader"></span>
                                Convert Menu
                            </button>
                        </div>
                    </div>
                </form>

                <hr>

                <h5>Export DB for Local Server Setup</h5>
                <button id="export_db_local_server_btn" class="btn btn-warning">
                    <span class="spinner-border spinner-border-sm d-none" id="export_db_local_server_loader"></span>
                    Export DB
                </button>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function waitForFileDownload(url, filename) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();

                xhr.open('GET', url, true);
                xhr.responseType = 'blob';

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const blob = xhr.response;
                        const link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;
                        link.click();

                        // Clean up
                        window.URL.revokeObjectURL(link.href);
                        resolve('Download completed');
                    } else {
                        reject('Download failed');
                    }
                };

                xhr.onerror = function () {
                    reject('Download error');
                };

                xhr.send();
            });
        }

        $(document).ready(function () {
            $('#restaurant_select').on('change', function () {
                let restaurantId = $(this).val();
                $('#import_restaurant_id').val(restaurantId);
            });

            // Import Menu Form Submission
            $('#import_menu_form').on('submit', function (e) {
                e.preventDefault();

                let restaurantId = $('#import_restaurant_id').val();
                let restaurantName = $('#restaurant_select option:selected').text();
                console.log({ restaurantName });
                let file = $('#menufile').val();

                if (!restaurantId) {
                    toastr.error('Please select a restaurant first.');
                    return false;
                }

                if (!file) {
                    toastr.error('Please select a file.');
                    return false;
                }

                if (!confirm(`ARE YOU SURE, You Want to Replace the Menu to ${restaurantName} ??? `)) {
                    return false;
                }

                $('#import_menu_btn').prop('disabled', true);
                $('#import_loader').removeClass('d-none');

                waitForFileDownload(
                    '{{ route('super-admin.menu.export') }}?restaurant_id=' + restaurantId,
                    restaurantName.replace(" ", '_') + '_' + Date.now() + "_menu-export_bkp.xlsx"
                ).then(() => {
                    setTimeout(() => {
                        $('#import_loader').removeClass('d-none');
                        importMenu(this);
                    }, 500);
                }).catch((error) => {
                    toastr.error('Error during file download.');
                    $('#import_loader').addClass('d-none');
                });

                function importMenu(form) {
                    console.log('Importing menu...');
                    $('#import_menu_btn').prop('disabled', true);
                    $('#import_loader').removeClass('d-none');

                    let formData = new FormData(form);
                    $.ajax({
                        url: '{{ route('super-admin.menu.import') }}',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            toastr.success(response.message);
                            $('#import_menu_btn').prop('disabled', false);
                            $('#import_loader').addClass('d-none');
                        },
                        error: function (error) {
                            toastr.error('Error importing menu.');
                            $('#import_menu_btn').prop('disabled', false);
                            $('#import_loader').addClass('d-none');
                        }
                    });
                }
            });

            $("#export_to_kwickpos_menu_btn").on('click', function () {
                let restaurantId = $('#restaurant_select').val();

                if (!restaurantId) {
                    toastr.error('Please select a restaurant first.');
                    return;
                }

                $('#export_to_kwickpos_menu_btn').prop('disabled', true);
                $('#export_to_kwickpos_loader').removeClass('d-none');

                window.location.href = '{{ route('super-admin.menu.export-to-kwickpos') }}?restaurant_id=' + restaurantId;

                setTimeout(function () {
                    $('#export_to_kwickpos_menu_btn').prop('disabled', false);
                    $('#export_to_kwickpos_loader').addClass('d-none');
                }, 2000);
            });

            // Export Menu Button Click
            $('#export_menu_btn').on('click', function () {
                let restaurantId = $('#restaurant_select').val();

                if (!restaurantId) {
                    toastr.error('Please select a restaurant first.');
                    return;
                }

                $('#export_menu_btn').prop('disabled', true);
                $('#export_loader').removeClass('d-none');

                window.location.href = '{{ route('super-admin.menu.export') }}?restaurant_id=' + restaurantId;

                setTimeout(function () {
                    $('#export_menu_btn').prop('disabled', false);
                    $('#export_loader').addClass('d-none');
                }, 2000);
            });
        });

        $(document).ready(function () {
            $('#export_db_local_server_btn').on('click', function () {
                let restaurantId = $('#restaurant_select').val();

                if (!restaurantId) {
                    toastr.error('Please select a restaurant first.');
                    return;
                }

                $('#export_db_local_server_btn').prop('disabled', true);
                $('#export_db_local_server_loader').removeClass('d-none');

                window.location.href = '{{ url('/super-admin/menu-export-for-local-setup') }}/' + restaurantId;

                setTimeout(function () {
                    $('#export_db_local_server_btn').prop('disabled', false);
                    $('#export_db_local_server_loader').addClass('d-none');
                }, 2000);
            });
        });
    </script>
@endsection