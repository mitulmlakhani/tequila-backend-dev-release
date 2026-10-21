@extends('layouts.master')
@section('title', 'Payment Methods Management')

@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid py-4">
            <div class="main-heading mb-4">
                <h4>POS Features Management</h4>
            </div>
            <div class="row">
                <!-- Add/Edit POS Feature Form -->
                <div class="col-lg-3 col-md-12 mb-4">
                    <div class="main-content p-4 bg-white shadow-sm rounded">
                        <form action="{{ route('pos-feature-save') }}" id="pos-feature-form" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="id" value="" disabled>
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description"
                                    placeholder="Description"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount"
                                    required>
                            </div>

                            <div class="form-check mb-3 mt-2">
                                <input class="form-check-input" type="checkbox" name="qty_base_pricing" id="qty_base_pricing" value="1">
                                <label class="form-check-label" for="qty_base_pricing">
                                    Quantity Based Pricing
                                </label>
                            </div>

                            <div class="row d-none" id="qty_section">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="qty" class="form-label">Quantity</label>
                                        <input type="text" class="form-control" id="qty" name="qty" placeholder="Qty" required>
                                    </div>
                                </div>

                                <div class="col-8">
                                    <div class="mb-3">
                                        <label for="qty_amount" class="form-label">Up to Quantity Amount</label>
                                        <input type="text" class="form-control" id="qty_amount" name="qty_amount" placeholder="Up to Quantity Amount" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="is_default" class="form-label">Is Default</label>
                                <select class="form-select" id="is_default" name="is_default">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>
                </div>

                <!-- Payment Methods Table -->
                <div class="col-lg-9 col-md-12">
                    <div class="main-content p-4 bg-white shadow-sm rounded">
                        <table id="pos-features-table" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th style="max-width: 250px;">Description</th>
                                    <th>Amount</th>
                                    <th>Is Default</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
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
            const table = $('#pos-features-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('pos-feature-list') }}',
                    data: function (d) {
                        d.status = $('#status_filter').val();
                    }
                },
                columns: [
                    { data: 'title', name: 'title' },
                    {
                        data: 'description', name: 'description', render: function (data) {
                            return '<div class="m-0 p-0 text-wrap text-break" style="max-width: 350px;">' + data + '</div>';
                        }
                    },
                    { data: 'amount', name: 'amount' },
                    {
                        data: 'is_default',
                        render: function (data) {
                            return (parseInt(data) === 1)
                                ? '<span class="badge bg-success">Yes</span>'
                                : '<span class="badge bg-secondary">No</span>';
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

            $('#pos-feature-form').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action') || '{{ route("pos-feature-save") }}';
                const method = $('#_method').val() || 'POST';

                $.ajax({
                    type: method,
                    url: url,
                    data: form.serialize(),
                    success: function (response) {
                        $('#id').attr('disabled', true);
                        toastr.success(response.message);
                        table.ajax.reload();
                        form.trigger('reset');
                    },
                    error: function () {
                        toastr.error('An error occurred!');
                    }
                });
            });

            $(document).on('click', '.edit-pos-feature', function () {
                const id = $(this).data('id');
                const url = '{{ route("pos-feature-show", ":id") }}'.replace(':id', id);

                $.get(url, function (response) {
                    if (response.status === 'success') {
                        const data = response.data;

                        $('#id').val(data.id);
                        $('#id').attr('disabled', false);
                        $('#title').val(data.title);
                        $('#description').val(data.description);
                        $('#amount').val(data.amount);
                        $('#is_default').val(data.is_default);

                        if (data.qty_base_pricing) {
                            $("#qty_base_pricing").attr('checked', true);
                            $("#qty").val(data.qty);
                            $("#qty_amount").val(data.qty_amount);
                        } else {
                            $("#qty_base_pricing").attr('checked', false);
                            $("#qty").val(data.qty);
                            $("#qty_amount").val(data.qty_amount);
                        }

                        $('#qty_base_pricing').trigger('change');

                        $('#_method').val('POST');
                        $('#pos-feature-form').attr('action', '{{ route("pos-feature-save") }}'.replace(':id', id));
                    }
                });
            });

            $('#qty_base_pricing').on('change', function () {
                if ($(this).is(':checked')) {
                    $("#qty_section").removeClass('d-none');
                    $("#qty").attr('required', true);
                    $("#qty_amount").attr('required', true);
                } else {
                    $("#qty_section").addClass('d-none');
                    $("#qty").attr('required', false);
                    $("#qty_amount").attr('required', false);
                }
            });
        });
    </script>
@endsection