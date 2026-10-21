@extends('layouts.master')
@section('title')
    Vendors
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4 id="form-title">Add Vendor</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('vendor-create') }}" method="POST" enctype='multipart/form-data'
                            id="vendor-form">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="company_name">Company Name</label>
                                <input type="text" placeholder="Vendor Name" id="company_name" name="company_name"
                                    class="form-control" required value="{{ old('company_name', '') }}">
                                @error('company_name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <input type="text" placeholder="Description" id="description" name="description"
                                    class="form-control" required value="{{ old('description', '') }}">
                                @error('description')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="name">Contact Persion Name</label>
                                <input type="text" placeholder="Name" id="name" name="name" class="form-control"
                                    required value="{{ old('name', '') }}">
                                @error('name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" placeholder="Email" id="email" name="email" class="form-control"
                                    required value="{{ old('email', '') }}">
                                @error('email')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" placeholder="Phone" id="phone" name="phone" class="form-control"
                                    required value="{{ old('phone', '') }}">
                                @error('phone')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="address">Address</label>
                                <textarea rows="3" placeholder="Address" id="address" name="address" class="form-control" required>{{ old('address', '') }}</textarea>
                                @error('address')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="ingredients-select">Ingredients</label><br />
                                <select required multiple="multiple" id="ingredients-select" name="ingredients[]"
                                    class="form-select w-100">
                                    @foreach ($ingredients as $ingredient)
                                        <option value="{{ $ingredient->id }}"
                                            {{ collect(old('ingredients', []))->contains($ingredient->id) ? 'selected' : '' }}>
                                            {{ $ingredient->name }}</option>
                                    @endforeach
                                </select>
                                @error('ingredients')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            @canany(['vendor-create', 'vendor-update'])
                            <button type="submit" class="btn btn-primary" id="save-btn">Save</button>
                            @endcanany
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-heading">
                        <h4>Vendor Management</h4>
                    </div>
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">#</th>
                                    <th scope="rowgroup">Company Name</th>
                                    <th scope="rowgroup">Description</th>
                                    <th scope="rowgroup">Name</th>
                                    <th scope="rowgroup">Email</th>
                                    <th scope="rowgroup">Phone</th>
                                    <th scope="rowgroup">Address</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vendors as $index => $vendor)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $vendor->company_name }}</td>
                                        <td>
                                            <p style="white-space: nowrap;">{{ $vendor->description }}</p>
                                        </td>
                                        <td>{{ $vendor->name }}</td>
                                        <td>{{ $vendor->email }}</td>
                                        <td>{{ $vendor->phone }}</td>
                                        <td>{{ $vendor->address }}</td>
                                        <td>
                                            @can('vendor-invoice-create')
                                                <a href="{{ route('vendor-invoice-create', [$vendor->id]) }}" class="btn btn-primary">Create Invoice</a>
                                            @endcan
                                            @can('vendor-invoice-read')
                                            <a href="{{ route('vendor-invoice-list', ['vendorId' => $vendor->id]) }}" class="btn btn-primary">View Invoice</a>
                                            @endcan

                                            @can('vendor-edit')
                                                <a aria-hidden="true" href="#" id="vendor-edit" data-bs-toggle="modal"
                                                    data-id="{{ $vendor->id }}" data-bs-target="#vendor-add-modal"
                                                    data-bs-whatever="@mdo">
                                                    <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                </a>
                                            @endcan

                                            @can('vendor-delete')
                                                <a aria-hidden="true" href="#" data-bs-toggle="modal"
                                                    id="deleteVendor" data-bs-target="#deleteVendorModal"
                                                    data-url="{{ route('vendor-delete', ['id' => $vendor->id]) }}">
                                                    <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
                                                </a>
                                            @endcan
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
    <div class="modal fade" id="deleteVendorModal" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Vendor</h5>
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
                    <a href="#" class="btn btn-primary" id="deleteVendorBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal Popup end-->
@endsection

@section('js')
    <!-- Add Select2 CDN -->
    <link href="{{ asset('assets/css/plugins/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/plugins/select2.min.js') }}"></script>

    <script>
        @if (Session::has('errors'))
            var isValidationError = true;
        @else
            var isValidationError = false;
        @endif

        var vendorCreateUrl = '{{ route('vendor-create') }}';
        var vendorUpdateUrl = '{{ route('vendor-edit', ':id') }}';
        var vendorDetailUrl = '{{ route('vendor-show', ':id') }}';

        $(document).ready(function() {

            $("#ingredients-select").select2({
                placeholder: "Select Ingredients"
            });

            // Use event delegation for dynamically loaded elements
            $(document).on('click', '#vendor-edit', function(e) {
                e.preventDefault(); // Prevent default action

                var id = $(this).data('id');

                var formUrl = vendorUpdateUrl.replace(':id', id);
                $('#vendor-form').attr('action', formUrl);
                $('#save-btn').text('Update');
                $('#form-title').text('Edit Vendor');

                var url = vendorDetailUrl.replace(':id', id);

                $("#category_management").find('tr').each(function (i, tr) {
                    this.style.removeProperty('background-color');
                });

                var vendorRow = $(this).closest('tr');

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.status === 'success') {

                            vendorRow.css('background-color', '#ffe');

                            const data = response.data;

                            // Populate form fields with data
                            $('#company_name').val(data.company_name).focus();
                            $('#name').val(data.name);
                            $('#description').val(data.description);
                            $('#email').val(data.email);
                            $('#phone').val(data.phone);
                            $('#address').val(data.address);
                            $('#ingredients-select').val(JSON.parse(data.ingredients ? data.ingredients : '[]')).trigger('change');
                        } else {
                            console.error('Error fetching vendor details:', response
                                .message);
                        }
                    },
                    error: function(err) {
                        console.error('AJAX error:', err);
                    }
                });
            });

            // Handle delete functionality in the modal
            $(document).on('click', '#deleteVendor', function(e) {
                var url = $(this).data('url');
                $('#deleteVendorBtn').attr('href', url);
            });
        });
    </script>
@endsection
