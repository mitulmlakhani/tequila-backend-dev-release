@extends('layouts.master')
@section('title', 'Issued Gift Cards Report')
@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection
@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Issued Gift Cards</h4>
                <a href="{{ route('report.export-gift-card-issued') }}?start_date={{ $start_date_utc }}&end_date={{ $end_date_utc }}&gift_card_number={{ $gift_card_number }}"
                    class="btn btn-success">Export</a>
            </div>

            <div class="card p-3 shadow-sm">
                <form method="GET" action="{{ route('report.gift-card-issued') }}" class="row g-3 align-items-center" id="filter-form">
                    @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])

                    <div class="col-md-3">
                        <label for="gift_card_number" class="form-label">Gift Card Number</label>
                        <input type="text" name="gift_card_number" placeholder="Gift Card Number" class="form-control" value="{{ $gift_card_number }}">
                    </div>
                </form>
            </div>


            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped w-100 text-nowrap">
                    <thead class="table-dark">
                        <tr>
                            <th>Card Number</th>
                            <th>Initial Amount</th>
                            <th>Current Balance</th>
                            <th>Expiration Date</th>
                            <th>Status</th>
                            <th>Issued On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report as $row)
                            @php
                                $eAt = \Carbon\Carbon::parse($row->expiration_date);
                                $cAt = \Carbon\Carbon::parse($row->created_at);
                                $from = $cAt->setTimeZone(auth()->user()->restaurant->timezone)->startOfDay()->format('m-d-Y H:i');
                                $to = $eAt->setTimeZone(auth()->user()->restaurant->timezone)->endOfDay()->format('m-d-Y H:i');
                            @endphp
                            <tr>
                                <td>{{ $row->card_number }}</td>
                                <td>{{ number_format($row->initial_amount, 2) }}</td>
                                <td>{{ number_format($row->current_balance, 2) }}</td>
                                <td>{{ $eAt->format('d-m-Y') }}</td>
                                <td>{{ ucfirst($row->status) }}</td>
                                <td>{{ $cAt->format('d-m-Y H:i') }}</td>
                                <td>
                                    <a class="btn btn-primary" target="_blank" href="{{ route('report.gift-card-usage') }}?gift_card_number={{ $row->card_number }}&start_date={{ $from }}&end_date={{ $to }}">View Usage</a>
                                    <a class="btn btn-primary" target="_blank" href="{{ route('report.gift-cards-transaction') }}?gift_card_number={{ $row->card_number }}&start_date={{ $from }}&end_date={{ $to }}">View Transactions</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No data found for selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($pagination)
                    <div class="d-flex justify-content-center mt-3">
                        {{ $pagination->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/reports/date-time-header-filter.js') }}"></script>
@endsection