@extends('layouts.master')
@section('title', 'Sales by Shift Report')

@section('css')
<link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Sales by Shift Report</h4>

            <div class="d-flex gap-2">
                <form method="GET" action="{{ route('report.export-sales-by-shift-report') }}">
                    <input type="hidden" name="start_date" id="export_start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" id="export_end_date" value="{{ $end_date_utc }}">
                    <button type="submit" class="btn btn-success">Excel</button>
                </form>
                
                <form method="GET" action="{{ route('report.export-sales-by-shift-report') }}">
                    <input type="hidden" name="format" value="pdf">
                    <input type="hidden" name="start_date" id="export_start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" id="export_end_date" value="{{ $end_date_utc }}">
                    <button type="submit" class="btn btn-success">PDF</button>
                </form>

                <a target="_blank" class="btn btn-success" href="{{ route('report.export-sales-by-shift-report') . '?format=receipt&start_date=' . $start_date_utc . '&end_date=' . $end_date_utc }}">Print Receipt</a>

                <button type="button" class="btn btn-success print_btn" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>
            </div>
        </div>

        <div class="card p-3 shadow-sm">
            <form method="GET" action="{{ route('report.sales-by-shift-report') }}" class="row g-3 align-items-center" id="filter-form">
                @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])
            </form>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Cashier</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Orders</th>
                        <th>Total Discount</th>
                        <th>Total Sales</th>
                        <th>Delivery Partner Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $row)
                    <tr>
                        <td>{{ $row->cashier_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->start_time)->format('m-d-Y h:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->end_time)->format('m-d-Y h:i A') }}</td>
                        <td>{{ $row->total_orders }}</td>
                        <td>{{ number_format($row->total_discount, 2) }}</td>
                        <td>{{ number_format($row->total_sales, 2) }}</td>
                        <td>{{ number_format($row->delivery_partner_sales, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No sales found for this period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pagination)
        <div class="d-flex justify-content-center mt-3">
            {{ $pagination->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>

<x-restaurant.email-report action="{{ route('report.email-sales-by-shift-report') }}">
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
