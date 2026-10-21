@extends('layouts.master')
@section('title')
    Variants
@endsection
@section('content')
    <style>
        table td {
            word-wrap: break-word;
            white-space: normal;
        }

        /* Apply only to the group column */
        .group-column {
            max-width: 200px;
            /* Adjust column width as needed */
            word-wrap: break-word;
            white-space: normal;
        }
    </style>

    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>Add Variant</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form novalidate action="{{ route('item-variants-save', $item->id) }}" method="POST"
                            enctype='multipart/form-data' id="variant-form">
                            @csrf
                            <input type="hidden" name="variant_id" id="variant_id" value="">
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" autofocus placeholder="Name" id="name" name="name"
                                    class="form-control max30Length" required value="{{ old('name', '') }}">
                                <div id="nameHelp" class="form-text">Item Name: <strong class="text-black">{{ $item->name }}</strong></div>
                                @error('name')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="description">Description </label>
                                <input type="text" name="description" placeholder="Description" id="description"
                                    description="description" class="form-control max30Length" required
                                    value="{{ old('description', '') }}">
                                @error('description')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">SKU</label>
                                <input type="text" placeholder="Sku" id="sku" sku="sku" name="sku"
                                    class="form-control max30Length" value="{{ old('sku', '') }}">
                                @error('sku')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="price">Price</label>
                                <input type="text" id="price" name="price" class="form-control" required
                                    value="{{ old('price', '0.00') }}">
                                <div id="priceHelp" class="form-text">Item Price: <strong class="text-black">{{ $item->price }}</strong></div>

                                @error('price')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="web_price">Web Price</label>
                                <input type="text" id="web_price" name="web_price" class="form-control" placeholder="0.00"
                                    value="{{ old('web_price', '') }}">

                                @error('web_price')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="DoorDash_price">DoorDash Price</label>
                                <input type="text" id="door_dash_price" name="door_dash_price" class="form-control" placeholder="0.00"
                                    value="{{ old('door_dash_price', '') }}">

                                @error('door_dash_price')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="ubereats_price">Ubereats Price</label>
                                <input type="text" id="ubereats_price" name="ubereats_price" class="form-control" placeholder="0.00"
                                    value="{{ old('ubereats_price', '') }}">

                                @error('ubereats_price')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="grubhub_price">Grubhub Price</label>
                                <input type="text" id="grubhub_price" name="grubhub_price" class="form-control" placeholder="0.00"
                                    value="{{ old('grubhub_price', '') }}">

                                @error('grubhub_price')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="quantity">Quantity</label>
                                <input type="number" id="quantity" name="quantity" class="form-control" required
                                    value="{{ old('quantity', '1') }}">

                                @error('quantity')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="is_default" name="is_default">
                                <label class="form-check-label" for="is_default">
                                    &nbsp;Is Default
                                </label>

                                @error('is_default')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="status">Status </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1" {{ old("status", 1) == 1 ? "selected" : "" }}>Active</option>
                                    <option value="0" {{ old("status", 'NA') == 0 ? "selected" : "" }}>In-active</option>
                                </select>
                                @error('status')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="image">Image </label>
                                <input type="file" id="image" name="image" class="form-control">
                                @error('image')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <img src="" class="list-image" id="edit_variant_icon">
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" onclick="document.getElementById('variant_id').value = ''"
                                class="btn btn-secondary">Reset</button>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-heading">
                        <h4>{{ $item->name }} Variants: ${{ $item->price }}</h4>
                        <div>
                            <button class="btn btn-primary" id="bulk-update-btn">Bulk Update</button>
                            <a href="{{ route('item-list') .'/'. request()->categoryId }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup"></th>
                                    <th scope="rowgroup"></th>
                                    <th scope="rowgroup">Name</th>
                                    <th scope="rowgroup">Price</th>
                                    <th scope="rowgroup">DoorDash Pprice</th>
                                    <th scope="rowgroup">UberEats Pprice</th>
                                    <th scope="rowgroup">Grubhub Pprice</th>
                                    <th scope="rowgroup">Quantity</th>
                                    <th scope="rowgroup">SKU</th>
                                    <th scope="rowgroup">Description</th>
                                    <th scope="rowgroup">Default</th>
                                    <th scope="rowgroup">Image</th>
                                    <th scope="rowgroup">Status</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($variants as $variant)
                                    <tr>
                                        <td>
                                            <input style="width: 18px; height: 18px; margin-top: 5px;" type="checkbox" class="variant-checkbox" value="{{ $variant->id }}">
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $variant->name }}</td>
                                        <td>{{ $variant->price }}</td>
                                        <td>{{ $variant->door_dash_price }}</td>
                                        <td>{{ $variant->ubereats_price }}</td>
                                        <td>{{ $variant->grubhub_price }}</td>
                                        <td>{{ $variant->quantity }}</td>
                                        <td>{{ $variant->sku }}</td>
                                        <td>{{ $variant->description }}</td>
                                        <td>{{ $variant->is_default ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <img src="{{ $variant->image_url }}" class="list-image">
                                        </td>
                                        <td>
                                            @if ($variant->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">In-active</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="me-2">
                                                @can('variant-edit')
                                                    <a aria-hidden="true" href="#" id="variant-edit" data-id="{{ $variant->id }}">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <span>
                                                @can('variant-delete')
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteVariant"
                                                        data-bs-target="#deleteVariantModal"
                                                        data-url="{{ route('item-variants-delete', ['itemId' => $item->id, 'id' => $variant->id]) }}">
                                                        <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
                                                    </a>
                                                @endcan
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->

    <!--Delete Modal Popup Start-->
    <div class="modal fade" id="deleteVariantModal" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-img">
                        <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                    </div>
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you Sure?</h4>
                        <p class="text-center mt-3">Do you really want to delete ? <br>This process cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-primary" id="deleteVariantBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal Popup end-->

    @include('modals.price-bulk-update')
@endsection
@section('js')
    <script>
        @if(Session::has('errors'))
            var isValidationError = true;
        @else
            var isValidationError = false;
        @endif

            var variantCreateUrl = '{{ route('item-variants-save', $item->id) }}';
        var variantDetailUrl = '{{ route('item-variant', ['itemId' => $item->id, 'id' => ':id']) }}';

        $(document).on('click', '#variant-add', function (e) {
            $('#variant-add-modal').find('.modal-title').text('Add Variant');
            var variantAddForm = $('#variant-add-modal').find('form');
            variantAddForm.attr('action', variantCreateUrl);
            variantAddForm.find('button[type=submit]').text('Add');
            variantAddForm.find('#name').val('');
            variantAddForm.find('#price').val('');
            variantAddForm.find('#image').val('');
            variantAddForm.find('#color').val('#ffffff');
            variantAddForm.find('#status').val('1');
            variantAddForm.find("#edit_variant_icon").hide();

            setTempData("modal_title", 'Add Variant');
            setTempData("add_update", 'Add');
            $('.validation-error').hide();
        });

        $(document).on('click', '#variant-edit', function (e) {
            var id = $(this).data('id');
            var url = variantDetailUrl.replace(':id', id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    if (response.status == 'success') {
                        var data = response.data;

                        var form = $('#variant-form');
                        form.find("#variant_id").val(data.id);
                        form.find('#name').val(data.name);
                        form.find('#description').val(data.description);
                        form.find('#sku').val(data.sku);

                        if (Number(data.is_default) == 1) {
                            form.find('#is_default').attr('checked', true);
                        }

                        form.find('#status').val(data.status);

                        form.find('#price').val(data.price);
                        form.find('#quantity').val(data.quantity);

                        if (data.image_url) {
                            $('#edit_variant_icon').attr('src', data.image_url).show();
                        } else {
                            $('#edit_variant_icon').hide();
                        }
                    }
                }
            });
        });


        $(document).on('click', '#deleteVariant', function (e) {
            var url = $(this).data('url');
            $('#deleteVariantBtn').attr('href', url);
        });

        // Validate input to allow only decimal values
        $('input[name="price"]').on('input', function () {
            this.value = this.value.replace(/[^0-9.]/g, '');

            if (this.value.split('.').length > 2) {
                this.value = this.value.replace(/\.+$/, "");
            }
        });
    </script>

        <script>
            $(document).ready(function () {
                var priceFields = ["#door_dash_price", "#ubereats_price", "#grubhub_price", "#web_price"];

                priceFields.forEach(function(selector) {
                    var priceField = $(selector);
                    priceField.data("isEdited", priceField.val().trim() !== "");

                    priceField.on("input change", function() {
                        priceField.data("isEdited", true);
                    });
                });

                $("#price").on("input change", function() {
                    priceFields.forEach(function(selector) {
                        var priceField = $(selector);
                        if (priceField.data("isEdited")) {
                            return;
                        }
                        priceField.val($("#price").val());
                    });
                });
            });
        </script>

    <script>
        $(document).ready(function () {
            // Show the modal on bulk update button click
            $('#bulk-update-btn').on('click', function() {
                if ($('.variant-checkbox:checked').length === 0) {
                    showWarningMessage('Please select variant to update.');
                } else {
                    $('#bulkUpdateModal').modal('show');
                    $('input[name="update_type"][value="set_to"]').prop('checked', true); // Set default to 'Set to'
                }
            });

            // Handle bulk update form submission
            $('#bulk-update-submit').on('click', function(e) {
                e.preventDefault();
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

                var selectedVariants = $('.variant-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedVariants.length === 0) {
                    showModalWarningMessage('No variant selected.');
                    return;
                }

                var formData = $('#bulk-update-form').serializeArray();
                formData.push({name: 'variants', value: selectedVariants.join(',')});
                formData.push({name: '_token', value: $('meta[name="csrf-token"]').attr('content')});
                formData.push({name: 'percent_type', value: percentType});

                $.ajax({
                    type: 'PUT',
                    url: '{{ route('variant-bulk-update') }}',
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
        });
    </script>

@endsection