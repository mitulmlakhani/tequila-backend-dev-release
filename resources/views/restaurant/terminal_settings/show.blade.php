@extends('layouts.master')
@section('title', 'Terminal Setting Details')
@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="main-heading mb-3">
            <h4>Terminal Setting Details</h4>
        </div>
        <div class="main-content p-3 bg-white shadow-sm rounded">
            <table class="table table-bordered">
                <tr>
                    <th>Restaurant</th>
                    <td>{{ $terminalSetting->restaurant->name }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $terminalSetting->name }}</td>
                </tr>
                <tr>
                    <th>Protocol</th>
                    <td>{{ $terminalSetting->protocol }}</td>
                </tr>
                <tr>
                    <th>Model Number</th>
                    <td>{{ $terminalSetting->model_number }}</td>
                </tr>
                <tr>
                    <th>Serial No</th>
                    <td>{{ $terminalSetting->serial_no }}</td>
                </tr>
                <tr>
                    <th>Device ID</th>
                    <td>{{ $terminalSetting->device_id }}</td>
                </tr>
                <tr>
                    <th>IP Address</th>
                    <td>{{ $terminalSetting->ip_address }}</td>
                </tr>
                <tr>
                    <th>Port</th>
                    <td>{{ $terminalSetting->port }}</td>
                </tr>
                <tr>
                    <th>Token</th>
                    <td>{{ $terminalSetting->token }}</td>
                </tr>
                <tr>
                    <th>Link</th>
                    <td>{{ $terminalSetting->link }}</td>
                </tr>
                <tr>
                    <th>Terminal ID</th>
                    <td>{{ $terminalSetting->terminal_id }}</td>
                </tr>
                <tr>
                    <th>Secret Key</th>
                    <td>{{ $terminalSetting->secret_key }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $terminalSetting->status ? 'Paired' : 'Vacant' }}</td>
                </tr>
            </table>
            <a href="{{ route('restaurant.terminal-settings.index') }}" class="btn btn-primary mt-3">Back to List</a>
        </div>
    </div>
</div>
@endsection
