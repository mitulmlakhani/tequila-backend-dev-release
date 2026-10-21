@extends('layouts.master')
@section('title', 'Batch Report')

@section('css')
<link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Batch Report</h4>

            <div class="d-flex gap-2">
                <form method="GET" action="{{ route('reports.batch.export') }}">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <button type="submit" class="btn btn-success btn-sm">Excel</button>
                </form>

                <form method="GET" action="{{ route('reports.batch.export') }}">
                    <input type="hidden" name="format" value="pdf">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <button type="submit" class="btn btn-success btn-sm">PDF</button>
                </form>

                <form method="GET" action="{{ route('reports.batch.export') }}" target="_blank">
                    <input type="hidden" name="format" value="receipt">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <button type="submit" class="btn btn-success btn-sm">Receipt</button>
                </form>

                <button type="button" class="btn btn-success btn-sm print_btn" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>
            </div>
        </div>

        <div class="card p-3 shadow-sm">
            <form method="GET" class="row g-3 align-items-center" id="filter-form">
                @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])
            </form>
        </div>

        <!-- Report Table -->
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Sale Date</th>
                        <th>Batch Number</th>
                        <th>Terminal</th>
                        <th>Location</th>
                        <th>Transactions</th>
                        <th>Amount</th>
                        <th>Tip</th>
                        <th>Returns</th>
                        <th>Total</th>
                        <th>Card Fees</th>
                        <th>Deposits</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->datetime_group)->format('d-m-Y h:i A') }}</td>
                            <td>
                                <a href="{{ route('reports.batch.detail', ['batch_number' => $row->batch_number]) . '?start_date=' . $start_date . '&end_date=' . $end_date }}">
                                    #{{ $row->batch_number }}
                                </a>
                            </td>
                            <td>{{ $row->terminal_sns }}</td>
                            <td>{{ $row->terminal_locations ?? '-' }}</td>
                            <td>{{ $row->txn_count }}</td>
                            <td>{{ number_format($row->total_amount, 2) }}</td>
                            <td>{{ number_format($row->total_tip, 2) }}</td>
                            <td>{{ number_format($row->total_returned ?? 0, 2) }}</td>
                            <td>{{ number_format($row->grand_total, 2) }}</td>
                            <td>{{ number_format($row->total_fee, 2) }}</td>
                            <td>{{ number_format($row->total_amount + $row->total_tip, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">No data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pagination)
            <div class="d-flex justify-content-center mt-4">
                {{ $pagination->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>

<x-restaurant.email-report action="{{ route('reports.batch.email') }}">
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
