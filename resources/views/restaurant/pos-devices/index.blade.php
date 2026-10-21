@extends('layouts.master')
@section('title')
    POS Devices
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>Add POS Device</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('pos-devices.store') }}" method="POST" enctype='multipart/form-data' id="pos-device-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" placeholder="Name" id="name" name="name" class="form-control" required value="{{ old('name', '') }}">
                                @error('name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="device_id">Device ID/Mac Address</label>
                                <input type="text" placeholder="device id / mac address" id="device_id" name="device_id" class="form-control" required value="{{ old('device_id', '') }}">
                                @error('device_id')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="type">Type</label>
                                <select class="form-select" id="type" name="type">
                                    @foreach($deviceTypes as $key => $label)
                                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Protocol -->
                            <div class="mb-3">
                                <label class="form-label" for="protocol">Protocol</label>
                                <select class="form-select" id="protocol" name="protocol" required>
                                    <option value="HTTP" {{ old('protocol', $device->protocol ?? '') == 'HTTP' ? 'selected' : '' }}>HTTP</option>
                                    <option value="TCPIP" {{ old('protocol', $device->protocol ?? '') == 'TCPIP' ? 'selected' : '' }}>TCPIP</option>
                                </select>
                                @error('protocol')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Serial Number -->
                            <div class="mb-3">
                                <label class="form-label" for="serial_no">Serial Number</label>
                                <input type="text" placeholder="Serial Number" id="serial_no" name="serial_no" class="form-control" required value="{{ old('serial_no', $device->serial_no ?? '') }}">
                                @error('serial_no')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="ip_address">IP Address</label>
                                <input type="text" placeholder="Enter IP Address" id="ip_address" name="ip_address"
                                    class="form-control" value="{{ old('ip_address', $device->ip_address ?? '') }}">
                                @error('ip_address')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="printer-dropdown" style="display: none;">
                                <label class="form-label" for="printer_id">Assign Printer</label>
                                <select class="form-select" id="printer_id" name="printer_id">
                                    <option value="">Select a Printer</option>
                                    @foreach($devices->where('type', 'printer')->where('status', 1) as $printer)
                                        <option value="{{ $printer->id }}" {{ old('printer_id', $device->printer_id ?? '') == $printer->id ? 'selected' : '' }}>
                                            {{ $printer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('printer_id')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="autoclose-shift">
                                <label class="form-label" for="autoclose_shift">Auto Close Shift</label>
                                <select class="form-select" id="autoclose_shift" name="autoclose_shift">
                                    <option value="">Select a Auto Close Shift</option>
                                    <option value="1" {{ old('autoclose_shift', $device->autoclose_shift ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('autoclose_shift', $device->autoclose_shift ?? '') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('autoclose_shift')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea placeholder="Description" id="description" name="description" class="form-control">{{ old('description', '') }}</textarea>
                                @error('description')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            @canany(['pos-devices.store', 'pos-devices.update'])
                            <button type="submit" class="btn btn-primary">Save</button>
                            @endcanany
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-heading">
                        <h4>POS Devices Management</h4>
                    </div>
                    <div class="main-content p-3">
                        <table id="devices_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Device ID</th>
                                    <th>Serial No</th>
                                    <th>IP Address</th>
                                    <th>Protocol</th>
                                    <th>Auto Close Shift</th>
                                    <th><div class="mb-3">
                                        <label for="type_filter" class="form-label">Filter by Type</label>
                                        <select id="type_filter" class="form-select">
                                            <option value="">All</option>
                                            @foreach($deviceTypes as $key => $label)
                                                <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div></th>
                                    <th>Printer</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($devices as $device)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $device->name }}</td>
                                        <td>{{ $device->device_id }}</td>
                                        <td>{{ $device->serial_no }}</td>
                                        <td>{{ $device->ip_address ?? 'N/A' }}</td>
                                        <td>{{ $device->protocol }}</td>
                                        <td>{{ $device->autoclose_shift ? 'Yes' : 'No'}}</td>
                                        <td>{{ ucfirst($device->type) }}</td>
                                        <td>{{ $device->printer->name ?? '—' }}</td>
                                        <td class="text-center">
                                            @if($device->type === 'printer' && auth()->user()->can('pos-devices.update'))
                                                <a href="{{ route('pos-devices.toggleStatus', ['id' => $device->id]) }}" class="btn btn-sm {{ $device->status ? 'btn-danger' : 'btn-success' }}">
                                                    <span>{{ $device->status ? 'Deactivate' : 'Activate' }}</span>
                                                </a>
                                            @else
                                            <div class="{{ $device->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $device->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                     class="me-2" alt="{{ $device->status ? 'active' : 'inactive' }}">
                                                <span>{{ $device->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            @can('pos-devices.update')
                                            <span class="me-2">
                                                <a href="#" id="edit-device" data-id="{{ $device->id }}" data-bs-toggle="modal"
                                                data-bs-target="#edit-device-modal" class="text-primary">
                                                    <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                </a>
                                            </span>
                                            @endcan

                                            @can('pos-devices.destroy')
                                            <span>
                                                <a href="#" id="delete-device" data-id="{{ $device->id }}" data-bs-toggle="modal"
                                                data-bs-target="#delete-device-modal" class="text-danger">
                                                    <img src="{{ asset('assets/images/dustbin.png') }}" alt="delete">
                                                </a>
                                            </span>
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

    <!-- Delete Modal Popup -->
    <div class="modal fade" id="delete-device-modal" tabindex="-1" aria-labelledby="deleteDeviceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteDeviceModalLabel">Delete POS Device</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                        <h4 class="mt-3">Are you sure?</h4>
                        <p>All associations with items will be deleted for this device.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="delete-device-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal Popup End -->
@endsection

@section('js')
<script>

    function togglePrinterDropdown() {
        const selectedType = $('#type').val();
        if (selectedType === 'pos') {
            $('#printer-dropdown').show();
            $('#autoclose-shift').show();
        } else {
            $('#printer-dropdown').hide();
            $('#autoclose-shift').hide();
            $('#printer_id').val('');
            $('#autoclose_shift').val('0');
        }
    }

    $(document).ready(function () {
        // Initialize DataTable
        $('#devices_management').DataTable({
            responsive: true,
            autoWidth: false,
            scrollX: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search POS Devices",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: "&lt;",
                    next: "&gt;",
                },
            },
        });

        // Clear the form and reset the modal for adding a new device
        $(document).on('click', '#pos-device-add', function () {
            $('#pos-device-form').trigger('reset');
            $('#pos-device-form').attr('action', '{{ route("pos-devices.store") }}');
            $('#pos-device-form').find('input[name="_method"]').remove(); // Remove any existing PUT method
            $('.modal-title').text('Add POS Device');
        });

        // Edit POS Device
        $(document).on('click', '#edit-device', function () { // Changed to match the HTML ID
            var id = $(this).data('id');
            var url = '{{ route("pos-devices.show", ":id") }}'.replace(':id', id);

            $("#devices_management").find('tr').each(function (i, tr) {
                this.style.removeProperty('background-color');
            });

            var deviceRow = $(this).closest('tr');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    if (response.status === 'success') {
                        deviceRow.css('background-color', '#ffe');
                        $('#name').val(response.data.device.name).focus();
                        $('#device_id').val(response.data.device.device_id);
                        $('#description').val(response.data.device.description);
                        $('#status').val(response.data.device.status);
                        $('#type').val(response.data.device.type).trigger('change');
                        togglePrinterDropdown();
                        $('#serial_no').val(response.data.device.serial_no);
                        $('#ip_address').val(response.data.device.ip_address);
                        $('#printer_id').val(response.data.device.printer_id || '').trigger('change');
                        $('#autoclose_shift').val(response.data.device.autoclose_shift || '').trigger('change');

                        $('#pos-device-form').attr('action', '{{ route("pos-devices.update", ":id") }}'.replace(':id', response.data.device.id));
                        if (!$('#pos-device-form').find('input[name="_method"]').length) {
                            $('#pos-device-form').append('<input type="hidden" name="_method" value="PUT">');
                        }
                        $('.modal-title').text('Edit POS Device');
                        $('#pos-device-modal').modal('show');
                    } else {
                        alert('Failed to fetch device data.');
                    }
                },
                error: function () {
                    alert('Error occurred while fetching device data.');
                },
            });
        });

        // Delete POS Device
        $(document).on('click', '#delete-device', function () { // Changed to match the HTML ID
            var id = $(this).data('id');
            var url = '{{ route("pos-devices.destroy", ":id") }}'.replace(':id', id);
            $('#delete-device-form').attr('action', url);
        });

        // Reset delete modal action on close
        $('#delete-device-modal').on('hidden.bs.modal', function () {
            $('#delete-device-form').attr('action', '');
        });

        // Filter by Type
        $('#type_filter').on('change', function () {
            const selectedType = $(this).val();
            const url = new URL(window.location.href);
            url.searchParams.set('type', selectedType);
            window.location.href = url.toString();
        });

        // Trigger change on page load to set initial visibility
        $('#type').trigger('change');

        
        $('#type').on('change', togglePrinterDropdown);
        $(document).ready(togglePrinterDropdown); // Initial load
    });

</script>
@endsection
