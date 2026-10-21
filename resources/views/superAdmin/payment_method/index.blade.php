@extends('layouts.master')
@section('title', 'Payment Methods Management')

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid py-4">
        <div class="main-heading mb-4">
            <h4>Payment Methods Management</h4>
        </div>
        <div class="row">
            <!-- Add/Edit Payment Method Form -->
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="main-content p-4 bg-white shadow-sm rounded">
                    <form id="payment-method-form" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="credit_card">Credit Card</option>
                                <option value="debit_card">Debit Card</option>
                                <option value="cash">Cash</option>
                                <option value="gift_card">Gift Card</option>
                                <option value="voucher">Voucher</option>
                                <option value="{{ \App\Models\PaymentMethod::$cryptoName }}">{{ \App\Models\PaymentMethod::$cryptoName }}</option>
                                <option value="delivery_partner">Delivery Partner</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save</button>
                    </form>
                </div>
            </div>

            <!-- Payment Methods Table -->
            <div class="col-lg-8 col-md-12">
                <div class="main-content p-4 bg-white shadow-sm rounded">
                    <table id="payment-methods-table" class="table table-striped table-bordered nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        const table = $('#payment-methods-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('payment-method-list') }}',
                data: function (d) {
                    d.status = $('#status_filter').val();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'type', name: 'type' },
                {
                    data: 'status',
                    render: function (data) {
                        return (parseInt(data) === 1)
                            ? '<span class="badge bg-success">Active</span>'
                            : '<span class="badge bg-secondary">Inactive</span>';
                    },
                    name: 'status'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                },
            ],
        });

        // Disable click event for non-editable/non-deletable payment methods
        $(document).on('click', '.edit-payment-method, .delete-payment-method', function (e) {
            if ($(this).hasClass('disabled')) {
                e.preventDefault();
                toastr.warning('This action is not allowed for this payment method.');
            }
        });

        // Save/Add Payment Method
        $('#payment-method-form').on('submit', function (e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action') || '{{ route("payment-method-store") }}';
            const method = $('#_method').val() || 'POST';

            $.ajax({
                type: method,
                url: url,
                data: form.serialize(),
                success: function (response) {
                    toastr.success(response.message);
                    table.ajax.reload();
                    form.trigger('reset');
                },
                error: function () {
                    toastr.error('An error occurred!');
                }
            });
        });

        // Edit Payment Method
        $(document).on('click', '.edit-payment-method', function () {
            const id = $(this).data('id');
            const url = '{{ route("payment-method-show", ":id") }}'.replace(':id', id);

            $.get(url, function (response) {
                if (response.status === 'success') {
                    const data = response.data;
                    $('#name').val(data.name);
                    $('#type').val(data.type);
                    $('#description').val(data.description);
                    $('#status').val(data.status);
                    $('#_method').val('POST');
                    $('#payment-method-form').attr('action', '{{ route("payment-method-edit", ":id") }}'.replace(':id', id));
                }
            });
        });

        // Delete Payment Method
        $(document).on('click', '.delete-payment-method', function () {
            const id = $(this).data('id');
            const url = '{{ route("payment-method-delete", ":id") }}'.replace(':id', id);

            if (confirm('Are you sure you want to delete this payment method?')) {
                $.get(url, function (response) {
                    toastr.success(response.message);
                    table.ajax.reload();
                });
            }
        });
    });
</script>
@endsection
