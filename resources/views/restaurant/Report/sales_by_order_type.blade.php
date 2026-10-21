@extends('layouts.master')
@section('title', 'Sales by Order Type Report')

@section('css')
<link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Sales by Order Type Report</h4>
            <div class="d-flex gap-2">
                <form method="GET" action="{{ route('export-sales-by-order-type-report') }}">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <input type="hidden" name="sort_by" value="{{ $sort_by }}">
                    <button type="submit" class="btn btn-success">Excel</button>
                </form>

                <form method="GET" action="{{ route('export-sales-by-order-type-report') }}">
                    <input type="hidden" name="format" value="pdf">
                    <input type="hidden" name="start_date" id="export_start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" id="export_end_date" value="{{ $end_date_utc }}">
                    <button type="submit" class="btn btn-success">PDF</button>
                </form>

                <a target="_blank" class="btn btn-success" href="{{ route('export-sales-by-order-type-report') . '?format=receipt&start_date=' . $start_date_utc . '&end_date=' . $end_date_utc }}">Print Receipt</a>

                <button type="button" class="btn btn-success print_btn" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>
            </div>
        </div>

        <div class="card p-3 shadow-sm">
            <form method="GET" action="{{ route('sales-by-order-type-report') }}" class="row g-3 align-items-center" id="filter-form">
                @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])

                <div class="d-flex gap-2">
                    <div>
                        <input type="radio" class="btn-check sort_radio" name="sort_by" value="most_orders" id="most_orders" {{ $sort_by == "most_orders" ? 'checked' : '' }}>
                        <label class="btn btn-success" for="most_orders">Most Orders</label>
                    </div>

                    <div>
                        <input type="radio" class="btn-check sort_radio" name="sort_by" value="least_orders" id="least_orders" {{ $sort_by == "least_orders" ? 'checked' : '' }}>
                        <label class="btn btn-success" for="least_orders">Least Orders</label>
                    </div>

                    <div>
                        <input type="radio" class="btn-check sort_radio" name="sort_by" value="total_sales" id="total_sales" {{ $sort_by == "total_sales" ? 'checked' : '' }}>
                        <label class="btn btn-success" for="total_sales">Total Sales</label>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Order Type</th>
                        <th>Total Orders</th>
                        <th>Total Discount</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $data)
                    <tr>
                        <td>{{ $data['order_type'] }}</td>
                        <td>{{ $data['total_orders'] ?? 0 }}</td>
                        <td>{{ $data['total_discount'] ?? 0 }}</td>
                        <td>{{ $data['total_sales'] ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No sales found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<x-restaurant.email-report action="{{ route('email-sales-by-order-type-report') }}">
    @section('additionalFields')
        <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
        <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
    @endsection
</x-restaurant.email-report>
@endsection

@section('js')
<script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
<script src="{{ asset('assets/js/reports/date-time-header-filter.js') }}"></script>

<script>
    $(document).ready(function () {
        $(".sort_radio").on('change', function () {
            $(".date-time-filter-submit-btn").click();
        });
    });
</script>
@endsection
