@extends('layouts.master')
@section('title')
    Shift Summary Report
@endsection
@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .center {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Shift Summary Report</h4>

                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>

                    <form method="GET" action="{{ route('report.export-shift-summary-report-excel') }}">
                        <input type="hidden" name="sections" class="section_input">
                        <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                        <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                        <button type="submit" class="btn btn-success btn-sm">Excel</button>
                    </form>

                    <form method="GET" action="{{ route('report.export-shift-summary-report-pdf') }}" target="_blank">
                        <input type="hidden" name="sections" class="section_input">
                        <input type="hidden" name="format" value="pdf">
                        <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                        <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                        <button type="submit" class="btn btn-success btn-sm">Print</button>
                    </form>
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

            <div class="row mt-4">
                <div class="col-md-6 col-sm-12 col-lg-4 offset-sm-0 offset-md-2 offset-lg-3">
                    <div id="printableArea" class="card p-3">

                        @include('restaurant.Report.pdf.shift_summary', [
                            'restaurant' => $restaurant,
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                            'report_title' => 'Shift Summary Report',
                            'report' => $report,
                            'view' => 1
                        ])

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->

    <x-restaurant.email-report action="{{ route('report.email-shift-summary-report') }}">
        @section('additionalFields')
            <input type="hidden" name="start_date" value="{{ $start_date_utc ?? '' }}">
            <input type="hidden" name="end_date" value="{{ $end_date_utc ?? '' }}">
            <input type="hidden" name="sections" class="section_input">
        @endsection
    </x-restaurant.email-report>

@endsection


@section('js')
    <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/reports/date-time-header-filter.js') }}"></script>
@endsection