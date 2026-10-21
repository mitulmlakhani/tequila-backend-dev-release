@extends('layouts.master')
@section('title', 'Terminal Settings Management')

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <!-- Add/Edit Terminal Form -->
            <div class="col-md-3">
                <div class="main-heading">
                    <h4 id="form-title">Add Terminal</h4>
                </div>
                @include('layouts.flash-msg')
                <div class="main-content p-3">
                    <form action="{{ route('restaurant.terminal-settings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="record_id" name="record_id">

                        <div class="mb-3">
                            <label class="form-label" for="name">Terminal Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="protocol">Protocol</label>
                            <select class="form-select" id="protocol" name="protocol" required>
                                <option value="HTTP">HTTP</option>
                                <option value="TCPIP">TCPIP</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="serial_no">Serial Number</label>
                            <input type="text" id="serial_no" name="serial_no" class="form-control" value="{{ old('serial_no') }}" required>
                            @error('serial_no')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="device_id">Device ID</label>
                            <input type="text" id="device_id" name="device_id" class="form-control" value="{{ old('device_id') }}" required>
                            @error('device_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="model_number" class="form-label">Model Number</label>
                            <input 
                                list="model_number_options" 
                                class="form-control @error('model_number') is-invalid @enderror" 
                                id="model_number" 
                                name="model_number" 
                                value="{{ old('model_number', $terminalSetting->model_number ?? '') }}"
                                placeholder="Select or enter model number">
                            <datalist id="model_number_options">
                                <option value="S300">
                                <option value="A35">
                                <option value="SP30">
                                <option value="A920">
                                <option value="A77">
                                <option value="A8700">
                                <option value="E600Mini">
                                <option value="E700">
                                <option value="E700Mini">
                                <option value="E770">
                                <option value="M8">
                            </datalist>
                            @error('model_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- IP Address -->
                        <div class="mb-3">
                            <label for="ip_address" class="form-label">IP Address</label>
                            <input type="text" class="form-control @error('ip_address') is-invalid @enderror" id="ip_address" name="ip_address" value="{{ old('ip_address', $terminalSetting->ip_address ?? '') }}">
                            @error('ip_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Port -->
                        <div class="mb-3">
                            <label for="port" class="form-label">Port</label>
                            <input type="number" class="form-control @error('port') is-invalid @enderror" id="port" name="port" value="{{ old('port', $terminalSetting->port ?? '') }}">
                            @error('port')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Token -->
                        <!-- <div class="mb-3">
                            <label for="token" class="form-label">Token</label>
                            <input type="text" class="form-control @error('token') is-invalid @enderror" id="token" name="token" value="{{ old('token', $terminalSetting->token ?? '') }}">
                            @error('token')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> -->

                        <!-- Link -->
                        <!-- <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="text" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link', $terminalSetting->link ?? '') }}">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> -->

                        <!-- Terminal ID -->
                        <div class="mb-3">
                            <label for="terminal_id" class="form-label">Terminal ID</label>
                            <input type="text" class="form-control @error('terminal_id') is-invalid @enderror" id="terminal_id" name="terminal_id" value="{{ old('terminal_id', $terminalSetting->terminal_id ?? '') }}">
                            @error('terminal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Secret Key -->
                        <!-- <div class="mb-3">
                            <label for="secret_key" class="form-label">Secret Key</label>
                            <input type="text" class="form-control @error('secret_key') is-invalid @enderror" id="secret_key" name="secret_key" value="{{ old('secret_key', $terminalSetting->secret_key ?? '') }}">
                            @error('secret_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> -->

                        @canany(['restaurant.terminal-settings.store', 'restaurant.terminal-settings.update'])
                        <button type="submit" class="btn btn-primary w-100">Save</button>
                        @endcanany
                    </form>
                </div>
            </div>

            <!-- Terminal Table -->
            <div class="col-md-9">
                <div class="main-heading">
                    <h4>Terminal Management</h4>
                </div>
                <div class="main-content p-3">
                    <table class="table table-striped" id="terminals-list">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Protocol</th>
                                <th>Serial No</th>
                                <th>Device ID</th>
                                <th>Model Number</th>
                                <th>IP Address</th>
                                <th>Port</th>
                                <th>Terminal ID</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($terminals as $terminal)
                                <tr>
                                    <td>{{ $terminal->name }}</td>
                                    <td>{{ $terminal->protocol }}</td>
                                    <td>{{ $terminal->serial_no }}</td>
                                    <td>{{ $terminal->device_id }}</td>
                                    <td>{{ $terminal->model_number }}</td>
                                    <td>{{ $terminal->ip_address }}</td>
                                    <td>{{ $terminal->port }}</td>
                                    <td>{{ $terminal->terminal_id }}</td>
                                    <td>{{ $terminal->status == 1 ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        @can('restaurant.terminal-settings.update')
                                        <a href="#" class="edit-terminal" 
                                           data-id="{{ $terminal->id }}"
                                           data-name="{{ $terminal->name }}"
                                           data-protocol="{{ $terminal->protocol }}"
                                           data-serial_no="{{ $terminal->serial_no }}"
                                           data-device_id="{{ $terminal->device_id }}"
                                           data-model_number="{{ $terminal->model_number }}"
                                           data-ip_address="{{ $terminal->ip_address }}"
                                           data-port="{{ $terminal->port }}"
                                           data-terminal_id="{{ $terminal->terminal_id }}">
                                            <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                        </a>
                                        @endcan
                                        @can('restaurant.terminal-settings.destroy')
                                        <form action="{{ route('restaurant.terminal-settings.destroy', $terminal->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="delete-terminal"  type="submit" style="border: none; background: none;">
                                                <img src="{{ asset('assets/images/dustbin.png') }}" alt="delete">
                                            </button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $terminals->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        // Handle Edit Click
        $(document).on('click', '.edit-terminal', function () {

            $("#terminals-list").find('tr').each(function (i, tr) {
                this.style.removeProperty('background-color');
            });

            var scheduleRow = $(this).closest('tr');
            scheduleRow.css('background-color', '#ffe');

            $('#record_id').val($(this).data('id'));
            $('#name').val($(this).data('name')).focus();
            $('#protocol').val($(this).data('protocol'));
            $('#serial_no').val($(this).data('serial_no'));
            $('#device_id').val($(this).data('device_id'));
            $('#model_number').val($(this).data('model_number'));
            $('#ip_address').val($(this).data('ip_address'));
            $('#port').val($(this).data('port'));
            $('#terminal_id').val($(this).data('terminal_id'));

            $('#form-title').text('Edit Terminal');
        });

        // Confirm Delete
        $(document).on('click', '.delete-terminal', function () {
            return confirm('Are you sure you want to delete this terminal?');
        });
    });
</script>
@endsection
