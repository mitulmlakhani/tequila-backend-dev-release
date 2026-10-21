@extends('layouts.master')
@section('title', 'Batch Detail - #' . $batch_number)

@section('css')
<link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Batch #{{ $batch_number }} - Transaction Detail</h4>
            <a href="{{ route('reports.batch') }}?start_date={{ $start_date }}&end_date={{ $end_date }}" class="btn btn-secondary btn-sm">← Back to Batch Report</a>

            <div class="d-flex gap-2">
                <form method="GET" action="{{ route('reports.batch.detail.export', ['batchNumber' => $batch_number]) }}">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <button type="submit" class="btn btn-success btn-sm">Excel</button>
                </form>

                <form method="GET" action="{{ route('reports.batch.detail.export', ['batchNumber' => $batch_number]) }}" target="_blank">
                    <input type="hidden" name="format" value="receipt">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <button type="submit" class="btn btn-success btn-sm">Print</button>
                </form>

                <button type="button" class="btn btn-success btn-sm print_btn" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>
            </div>
        </div>

        <div class="card p-3 shadow-sm">
            <form method="GET" class="row g-3 align-items-center" id="filter-form">
                @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])
            </form>
        </div>

        <!-- Transaction Table -->
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Sale Date</th>
                        <th>Deposit Date</th>
                        <th>Employee</th>
                        <th>Terminal</th>
                        <th>Location</th>
                        <th>Transaction ID</th>
                        <th>Order ID</th>
                        <th>Approval Number</th>
                        <th>Card Holder</th>
                        <th>Card Number</th>
                        <th>Sale</th>
                        <th>Tip</th>
                        <th>Sub Total</th>
                        <th>CC Fee</th>
                        <th>Tax</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $txn)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($txn['created_at'])->format('d-m-Y h:i A') }}</td>
                        <td>{{ $txn['deposit_settled_at'] ? \Carbon\Carbon::parse($txn['deposit_settled_at'])->format('d-m-Y h:i A') : '-' }}</td>
                        <td>{{ $txn['staff_name'] }}</td>
                        <td>{{ $txn['terminal_sn'] }}</td>
                        <td>{{ $txn['terminal_location'] }}</td>
                        <td>#{{ $txn['id'] }}</td>
                        <td>{{ $txn['order_id'] }}</td>
                        <td>{{ $txn['approval_code'] }}</td>
                        <td>{{ $txn['card_holder'] }}</td>
                        <td>{{ $txn['card_number'] }}</td>
                        <td>{{ number_format($txn['amount'], 2) }}</td>
                        <td>{{ number_format($txn['tip_amount'], 2) }}</td>
                        <td>{{ number_format($txn['amount'] + $txn['tip_amount'], 2) }}</td>
                        <td>{{ number_format($txn['transaction_fee'], 2) }}</td>
                        <td>{{ number_format($txn['tax_amount'], 2) }}</td>
                        <td>{{ number_format($txn['grand_total'], 2) }}</td>
                        <td>{{ $txn['is_deposit'] ? 'Deposited' : 'Not Deposited' }}</td>
                    </tr>
                    @empty
                        <tr><td colspan="17" class="text-center">No transactions found for this batch.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<x-restaurant.email-report action="{{ route('reports.batch.detail.email', $batch_number) }}">
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
