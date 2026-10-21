@extends('layouts.master')
@section('title', 'Terminal Settings Management')

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="main-heading">
            <h4>Terminal Settings Management</h4>
        </div>
        <div class="row mb-3">
            <!-- Restaurant Filter -->
            <div class="col-md-6">
                <label for="restaurant_filter" class="form-label">Filter by Restaurant</label>
                <select id="restaurant_filter" class="form-select">
                    <option value="">All Restaurants</option>
                    @foreach ($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}">{{ ucwords($restaurant->parentRestaurant ? ($restaurant->parentRestaurant->name . ': ' . $restaurant->branch_name) : $restaurant->name) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="col-md-6">
                <label for="status_filter" class="form-label">Filter by Status</label>
                <select id="status_filter" class="form-select">
                    <option value="">All</option>
                    <option value="1">Enabled</option>
                    <option value="0">Disabled</option>
                </select>
            </div>
        </div>

        <div class="main-content p-3 bg-white shadow-sm rounded">
            <table id="terminal_settings_table" class="display nowrap w-100 table table-striped">
                <thead>
                    <tr>
                        <th>Restaurant</th>
                        <th>Name</th>
                        <th>Protocol</th>
                        <th>Serial No</th>
                        <th>Status</th>
                        <th>Actions</th>
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
    const table = $('#terminal_settings_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('super-admin.terminal-settings.index') }}',
            type: 'GET',
            data: function (d) {
                d.restaurant_id = $('#restaurant_filter').val();
                d.status = $('#status_filter').val();
            },
            error: function (xhr, error, thrown) {
                console.log("Error fetching data:", xhr.responseText);
            }
        },
        columns: [
            { data: 'restaurant_name', name: 'restaurant_name' }, // ✅ Fixed column reference
            { data: 'name', name: 'name' }, // ✅ Ensuring correct column mapping
            { data: 'protocol', name: 'protocol' },
            { data: 'serial_no', name: 'serial_no' },
            {
                data: 'status',
                name: 'status',
                render: function (data, type, row) {
                    return data === 'Enabled'
                        ? '<span class="text-success">Enabled</span>'
                        : '<span class="text-danger">Disabled</span>';
                }
            },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        pageLength: 10, 
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        paging: true,
        searching: true,
        ordering: true,
    });

    // Apply Filters
    $('#restaurant_filter, #status_filter').on('change', function () {
        table.ajax.reload();
    });

    // Toggle Status (Enable/Disable)
    $(document).on('click', '.toggle-status', function () {
        const terminalId = $(this).data('id');

        if (confirm('Are you sure you want to change the status of this terminal?')) {
            $.ajax({
                type: 'POST',
                url: '{{ route('super-admin.terminal-settings.toggle-status', '') }}/' + terminalId,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function (response) {
                    alert(response.message);
                    table.ajax.reload();
                },
                error: function (xhr, status, error) {
                    console.log("Error:", xhr.responseText);
                    alert('Error occurred while changing terminal status.');
                }
            });
        }
    });
});

</script>
@endsection