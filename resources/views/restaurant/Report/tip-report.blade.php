@extends('layouts.master')
@section('title', 'Tip Report')
@section('css')
<link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Tip Report</h4>
            <div class="d-flex gap-1">
                <form method="GET" action="{{ route('report.export-tip-report') }}">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}" id="export_start_date">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}" id="export_end_date">
                    <button type="submit" class="btn btn-success">Excel</button>
                </form>

                <button class="btn btn-success btn-sm print_btn" href="{{ route('report.export-tip-report') }}?format=pdf&start_date={{ $start_date_utc }}&end_date={{ $end_date_utc }}">PDF</button>

                <button class="btn btn-success btn-sm print_btn" href="{{ route('report.export-tip-report') }}?format=receipt&start_date={{ $start_date_utc }}&end_date={{ $end_date_utc }}">Print Receipt</button>

                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>
            </div>
        </div>

        <div class="card p-3 shadow-sm">
            <form method="GET" action="{{ route('report.tip-report') }}" class="row g-3 align-items-center" id="filter-form">
                @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])
            </form>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Cash Tip</th>
                        <th>Credit Card Tip</th>
                        <th>Crypto Tip</th>
                        <th>Other Tip</th>
                        <th>Shared Tip</th>
                        <th>Total Tip</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tip_date)->format('d M Y') }}</td>
                        <td>{{ $row->employee }}</td>
                        <td>{{ currencyFormat($row->cash_tip) }}</td>
                        <td>{{ currencyFormat($row->credit_tip) }}</td>
                        <td>{{ currencyFormat($row->crypto_tip) }}</td>
                        <td>{{ currencyFormat($row->other_tip) }}</td>
                        <td class="{{ $row->tip_shared > 0 ? 'text-danger' : '' }}">{{ $row->tip_shared > 0 ? currencyFormat($row->tip_shared) : '-' }}</td>
                        <td>{{ currencyFormat($row->total_tip) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No tips found for this period.</td>
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

<x-restaurant.email-report action="{{ route('report.email-tip-report') }}">
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
    $(".print_btn").on('click', function (e) {
        e.preventDefault();
        
        let win = window.open($(this).attr('href'), "_blank");
        
        win.addEventListener("load", () => {
            win.print();
        });
    })
</script>
@endsection
