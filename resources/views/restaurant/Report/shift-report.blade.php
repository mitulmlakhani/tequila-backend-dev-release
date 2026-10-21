@extends('layouts.master')
@section('title', 'Drawer Report')

@section('css')
<link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Drawer Report</h4>
            <div class="d-flex align-items-center">
                <form method="GET" action="{{ route('report.export-shift-report') }}" target="_blank">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <input type="hidden" name="cashier_id" value="{{ $selected_cashier }}">
                    <button type="submit" class="btn btn-success">Export</button>
                </form>
                <form method="GET" action="{{ route('report.print-shift-report') }}" target="_blank" class="ms-2">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <input type="hidden" name="cashier_id" value="{{ $selected_cashier }}">
                    <button type="submit" class="btn btn-primary">Print</button>
                </form>

                <button type="button" class="btn btn-success print_btn ms-2" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card p-3 shadow-sm">
            <form method="GET" action="{{ route('report.shift-report') }}" id="filter-form">
                @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])
                <div class="col-md-3 mt-3">
                    <label for="cashier_id" class="form-label">Select Cashier</label>
                    <select name="cashier_id" id="cashier_id" class="form-select">
                        <option value="">All Cashiers</option>
                        @foreach($cashiers as $id => $name)
                            <option value="{{ $id }}" {{ $selected_cashier == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <!-- Report Table -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Opening</th>
                        <th>Closing</th>
                        <th>Cash Out</th>
                        <!-- <th>Balance</th> -->
                        <th>Cash Collected</th>
                        <th>Credit Collected</th>
                        <th>Other Collected</th>
                        <th>Tips</th>
                        <th>Refunds</th>
                        <th>Net Owed</th>
                        <th>Final Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report as $row)
                        <tr>
                            <td>{{ $row['date'] }}</td>
                            <td>{{ $row['cashier'] }}</td>
                            <td>{{ $row['start_time'] }}</td>
                            <td>{{ $row['end_time'] }}</td>
                            <td>{{ $row['opening_amount'] }}</td>
                            <td>{{ $row['closing_amount'] }}</td>
                            <td>{{ $row['cashout'] }}</td>
                            <!-- <td>{{ $row['balance'] }}</td> -->
                            <td>{{ $row['cash_collected'] }}</td>
                            <td>{{ $row['credit_collected'] }}</td>
                            <td>{{ $row['other_collected'] }}</td>
                            <td>{{ $row['tip_amount'] }}</td>
                            <td>{{ $row['refunds'] }}</td>
                            <td>{{ $row['net_owed'] }}</td>
                            <td>{{ $row['final_balance'] ?? '0.00' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if ($pagination)
            <div class="d-flex justify-content-center mt-3">
                {!! $pagination->appends(request()->except('page'))->links('pagination::bootstrap-4') !!}
            </div>
        @endif
    </div>
</div>

<x-restaurant.email-report action="{{ route('report.email-shift-report') }}">
    @section('additionalFields')
        <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
        <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
    @endsection
</x-restaurant.email-report>
@endsection

@section('js')
<script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
<script src="{{ asset('assets/js/reports/date-time-header-filter.js') }}"></script>
@endsection
