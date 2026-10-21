@extends('layouts.master')
@section('title', 'Order Summary Report')

@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection

@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Order Summary Report</h4>

                <div class="d-flex gap-2">
                    <form method="GET" action="{{ route('export-order-summary-report') }}">
                        <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                        <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                        <button type="submit" class="btn btn-success btn-sm">Excel</button>
                    </form>

                    <form method="GET" action="{{ route('export-order-summary-report') }}">
                        <input type="hidden" name="format" value="pdf">
                        <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                        <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                        <button type="submit" class="btn btn-success btn-sm">PDF</button>
                    </form>

                <a target="_blank" class="btn btn-success btn-sm" href="{{ route('export-order-summary-report') . '?format=receipt&start_date=' . $start_date_utc . '&end_date=' . $end_date_utc }}">Print Receipt</a>

                <button type="button" class="btn btn-success btn-sm print_btn" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>

                </div>
            </div>

            <div class="card p-3 shadow-sm">
                <form method="GET" action="{{ route('order-summary-report') }}" class="row g-3 align-items-center"
                    id="filter-form">
                    @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])
                </form>
            </div>

            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Total Orders</th>
                            <th>Total Sales</th>
                            <th>Total Tax</th>
                            <th>Total Discount</th>
                            <th>Total Tip</th>
                            <th>Tip Tx Fees</th>
                            <th>Gross Total</th>
                            <th>Total Paid</th>
                            <th>Gross Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report as $row)
                            <tr>
                                <td>{{ $row['order_date'] }}</td>
                                <td>{{ $row['total_orders'] }}</td>
                                <td>{{ $row['total_sales'] }}</td>
                                <td>{{ $row['total_tax'] }}</td>
                                <td>{{ $row['total_discount'] }}</td>
                                <td>{{ $row['total_tip'] }}</td>
                                <td>{{ $row['total_tip_transaction_fees'] }}</td>
                                <td>{{ $row['grand_total'] }}</td>
                                <td>{{ $row['total_paid'] }}</td>
                                <td>{{ $row['gross_paid'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pagination)
                <div class="d-flex justify-content-center mt-3">
                    {{ $pagination->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    <x-restaurant.email-report action="{{ route('export-payment-email') }}">
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