@extends('layouts.master')
@section('title', 'Timezone Management')

@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid py-4">
            <div class="main-heading mb-4">
                <h4>Timezone Management</h4>
            </div>
            <div class="row">
                <!-- Add/Edit Timezone Form -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="main-content p-4 bg-white shadow-sm rounded">
                        <form id="timezone-form" method="POST">
                            @csrf
                            <input type="hidden" name="_method" id="_method" value="">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Eastern Daylight Time (North America)">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="America/New_York" required>
                            </div>
                            <div class="mb-3">
                                <label for="offset" class="form-label">Offset</label>
                                <input type="text" class="form-control" id="offset" name="offset" placeholder="-04:00">
                            </div>
                            <div class="mb-3">
                                <label for="abbreviation" class="form-label">Abbreviation</label>
                                <input type="text" class="form-control" id="abbreviation" name="abbreviation" placeholder="UTC">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>
                </div>

                <!-- Timezone Table -->
                <div class="col-lg-8 col-md-12">
                    <div class="main-content p-4 bg-white shadow-sm rounded">
                        <table id="timezones-table" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Name</th>
                                    <th>Offset</th>
                                    <th>Abbreviation</th>
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
            const table = $('#timezones-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('timezone-list') }}',
                    data: function (d) {
                        d.status = $('#status_filter').val();
                    }
                },
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'name', name: 'name' },
                    { data: 'offset', name: 'offset' },
                    { data: 'abbreviation', name: 'abbreviation' },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            // Disable click event for non-editable/non-deletable timezone
            $(document).on('click', '.edit-timezone, .delete-timezone', function (e) {
                if ($(this).hasClass('disabled')) {
                    e.preventDefault();
                    toastr.warning('This action is not allowed for this timezone.');
                }
            });

            // Save/Add Timezone
            $('#timezone-form').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action') || '{{ route("timezone-store") }}';
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

            // Edit Timezone
            $(document).on('click', '.edit-timezone', function () {
                const id = $(this).data('id');
                const url = '{{ route("timezone-show", ":id") }}'.replace(':id', id);

                $.get(url, function (response) {
                    if (response.status === 'success') {
                        const data = response.data;
                        $('#name').val(data.name);
                        $('#offset').val(data.offset);
                        $('#abbreviation').val(data.abbreviation);
                        $('#title').val(data.title);
                        $('#_method').val('POST');
                        $('#timezone-form').attr('action', '{{ route("timezone-edit", ":id") }}'.replace(':id', id));
                    }
                });
            });

            // Delete Timezone
            $(document).on('click', '.delete-timezone', function () {
                const id = $(this).data('id');
                const url = '{{ route("timezone-delete", ":id") }}'.replace(':id', id);

                if (confirm('Are you sure you want to delete this timezone?')) {
                    $.get(url, function (response) {
                        toastr.success(response.message);
                        table.ajax.reload();
                    });
                }
            });
        });
    </script>
@endsection