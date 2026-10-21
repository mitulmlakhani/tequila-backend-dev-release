@extends('layouts.master')
@section('title', 'Batch Report')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}">
@endsection

@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Batch Report Summary</h4>

                @php
                    if ($type == "day") {
                        $nextDate = $date->copy()->addDay()->format('Y-m-d');
                        $prevDate = $date->copy()->subDay()->format('Y-m-d');
                    } else if ($type == "month") {
                        $nextDate = $date->copy()->addMonth()->format('Y-m-d');
                        $prevDate = $date->copy()->subMonth()->format('Y-m-d');
                        
                    } else if ($type == "year") {
                        $nextDate = $date->copy()->addYear()->format('Y-m-d');
                        $prevDate = $date->copy()->subYear()->format('Y-m-d');
                    }
                    
                    $upVals = [];

                    if($type == "day") {
                        if($serial) {
                            $upVals['url'] = route('reports.batch.summary')."?type=day&date=".$date->format('Y-m-d');
                            $upVals['title'] = 'Back to Day';
                        } else {
                            $upVals['url'] = route('reports.batch.summary')."?type=month&date=".$date->format('Y-m-d');
                            $upVals['title'] = 'Back to Month';
                        }
                    } else if($type == "month") {
                        $upVals['url'] = route('reports.batch.summary')."?date=".$date->format('Y-m-d');
                        $upVals['title'] = 'Back to Year';
                    }
                @endphp

                
                @if(!empty($upVals))
                    <a class="btn btn-primary d-flex align-items-center justify-content-center" href="{{ $upVals['url'] }}">
                        <img src="{{ asset('assets/images/up-arrow.png') }}" alt="up" class="img-fluid me-2">
                        {{ $upVals['title'] }}
                    </a>
                @endif

                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('reports.batch.summary')."?type=".$type."&date=".$prevDate."&terminal=".$serial }}" style="min-width: 50px;" class="btn btn-primary prev_date"><</a>
                    <input type="text" class="datepicker form-control max30Length mx-2" id="date" name="date" value="{{ $fdate }}" />
                    <a href="{{ route('reports.batch.summary')."?type=".$type."&date=".$nextDate."&terminal=".$serial }}" style="min-width: 50px;" class="btn btn-primary next_date">></a>
                </div>

                <div>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>

                    <a target="_blank"
                        href="{{ route('reports.batch.summary.share') }}?type={{ $type }}&terminal={{ $serial }}&file_format=pdf&date={{ $date->format('Y-m-d') }}"
                        class="downloadBtn btn btn-dark m-1">PDF Download</a>

                    <a target="_blank"
                        href="{{ route('reports.batch.summary.share') }}?type={{ $type }}&terminal={{ $serial }}&file_format=excel&date={{ $date->format('Y-m-d') }}"
                        class="downloadBtn btn btn-dark m-1">Excel Download</a>
                </div>
            </div>

            <!-- Report Table -->
            <div class="table-responsive mt-4">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            @if($type == "day" && !$serial)
                                <th>End Time</th>
                                <th>Terminal</th>
                            @endif
                            @if($serial)
                                <th>Order Id</th>
                                <th>Terminal</th>
                                <th>Card Number</th>
                            @else
                            <th>Transactions</th>
                            @endif
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($type == "day" && $serial)
                            @foreach ($report as $i => $row)
                                <tr>
                                    <td>{{ $row['payment_date'] ?? '' }}</td>
                                    <td>{{ $row['order_id'] ?? '' }}</td>
                                    <td>{{ $row['terminal_sn'] ?? '' }}</td>
                                    <td>{{ $row['card_number'] ?? '' }}</td>
                                    <td>{{ $row['total_gross'] ?? ''  }}</td>
                                </tr>
                            @endforeach
                        @elseif($type == "day")
                            @foreach ($report as $i => $row)
                                <tr>
                                    <td>{{ $row['payment_date'] ?? '' }}</td>
                                    <td>{{ $row['end_date'] ?? '' }}</td>
                                    <td>
                                        @if($row['terminal_sn'] ?? null)
                                            <a href="{{ route('reports.batch.summary') }}?type=day&date={{ request('date') }}&terminal={{ $row['terminal_sn'] ?? '' }}">{{ $row['terminal_sn'] ?? '' }}</a></td>
                                        {{-- {{ $row['terminal_sn'] ?? '' }} --}}
                                        @else
                                        {{ $row['terminal_sn'] ?? '' }}                                    
                                        @endif
                                    </td>
                                    <td>{{ $row['total_transactions'] ?? '' }}</td>
                                    <td>{{ $row['total_gross'] ?? ''  }}</td>
                                </tr>
                            @endforeach
                        @elseif ($type == "year")
                            @for($i = 1; $i <= 12; $i++)
                                @php
                                    $monthF = \Carbon\Carbon::createFromDate($date->year, $i, 1);
                                    $month = $monthF->format('m') . '/' . $date->year;
                                    $row = $report[$month] ?? ['payment_date' => $month];
                                @endphp

                                <tr>
                                    <td>
                                        @if($row['total_transactions'] ?? 0)
                                            <a href="{{ route('reports.batch.summary') }}?type=month&date={{ $monthF->format('Y-m-d') }}">{{ $row['payment_date'] ?? '' }}</a></td>
                                        @else
                                            {{ $row['payment_date'] ?? '' }}
                                        @endif
                                    <td>{{ $row['total_transactions'] ?? '' }}</td>
                                    <td>{{ $row['total_gross'] ?? ''  }}</td>
                                </tr>

                            @endfor
                        @elseif ($type == "month")
                            @for($i = 1; $i <= $date->endOfMonth()->day; $i++)
                                @php
                                    $day = \Carbon\Carbon::createFromDate($date->year, $date->month, $i)->format('Y-m-d');
                                    $row = $report[$day] ?? ['payment_date' => $day];
                                @endphp

                                <tr>
                                    <td>
                                        @if($row['total_transactions'] ?? 0)
                                            <a href="{{ route('reports.batch.summary') }}?type=day&date={{ $row['payment_date'] }}">{{ $row['payment_date'] ?? '' }}</a></td>
                                        @else
                                            {{ $row['payment_date'] ?? '' }}
                                        @endif
                                    </td>
                                    <td>{{ $row['total_transactions'] ?? '' }}</td>
                                    <td>{{ $row['total_gross'] ?? ''  }}</td>
                                </tr>
                            @endfor
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-restaurant.email-report action="{{ route('reports.batch.summary.share') }}">
        @section('additionalFields')
            <input type="hidden" name="type" value="email">
            <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">
        @endsection
    </x-restaurant.email-report>

@endsection

@php
    $url = route('reports.batch.summary')."?type=".$type."&date=#DATE#&terminal=".$serial;
@endphp

@section('js')
    <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
    <script>
        var url = "{{ $url }}";

        flatpickr(".datepicker", { dateFormat: "Y-m-d" });

        function redirectToDate(date) {
            url = url.replace("#DATE#", date);
            url = url.replace(/&amp;/g, "&");

            window.location.href = url;
        }

        function addSubtractDate(baseDate, years = 0, months = 0, days = 0) {
            var date = new Date(baseDate);

            date.setFullYear(date.getFullYear() + years);
            date.setMonth(date.getMonth() + months);
            date.setDate(date.getDate() + days);

            return date;
        }

        $(".datepicker").on("change", function (e) {
            redirectToDate($(this).val());
        });
    </script>
@endsection