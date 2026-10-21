@extends('layouts.master')
@section('title')
Hourly Sales Report
@endsection
@section('css')
<link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection
@section('content')
<!--Main Section Start-->
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="main-heading">
                    <h4>Hourly Sales Report</h4>
                    <form method="GET" action="{{ route('hourly-sales-report') }}" class="d-flex align-items-center">
                        <div class="col-4">
                            <input type="text" class="form-control max30Length" id="filter_date" name="date" value="{{ $date }}" />
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control max30Length ms-1" placeholder="From Time" id="from_time" value="{{ $start_time }}" class="filter_time" name="start_time" />
                        </div>
                        <div class="col-1 text-center">To</div>
                        <div class="col-2">
                            <input type="text" class="form-control ms-1" placeholder="To Time" id="end_time" class="filter_time" value="{{ $end_time }}" name="end_time" />
                        </div>
                        &nbsp;&nbsp;
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                    <div>
                        <a href="{{ route('payment-report') }}?start_date={{ $date . ' ' . $start_time }}&end_date={{ $date . ' ' . $end_time }}" class="btn btn-primary" target="_blank">Tickets</a>

                        <button class="btn btn-primary print_btn" href="{{ route('export-hourly-sales-report') }}?format=receipt&start_date={{ $start_date_utc }}&end_date={{ $end_date_utc }}">Print</button>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>

                        <form method="GET" action="{{ route('export-hourly-sales-report') }}" target="_blank" class="d-inline">
                            <input type="hidden" name="format" value="pdf">
                            <input type="hidden" name="start_date" id="export_form_date" value="{{ $start_date_utc }}">
                            <input type="hidden" name="end_date" id="export_to_date" value="{{ $end_date_utc }}">
                            <button type="submit" class="btn btn-primary">PDF</button>
                        </form>

                        <form method="GET" action="{{ route('export-hourly-sales-report') }}" target="_blank" class="d-inline">
                            <input type="hidden" name="start_date" id="export_form_date" value="{{ $start_date_utc }}">
                            <input type="hidden" name="end_date" id="export_to_date" value="{{ $end_date_utc }}">
                            <button type="submit" class="btn btn-primary">Excel</button>
                        </form>
                    </div>
                </div>
            </div>
            @include('layouts.flash-msg')
            <div class="col-12 col-md-12 col-lg-12">
                <div class="main-content p-3">
                    <table id="hourly_sales_report" class="display nowrap w-100">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Cash Sale</th>
                                <th>Credit Sale</th>
                                <th>Other Sale</th>
                                <th>Total Sale</th>
                                <th>Cash Tip</th>
                                <th>Credit Tip</th>
                                <th>Other Tip</th>
                                <th>Total Tip</th>
                                <th>Total Paid</th>
                                <th>Gross Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report as $reportItem)
                                <tr>
                                    <td>{{ $reportItem->hour }}</td>
                                    <td>{{ currencyFormat($reportItem->cash_sale) }}</td>
                                    <td>{{ currencyFormat($reportItem->credit_sale) }}</td>
                                    <td>{{ currencyFormat($reportItem->other_sale) }}</td>
                                    <td>{{ currencyFormat($reportItem->total_sale) }}</td>
                                    <td>{{ currencyFormat($reportItem->cash_tip) }}</td>
                                    <td>{{ currencyFormat($reportItem->credit_tip) }}</td>
                                    <td>{{ currencyFormat($reportItem->other_tip) }}</td>
                                    <td>{{ currencyFormat($reportItem->total_tip) }}</td>
                                    <td>{{ currencyFormat($reportItem->total_paid) }}</td>
                                    <td>{{ currencyFormat($reportItem->gross_paid) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-3">
                        @if($pagination)
                        <div class="d-flex justify-content-center mt-3">
                            {{ $pagination->links('pagination::bootstrap-4') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Main Section End-->

<x-restaurant.email-report action="{{ route('email-hourly-sales-report') }}">
    @section('additionalFields')
        <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
        <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
    @endsection
</x-restaurant.email-report>

@endsection
@section('js')
<script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
<script>
    $(document).ready(function() {
        flatpickr("#filter_date", {
            dateFormat: "m-d-Y",
            allowInput: true
        });

        flatpickr("#from_time, #end_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            minuteIncrement: 1,
        });
    });
</script>

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
