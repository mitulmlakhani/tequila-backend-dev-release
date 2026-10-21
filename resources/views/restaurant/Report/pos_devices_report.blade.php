@extends('layouts.master')
@section('title', 'POS Devices Report')

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Devices Report</h4>
            <div class="d-flex align-items-end gap-2">
                <form method="GET" action="{{ route('report.pos-devices-report') }}" class="d-flex align-items-end gap-2 mb-0">
                    <div>
                        <label for="type" class="form-label mb-0 small">Device Type</label>
                        <select name="type" id="type" class="form-select form-select-sm">
                            <option value="">All</option>
                            @foreach($deviceTypes as $key => $label)
                                <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
                <form method="GET" action="{{ route('report.export-pos-devices-report') }}">
                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <button type="submit" class="btn btn-sm btn-success">Export CSV</button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark text-nowrap">
                    <tr>
                        <th>Device Name</th>
                        <th>Device ID</th>
                        <th>Serial No</th>
                        <th>Type</th>
                        <th>Protocol</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Attached Terminals</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $device)
                        <tr>
                            <td class="text-nowrap">{{ $device->name }}</td>
                            <td class="text-break">{{ $device->device_id }}</td>  <!-- Wrapped -->
                            <td class="text-break">{{ $device->serial_no }}</td>  <!-- Wrapped -->
                            <td class="text-nowrap">{{ ucfirst($device->type) }}</td>
                            <td class="text-nowrap">{{ $device->protocol }}</td>
                            <td class="text-nowrap">{{ $device->ip_address }}</td>
                            <td class="text-nowrap">{{ $device->status ? 'Active' : 'Inactive' }}</td>
                            <td class="text-nowrap">{{ $device->created_at?->format('Y-m-d') }}</td>
                            <td>
                                @if($device->terminals->isNotEmpty())
                                    <ul class="pl-3 mb-0">
                                        @foreach($device->terminals as $terminal)
                                            <li>{{ $terminal->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <em>No Terminals</em>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No POS devices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $data->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection