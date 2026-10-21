@extends('layouts.master')
@section('title', 'Payment Report')
@section('css')
<link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection
@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Payment Report</h4>

            <div class="d-flex gap-2">
                <form method="GET" action="{{ route('export-payment-report') }}">
                    <input type="hidden" name="format" value="excel" />
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <input type="hidden" name="payment_method_id" value="{{ request('payment_method_id') }}">
                    <input type="hidden" name="order_id" value="{{ request('order_id') }}">
                    <input type="hidden" name="transaction_id" value="{{ request('transaction_id') }}">
                    <input type="hidden" name="gift_card_code" value="{{ request('gift_card_code') }}">
                    <button type="submit" class="btn btn-success">Excel</button>
                </form>
                
                <form method="GET" action="{{ route('export-payment-report') }}">
                    <input type="hidden" name="format" value="pdf" />
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <input type="hidden" name="payment_method_id" value="{{ request('payment_method_id') }}">
                    <input type="hidden" name="order_id" value="{{ request('order_id') }}">
                    <input type="hidden" name="transaction_id" value="{{ request('transaction_id') }}">
                    <input type="hidden" name="gift_card_code" value="{{ request('gift_card_code') }}">
                    <button type="submit" class="btn btn-success">PDF</button>
                </form>

                <form method="GET" action="{{ route('export-payment-report') }}" target="_blank">
                    <input type="hidden" name="format" value="receipt" />
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <input type="hidden" name="payment_method_id" value="{{ request('payment_method_id') }}">
                    <input type="hidden" name="order_id" value="{{ request('order_id') }}">
                    <input type="hidden" name="transaction_id" value="{{ request('transaction_id') }}">
                    <input type="hidden" name="gift_card_code" value="{{ request('gift_card_code') }}">
                    <button type="submit" class="btn btn-success">Print</button>
                </form>

                <button type="button" class="btn btn-success btn-sm print_btn" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card p-3 shadow-sm">
            <form method="GET" action="{{ route('payment-report') }}" class="row g-3 align-items-center" id="filter-form">
                    @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])

                    <div class="col-md-3">
                        <label for="payment_method_id" class="form-label">Payment Method</label>
                        <select name="payment_method_id" class="form-select">
                            <option value="">All Methods</option>
                            @foreach($payment_methods as $method)
                                <option value="{{ $method->id }}" {{ request('payment_method_id') == $method->id ? 'selected' : '' }}>{{ $method->name ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="order_id" class="form-label">Order ID</label>
                        <input type="text" name="order_id" class="form-control" value="{{ request('order_id') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="transaction_id" class="form-label">Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control" value="{{ request('transaction_id') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="gift_card_code" class="form-label">Gift Card Code</label>
                        <input type="text" name="gift_card_code" class="form-control" value="{{ request('gift_card_code') }}">
                    </div>
            </form>
        </div>

        <!-- Report Table -->
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Payment Method</th>
                        <th>Transaction ID</th>
                        <th>Gift Card Code</th>
                        <th>Amount</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report as $data)
                        <tr>
                            <td><a href="{{ route('order', $data['order_id']) }}">#{{ $data['order_id'] }}</a></td>
                            <td>{{ $data['payment_method'] }}</td>
                            <td>{{ $data['transaction_id'] }}</td>
                            <td>{{ $data['gift_card_code'] }}</td>
                            <td>${{ $data['amount'] }}</td>
                            <td>{{ $data['date'] }}</td>
                            <td>{{ $data['status'] }}</td>
                        </tr>
                    @endforeach
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

<x-restaurant.email-report action="{{ route('export-payment-email') }}">
    @section('additionalFields')
        <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
        <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
        <input type="hidden" name="payment_method_id" value="{{ request('payment_method_id') }}">
        <input type="hidden" name="order_id" value="{{ request('order_id') }}">
        <input type="hidden" name="transaction_id" value="{{ request('transaction_id') }}">
        <input type="hidden" name="gift_card_code" value="{{ request('gift_card_code') }}">
    @endsection
</x-restaurant.email-report>

@endsection
@section('js')
<script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
<script src="{{ asset('assets/js/reports/date-time-header-filter.js') }}"></script>
@endsection
