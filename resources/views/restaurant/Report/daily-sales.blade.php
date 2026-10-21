@extends('layouts.master')
@section('title')
    Daily Sales Report
@endsection
@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Daily Sales Report</h4>
                <div class="d-flex gap-1">
                <form method="GET" action="{{ route('export-daily-sales-report') }}">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <button type="submit" class="btn btn-success btn-sm">Excel</button>
                </form>
 
                <form method="GET" action="{{ route('export-daily-sales-report') }}">
                    <input type="hidden" name="format" value="pdf">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <button type="submit" class="btn btn-success btn-sm">PDF</button>
                </form>
                
                <button class="btn btn-success print_btn btn-sm" href="{{ route('export-daily-sales-report') }}?format=receipt&start_date={{ $start_date_utc }}&end_date={{ $end_date_utc }}">Print Receipt</button>

                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>
                </div>
            </div>

            <div class="card p-3 shadow-sm">
                <form method="GET" class="row g-3 align-items-center" id="filter-form">
                    @include('restaurant.Report.partials.date-time-header', [
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                    ])
                </form>
            </div>

            @include('layouts.flash-msg')
            <div class="table-responsive mt-4 fixed-table">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Cash Sale</th>
                        <th>Credit Sale</th>
                        <th>Other Sale</th>
                        <th>Total Sale</th>
                        <th>Total Tax</th>
                        <th>Cash Tip</th>
                        <th>Credit Tip</th>
                        <th>Other Tip</th>
                        <th>Total Tip</th>
                        <th>Total Paid</th>
                        <th>Gross Paid</th>
                        <th>Delivery Partner Sales</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report as $row)
                        <tr>
                            <td>{{ date('d, M Y', strtotime($row->sale_date)) }}</td>
                            <td>{{ currencyFormat($row->cash_sale) }}</td>
                            <td>{{ currencyFormat($row->credit_sale) }}</td>
                            <td>{{ currencyFormat($row->other_sale) }}</td>
                            <td>{{ currencyFormat($row->total_sales) }}</td>
                            <td>{{ currencyFormat($row->total_tax) }}</td>
                            <td>{{ currencyFormat($row->cash_tip) }}</td>
                            <td>{{ currencyFormat($row->credit_tip) }}</td>
                            <td>{{ currencyFormat($row->other_tip) }}</td>
                            <td>{{ currencyFormat($row->total_tip) }}</td>
                            <td>{{ currencyFormat($row->total_paid) }}</td>
                            <td>{{ currencyFormat($row->gross_paid) }}</td>
                            <td>{{ currencyFormat($row->delivery_partner_sales) }}</td>
                            <td>
                                @php
                                    $restaurant = getCurrentRestaurant();
                                    $date = dateFormat($row->sale_date, 'Y-m-d');
                                    $openDate = \Carbon\Carbon::createFromFormat('Y-m-d h:i A', $date . ' ' . $restaurant->open_time, $restaurant->timezone ?: 'UTC')->format('m-d-Y H:i');
                                    $closeDate = \Carbon\Carbon::createFromFormat('Y-m-d h:i A', $date . ' ' . $restaurant->close_time, $restaurant->timezone ?: 'UTC')->format('m-d-Y H:i'); 
                                @endphp
                                <a href="{{ route('payment-report') }}?start_date={{ $openDate }}&end_date={{ $closeDate }}"
                                    class="btn btn-primary" target="_blank">Tickets</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
            <!-- Pagination Links -->
            @if($pagination)
                <div class="d-flex justify-content-center mt-3">
                    {{ $pagination->links('pagination::bootstrap-4') }}
                </div>
            @endif

        </div>
    </div>
    <!--Main Section End-->

    <x-restaurant.email-report action="{{ route('email-daily-sales-report') }}">
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