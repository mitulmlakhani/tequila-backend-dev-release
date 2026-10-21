@extends('layouts.master')
@section('title', 'POS Devices Management')

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="main-heading mb-3">
            <h4>POS Devices Management</h4>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="restaurant_filter" class="form-label">Filter by Restaurant</label>
                <select id="restaurant_filter" class="form-select">
                    <option value="">All Restaurants</option>
                    @foreach ($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}">{{ ucwords($restaurant->parentRestaurant ? ($restaurant->parentRestaurant->name . ': ' . $restaurant->branch_name) : $restaurant->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="type_filter" class="form-label">Filter by Type</label>
                <select id="type_filter" class="form-select">
                    <option value="">All Types</option>
                    @foreach($deviceTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="status_filter" class="form-label">Filter by Status</label>
                <select id="status_filter" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <div class="main-content p-3 bg-white shadow-sm rounded">
            <table id="pos_devices_table" class="display nowrap w-100 table table-striped">
                <thead>
                    <tr>
                        <th>Restaurant</th>
                        <th>Name</th>
                        <th>Device ID</th>
                        <th>Protocol</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        const table = $('#pos_devices_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('super-admin.pos-devices.index') }}',
                type: 'GET',
                data: function (d) {
                    d.restaurant_id = $('#restaurant_filter').val();
                    d.type = $('#type_filter').val();
                    d.status = $('#status_filter').val();
                }
            },
            columns: [
                { data: 'restaurant_name', name: 'restaurant_name' },
                { data: 'name', name: 'name' },
                { data: 'device_id', name: 'device_id' },
                { data: 'protocol', name: 'protocol' },
                { data: 'type', name: 'type' },
                {
                    data: null,
                    name: 'status_action',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        const isActive = data.status === 'Active';
                        const btnClass = isActive ? 'btn-success' : 'btn-danger';
                        const btnText = isActive ? 'Activated' : 'Deactivated';
                        const toggleTo = isActive ? 0 : 1;

                        return `
                            <button class="btn btn-sm ${btnClass} toggle-device-status"
                                data-id="${data.id}"
                                data-status="${toggleTo}">
                                ${btnText}
                            </button>
                        `;
                    }
                }
            ],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        });

        $('#restaurant_filter, #type_filter, #status_filter').on('change', function () {
            table.ajax.reload();
        });

        $(document).on('click', '.toggle-device-status', function () {
            const deviceId = $(this).data('id');
            const newStatus = $(this).data('status');
            const statusText = newStatus === 1 ? 'Activate' : 'Deactivate';

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to ${statusText} this device?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Yes, ${statusText} it!`,
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('super-admin.pos-devices.activate', '') }}/' + deviceId,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            status: newStatus
                        },
                        success: function (response) {
                            Swal.fire('Success!', response.message, 'success');
                            table.ajax.reload(null, false);
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
