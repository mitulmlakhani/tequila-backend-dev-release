@extends('layouts.master')
@section('title')
    Tax Management
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <!-- Left Side Form -->
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>Add Tax</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        {!! Form::open(['route' => 'taxManage.store', 'method' => 'POST', 'id' => 'tax-form']) !!}
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="tax_name">Tax Name</label>
                                {!! Form::text('tax_name', old('tax_name'), [
                                    'placeholder' => 'Tax Name',
                                    'class' => 'form-control',
                                    'id' => 'tax_name',
                                    'required',
                                ]) !!}
                                @error('tax_name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="tax_percent">Tax Percentage</label>
                                {!! Form::text('tax_percent', old('tax_percent'), [
                                    'placeholder' => 'Tax Percentage',
                                    'class' => 'form-control',
                                    'id' => 'tax_percent',
                                    'required',
                                ]) !!}
                                @error('tax_percent')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="category_id">Assigned Category</label>
                                {{ Form::select('category_id[]', getCategory('', 'get'), old('category_id'), [
                                    'class' => 'form-control selectpicker',
                                    'id' => 'category_id',
                                    'multiple' => "multiple",
                                    'data-live-search' => "true"
                                ]) }}
                                @error('category_id')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="status">Status</label>
                                {{ Form::select('status', ['1' => 'Active', '0' => 'Inactive'], old('status'), [
                                    'class' => 'form-select',
                                    'id' => 'status',
                                ]) }}
                                @error('status')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        {!! Form::close() !!}
                    </div>
                </div>
                
                <!-- Right Side DataTable -->
                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-heading">
                        <h4>Tax Management</h4>
                    </div>
                    <div class="main-content p-3">
                        <table id="tax_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tax Name</th>
                                    <th>Tax Percentage</th>
                                    <th>Assigned Category</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taxDataList as $tax)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tax->tax_name }}</td>
                                        <td>{{ $tax->tax_percent }}%</td>
                                        <td>{{ getCategory($tax->category_id, 'category') }}</td>
                                        <td>
                                            <div class="{{ $tax->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $tax->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                     class="me-2" alt="{{ $tax->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $tax->status ? 'Active' : 'Inactive' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="me-2">
                                                @can('tax-edit')
                                                    <a href="#" class="edit-tax" data-id="{{ $tax->id }}">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <span>
                                                @can('tax-delete')
                                                    <a href="#" class="delete-tax" data-id="{{ $tax->id }}">
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
    <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;"></div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteTaxModal" tabindex="-1" aria-labelledby="deleteTaxLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteTaxLabel">Delete Tax</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center mt-3">Are you sure you want to delete this tax?<br>This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="delete-confirm-btn" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
<script>
    $(document).ready(function () {
        var taxEditUrl = '{{ route('taxManage.show', ':id') }}';
        var taxUpdateUrl = '{{ route('taxManage.update', ':id') }}';
        var taxDeleteUrl = '{{ route('taxManage.destroy', ':id') }}';

        // Initialize DataTable
        $('#tax_management').DataTable();

        // Allow decimal values in tax_percent input field
        $('#tax_percent').on('input', function () {
            let value = $(this).val();
            // Allow only numbers and decimals
            value = value.replace(/[^0-9.]/g, '');

            // Ensure only one decimal point
            if ((value.match(/\./g) || []).length > 1) {
                value = value.substring(0, value.length - 1);
            }

            $(this).val(value);
        });

        // Toast Notification Function
        function showToast(type, message) {
            var toastHTML = `
                <div class="toast align-items-center text-white bg-${type} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            $("#toast-container").append(toastHTML);
            setTimeout(() => $(".toast").remove(), 4000);
        }

        // Edit Tax
        $(document).on('click', '.edit-tax', function () {
            var id = $(this).data('id');
            var url = taxEditUrl.replace(':id', id);

            $("#tax_management").find('tr').each(function (i, tr) {
                this.style.removeProperty('background-color');
            });

            var taxRow = $(this).closest('tr');

            $.ajax({
                url: url,
                type: 'GET',
                data: { "_token": "{{ csrf_token() }}" },
                success: function (response) {
                    if (response) {
                        taxRow.css('background-color', '#ffe');
                        $('#record_id').val(response.id);
                        $('#tax_name').val(response.tax_name).focus();
                        $('#tax_percent').val(response.tax_percent);

                        // Fix category selection - handling both array and string cases
                        if (Array.isArray(response.category_id)) {
                    $('#category_id').val(response.category_id);
                } else if (typeof response.category_id === "string") {
                    $('#category_id').val(response.category_id.split(","));
                }

                $('#category_id').selectpicker('refresh');

                        $('#status').val(response.status);
                        $('#tax-form').attr('action', taxUpdateUrl.replace(':id', id));

                        if (!$('#tax-form').find('input[name="_method"]').length) {
                            $('#tax-form').append('<input type="hidden" name="_method" value="PUT">');
                        }

                        $('.modal-title').text('Edit Tax');
                        $('#viewPopBox').modal('show');
                    }
                },
                error: function () {
                    showToast("danger", "Failed to fetch tax details. Please try again.");
                }
            });
        });


        // Delete Tax - Open Confirmation Modal
        $(document).on('click', '.delete-tax', function () {
            var id = $(this).data('id');
            $('#delete-confirm-btn').data('id', id);
            $('#deleteTaxModal').modal('show');
        });

        // Delete Tax - Confirm Deletion
        $(document).on('click', '#delete-confirm-btn', function () {
            var id = $(this).data('id');
            var url = taxDeleteUrl.replace(':id', id);

            $.ajax({
                url: url,
                type: 'DELETE',
                data: { "_token": "{{ csrf_token() }}" },
                success: function (response) {
                    $('#deleteTaxModal').modal('hide');
                    showToast("success", "Tax deleted successfully.");
                    setTimeout(() => location.reload(), 1000);
                },
                error: function () {
                    showToast("danger", "Failed to delete tax. Please try again.");
                }
            });
        });
    });

</script>
@endsection