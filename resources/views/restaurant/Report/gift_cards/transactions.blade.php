@extends('layouts.master')
@section('title', 'Gift Cards Transaction Report')

@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection

@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Gift Card Transactions</h4>
                <a href="{{ route('report.export-gift-cards-transaction') }}?start_date={{ $start_date_utc }}&end_date={{ $end_date_utc }}&gift_card_number={{ $gift_card_number }}"
                    class="btn btn-success">Export</a>
            </div>

            <div class="card p-3 shadow-sm">
                <form method="GET" action="{{ route('report.gift-cards-transaction') }}" class="row g-3 align-items-center"
                    id="filter-form">
                    @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])

                    <div class="col-md-3">
                        <label for="gift_card_number" class="form-label">Gift Card Number</label>
                        <input type="text" name="gift_card_number" placeholder="Gift Card Number" class="form-control" value="{{ $gift_card_number }}">
                    </div>
                </form>
            </div>

            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped text-nowrap w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>Card Number</th>
                            <th>Initial Amount</th>
                            <th>Current Balance</th>
                            <th>Transaction Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Transaction Date</th>
                            <th>Processed By</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report as $row)
                            <tr>
                                <td>{{ $row->card_number }}</td>
                                <td>{{ number_format($row->initial_amount, 2) }}</td>
                                <td>{{ number_format($row->current_balance, 2) }}</td>
                                <td>{{ ucfirst($row->transaction_type) }}</td>
                                <td>{{ number_format($row->amount, 2) }}</td>
                                <td>{{ $row->description }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->transaction_date)->format('d-m-Y H:i') }}</td>
                                <td>{{ $row->processed_by }}</td>
                                <td>{{ $row->branch_restaurant_name }} {{ $row->branch_branch_name ? ' - ' . $row->branch_branch_name : '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No data found.</td>
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
@endsection

@section('js')
    <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/reports/date-time-header-filter.js') }}"></script>
@endsection