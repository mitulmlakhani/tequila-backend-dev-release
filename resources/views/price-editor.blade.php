@extends('layouts.master')
@section('title')
    Price Editor
@endsection
@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12">
                    <div class="main-heading d-flex justify-content-between">
                        <h4>Manage Prices</h4>
                        @canany(['price-editor.bulk-update'])
                        <button class="btn btn-primary" id="bulk-update-btn">Bulk Update</button>
                        @endcanany
                    </div>
                </div>

                @include('layouts.flash-msg')

                <div class="col-12">
                    <div class="main-content p-3">
                        <table id="price-editor-table" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th><input class="form-check-input" type="checkbox" id="select-all"></th>
                                    <th>Item Name</th>
                                    <th>
                                        <select id="category-filter" class="form-control">
                                            <option value="">All Categories</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>POS Price</th>
                                    <th>Online Order</th>
                                    <th>Door Dash Price</th>
                                    <th>UberEats Price</th>
                                    <th>Grubhub Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.price-bulk-update')
@endsection

@section('js')
    <script>
        var priceEditUrl = "{{ route('price-editor.update', '#ITEM_ID') }}";

        $(document).ready(function () {
            const table = $('#price-editor-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("price-editor.index") }}',
                    data: function (d) {
                        d.category_id = $('#category-filter').val();
                    }
                },
                columns: [
                    { data: 0, orderable: false, searchable: false },
                    { data: 1 },
                    { data: 2 },
                    { data: 3, orderable: false, searchable: false },
                    { data: 4, orderable: false, searchable: false },
                    { data: 5, orderable: false, searchable: false },
                    { data: 6, orderable: false, searchable: false },
                    { data: 7, orderable: false, searchable: false },
                    { data: 8, orderable: false, searchable: false },
                ],
                scrollX: true,
            });

            $('#category-filter').on('change', function () {
                table.ajax.reload();
            });

            $('#select-all').on('click', function () {
                $('.item-checkbox').prop('checked', this.checked);
            });
        });

        $(document).ready(function () {
            // Show the modal on bulk update button click
            $('#bulk-update-btn').on('click', function() {
                if ($('.item-checkbox:checked').length === 0) {
                    showWarningMessage('Please select items to update.');
                } else {
                    $('#bulkUpdateModal').modal('show');
                    $('input[name="update_type"][value="set_to"]').prop('checked', true); // Set default to 'Set to'
                }
            });

            // Handle bulk update form submission
            $('#bulk-update-submit').on('click', function() {
                var updateType = $('input[name="update_type"]:checked').val();
                var setToValue = $('input[name="set_to"]').val();
                var byPriceValue = $('input[name="by_price"]').val();
                var byPercentValue = $('input[name="by_percent"]').val();
                var percentType = $('input[name="percent_type"]:checked').val();

                if (updateType === 'set_to' && !setToValue) {
                    showModalWarningMessage('Please enter a value for "Set to".');
                    return;
                }

                if (updateType === 'by_price' && !byPriceValue) {
                    showModalWarningMessage('Please enter a value for "By Price".');
                    return;
                }

                if (updateType === 'by_percent' && !byPercentValue) {
                    showModalWarningMessage('Please enter a value for "By Percent".');
                    return;
                }

                var selectedItems = $('.item-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedItems.length === 0) {
                    showModalWarningMessage('No items selected.');
                    return;
                }

                var formData = $('#bulk-update-form').serializeArray();
                formData.push({name: 'items', value: selectedItems.join(',')});
                formData.push({name: '_token', value: $('meta[name="csrf-token"]').attr('content')});
                formData.push({name: 'percent_type', value: percentType});

                $.ajax({
                    type: 'PUT',
                    url: '{{ route('price-editor.bulk-update') }}',
                    data: $.param(formData),
                    success: function(response) {
                        showSuccessMessage('Bulk update successful.');
                        location.reload();
                    },
                    error: function(xhr) {
                        showWarningMessage('An error occurred: ' + xhr.responseText);
                    }
                });
            });

            function showSuccessMessage(message) {
                var successAlert = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                                    + message +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                $('.main-content').prepend(successAlert);
            }

            function showWarningMessage(message) {
                var warningAlert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">'
                                    + message +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                $('.main-content').prepend(warningAlert);
            }

            function showModalWarningMessage(message) {
                var modalWarningAlert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">'
                                    + message +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                $('.modal-body').prepend(modalWarningAlert);
            }

            // Validate input to allow only decimal values
            $('input[name="price"], input[name="web_price"], input[name="door_dash_price"], input[name="ubereats_price"], input[name="grubhub_price"], input[name="set_to"], input[name="by_price"], input[name="by_percent"]').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '');
                if (this.value.split('.').length > 2) {
                    this.value = this.value.replace(/\.+$/, "");
                }
            });

            $("#price-editor-table").on('click', ".update-price-btn", function (e) {
                e.preventDefault();
                var itemId = $(this).data('item_id');

                priceEditUrl = priceEditUrl.replace('#ITEM_ID', itemId);

                var tr = $(this).closest('tr');

                var price = tr.find('input[name="price"]').val();
                var webPrice = tr.find('input[name="web_price"]').val();
                var doorDashPrice = tr.find('input[name="door_dash_price"]').val();
                var uberEatsPrice = tr.find('input[name="ubereats_price"]').val();
                var grubhubPrice = tr.find('input[name="grubhub_price"]').val();

                $.ajax({
                    type: 'POST',
                    url: priceEditUrl,
                    data: {
                        _method: 'PUT',
                        _token: '{{ csrf_token() }}',
                        price: price,
                        web_price: webPrice,
                        door_dash_price: doorDashPrice,
                        ubereats_price: uberEatsPrice,
                        grubhub_price: grubhubPrice
                    },
                    success: function (response) {
                        showSuccessMessage('Price updated successfully.');
                    },
                    error: function (xhr) {
                        showWarningMessage('An error occurred: ' + xhr.responseText);
                    }
                });
            });

            $(document).on("change input", ".web_price_input, .door_dash_price_input, .ubereats_price_input, .grubhub_price_input", function () {
                $(this).data('modified', 1);
            });

            $(document).on("change input", ".price_input", function () {
                var newPrice = parseFloat($(this).val());

                if (isNaN(newPrice)) {
                    return;
                }

                var _ptr = $(this).closest("tr");
                var _uberEatsInput = $(_ptr).find(".ubereats_price_input");
                var _grubhubInput = $(_ptr).find(".grubhub_price_input");
                var _doorDashInput = $(_ptr).find(".door_dash_price_input");
                var _webInput = $(_ptr).find(".web_price_input");

                if ($(_uberEatsInput).data('modified') != 1) {
                    $(_uberEatsInput).val(newPrice.toFixed(2));
                }

                if ($(_grubhubInput).data('modified') != 1) {
                    $(_grubhubInput).val(newPrice.toFixed(2));
                }

                if ($(_doorDashInput).data('modified') != 1) {
                    $(_doorDashInput).val(newPrice.toFixed(2));
                }

                if ($(_webInput).data('modified') != 1) {
                    $(_webInput).val(newPrice.toFixed(2));
                }
            });

        });
    </script>
@endsection
