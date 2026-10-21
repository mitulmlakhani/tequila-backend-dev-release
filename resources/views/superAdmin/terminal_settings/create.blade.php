@extends('layouts.master')
@section('title', isset($terminalSetting) ? 'Edit Terminal Setting' : 'Add Terminal Setting')
@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="main-heading mb-3">
            <h4>{{ isset($terminalSetting) ? 'Edit Terminal Setting' : 'Add Terminal Setting' }}</h4>
        </div>
        <div class="main-content p-3 bg-white shadow-sm rounded">

            <!-- Global Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ isset($terminalSetting) ? route('super-admin.terminal-settings.update', $terminalSetting) : route('super-admin.terminal-settings.store') }}">
                @csrf
                @if(isset($terminalSetting))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <!-- Restaurant -->
                    <div class="col-md-6">
                        <label for="restaurant_id" class="form-label">Restaurant</label>
                        <select name="restaurant_id" id="restaurant_id" class="form-control @error('restaurant_id') is-invalid @enderror" required>
                            <option value="" disabled>Select Restaurant</option>
                            @foreach($restaurants as $restaurant)
                                <option value="{{ $restaurant->id }}" {{ old('restaurant_id', $terminalSetting->restaurant_id ?? '') == $restaurant->id ? 'selected' : '' }}>
                                    {{ $restaurant->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('restaurant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Protocol -->
                    <div class="col-md-6">
                        <label for="protocol" class="form-label">Protocol</label>
                        <select name="protocol" id="protocol" class="form-control @error('protocol') is-invalid @enderror" required>
                            <option value="HTTP" {{ old('protocol', $terminalSetting->protocol ?? '') == 'HTTP' ? 'selected' : '' }}>HTTP</option>
                            <option value="TCPIP" {{ old('protocol', $terminalSetting->protocol ?? '') == 'TCPIP' ? 'selected' : '' }}>TCPIP</option>
                        </select>
                        @error('protocol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Device Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $terminalSetting->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
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


                    <!-- Serial Number -->
                    <div class="col-md-6">
                        <label for="serial_no" class="form-label">Serial Number</label>
                        <input type="text" class="form-control @error('serial_no') is-invalid @enderror" id="serial_no" name="serial_no" value="{{ old('serial_no', $terminalSetting->serial_no ?? '') }}" required>
                        @error('serial_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Device ID -->
                    <div class="col-md-6">
                        <label for="device_id" class="form-label">Device ID</label>
                        <input type="text" class="form-control @error('device_id') is-invalid @enderror" id="device_id" name="device_id" value="{{ old('device_id', $terminalSetting->device_id ?? '') }}" required>
                        @error('device_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- IP Address -->
                    <div class="col-md-6">
                        <label for="ip_address" class="form-label">IP Address</label>
                        <input type="text" class="form-control @error('ip_address') is-invalid @enderror" id="ip_address" name="ip_address" value="{{ old('ip_address', $terminalSetting->ip_address ?? '') }}">
                        @error('ip_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Port -->
                    <div class="col-md-6">
                        <label for="port" class="form-label">Port</label>
                        <input type="number" class="form-control @error('port') is-invalid @enderror" id="port" name="port" value="{{ old('port', $terminalSetting->port ?? '') }}">
                        @error('port')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Token -->
                    <div class="col-md-6">
                        <label for="token" class="form-label">Token</label>
                        <input type="text" class="form-control @error('token') is-invalid @enderror" id="token" name="token" value="{{ old('token', $terminalSetting->token ?? '') }}">
                        @error('token')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Link -->
                    <div class="col-md-6">
                        <label for="link" class="form-label">Link</label>
                        <input type="text" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link', $terminalSetting->link ?? '') }}">
                        @error('link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Terminal ID -->
                    <div class="col-md-6">
                        <label for="terminal_id" class="form-label">Terminal ID</label>
                        <input type="text" class="form-control @error('terminal_id') is-invalid @enderror" id="terminal_id" name="terminal_id" value="{{ old('terminal_id', $terminalSetting->terminal_id ?? '') }}">
                        @error('terminal_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Secret Key -->
                    <div class="col-md-6">
                        <label for="secret_key" class="form-label">Secret Key</label>
                        <input type="text" class="form-control @error('secret_key') is-invalid @enderror" id="secret_key" name="secret_key" value="{{ old('secret_key', $terminalSetting->secret_key ?? '') }}">
                        @error('secret_key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="col-md-12">
                        <button type="submit" id="submit_button" class="btn btn-primary w-100">{{ isset($terminalSetting) ? 'Update' : 'Save' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const restaurantId = document.getElementById('restaurant_id');
        const deviceId = document.getElementById('device_id');
        const deviceIdError = document.getElementById('device_id_error');
        const submitButton = document.getElementById('submit_button');

        function validateDeviceId() {
            const restaurantIdValue = restaurantId.value;
            const deviceIdValue = deviceId.value;
            const id = "{{ $terminalSetting->id ?? '' }}"; // Include ID if editing

            if (restaurantIdValue && deviceIdValue) {
                fetch("{{ route('super-admin.terminal-settings.check-device-id') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ restaurant_id: restaurantIdValue, device_id: deviceIdValue, id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        deviceIdError.style.display = 'block';
                        submitButton.disabled = true;
                    } else {
                        deviceIdError.style.display = 'none';
                        submitButton.disabled = false;
                    }
                })
                .catch(() => {
                    // Handle any unexpected errors
                    deviceIdError.style.display = 'none';
                    submitButton.disabled = false;
                });
            }
        }

        restaurantId.addEventListener('change', validateDeviceId);
        deviceId.addEventListener('input', validateDeviceId);


        const ipAddressField = document.getElementById('ip_address');

        ipAddressField.addEventListener('input', function (event) {
            // Allow only digits, dots, and backspace
            this.value = this.value.replace(/[^0-9.]/g, '');
        });
    });
</script>
@endsection
