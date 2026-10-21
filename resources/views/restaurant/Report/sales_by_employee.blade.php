@extends('layouts.master')
@section('title', 'Sales by Employee Report')
@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection

@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Sales by Employee Report</h4>
                <div class="d-flex">
                    <form method="GET" action="{{ route('report.export-sales-by-employee-report') }}" id="export-form">
                        <input type="hidden" name="start_date" id="export_start_date" value="{{ $start_date }}">
                        <input type="hidden" name="end_date" id="export_end_date" value="{{ $end_date }}">
                        <input type="hidden" name="employee" id="export_employee" value="{{ request('employee') }}">
                        <button type="submit" class="btn btn-success">Export</button>
                    </form>

                    <a class="btn btn-primary print_btn ms-2" href="{{ route('report.print-sales-by-employee-report', ['start_date' => $start_date, 'end_date' => $end_date]) }}">Print</a>

                    <a class="btn btn-dark ms-2" data-bs-toggle="modal" data-bs-target="#scheduleReport">Schedule</a>
                </div>
            </div>

            <div class="card p-3 shadow-sm">
                <form method="GET" action="{{ route('report.sales-by-employee-report') }}"
                    class="row g-3 align-items-center" id="filter-form">
                    @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])
                    <div class="col-md-3">
                        <label class="form-label">Search Employee</label>
                        <input type="text" class="form-control" name="employee" value="{{ request('employee') }}"
                            placeholder="Enter employee name">
                    </div>
                </form>
            </div>

            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee Name</th>
                            <th>Cash Sale</th>
                            <th>Credit Sale</th>
                            <th>Crypto Sale</th>
                            <th>Other Sale</th>
                            <th>Total Sale</th>
                            <th>Tip</th>
                            <th>Refund Amount</th>
                            <th>Total Refund Txs</th>
                            <th>Total Paid</th>
                            <th>Gross Paid</th>
                            <th>Owned Cash</th>
                            <th>Detailed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totals = [
                                'cash_sale' => 0,
                                'credit_sale' => 0,
                                'crypto_sale' => 0,
                                'other_sale' => 0,
                                'total_sale' => 0,
                                'tip_amount' => 0,
                                'total_paid' => 0,
                                'gross_paid' => 0,
                                'refund_amount' => 0,
                                'cash_owned' => 0,
                                'refund_count' => 0,
                            ];
                        @endphp
                        @forelse ($report as $row)
                            @php
                                $totals['cash_sale'] += $row->cash_sale;
                                $totals['credit_sale'] += $row->credit_sale;
                                $totals['crypto_sale'] += $row->crypto_sale;
                                $totals['other_sale'] += $row->other_sale;
                                $totals['total_sale'] += $row->total_sale;
                                $totals['tip_amount'] += $row->tip_amount;
                                $totals['total_paid'] += $row->total_paid;
                                $totals['gross_paid'] += $row->gross_paid;
                                $totals['refund_amount'] += $row->refund_amount ?? 0;
                                $totals['refund_count'] += $row->refund_count ?? 0;

                                $cashOwned = $row->cash_sale - $row->tip_amount;
                                $totals['cash_owned'] += $cashOwned;
                            @endphp
                            <tr>
                                <td>{{ $row->employee }}</td>
                                <td class="text-center">
                                    {{ currencyFormat($row->cash_sale) }}
                                    @if($row->cash_refund > 0)
                                        <br />
                                        <span class="text-danger">Refund: +{{ currencyFormat($row->cash_refund) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ currencyFormat($row->credit_sale) }}
                                    @if($row->credit_refund > 0)
                                        <br />
                                        <span class="text-danger">Refund: +{{ currencyFormat($row->credit_refund) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ currencyFormat($row->crypto_sale) }}
                                    @if($row->crypto_refund > 0)
                                        <br />
                                        <span class="text-danger">Refund: +{{ currencyFormat($row->crypto_refund) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ currencyFormat($row->other_sale) }}
                                    @if($row->other_refund > 0)
                                        <br />
                                        <span class="text-danger">Refund: +{{ currencyFormat($row->other_refund) }}</span>
                                    @endif
                                </td>
                                <td>{{ currencyFormat($row->total_sale) }}</td>
                                <td>{{ currencyFormat($row->tip_amount) }}</td>
                                <td><span class="text-danger">{{ currencyFormat($row->refund_amount ?? 0) }}</span></td>
                                <td>{{ $row->refund_count }}</td>
                                <td>{{ currencyFormat($row->total_paid) }}</td>
                                <td>{{ currencyFormat($row->gross_paid) }}</td>
                                <td>{{ currencyFormat($cashOwned) }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a target="_blank"
                                            href="{{ route('report.sales-by-employee-report-summary') }}?employee={{ $row->employee_id }}&start_date={{ $start_date }}&end_date={{ $end_date }}"
                                            class="btn btn-dark m-1">View</a>

                                        <a target="_blank" class="btn btn-primary m-1 print_btn" 
                                            href="{{ route('report.sales-by-employee-report-summary', ['employee' => $row->employee_id, 'start_date' => $start_date, 'end_date' => $end_date, 'print' => 1]) }}">Print</a>

                                        <a data-employee="{{ $row->employee_id }}" data-start_date=" {{ $start_date }}"
                                            data-end_date="{{ $end_date }}" class="btn btn-dark m-1 mailReport">Share</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No sales found for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold bg-light">
                            <td>Total</td>
                            <td class="text-center">{{ currencyFormat($totals['cash_sale']) }}</td>
                            <td class="text-center">{{ currencyFormat($totals['credit_sale']) }}</td>
                            <td class="text-center">{{ currencyFormat($totals['crypto_sale']) }}</td>
                            <td class="text-center">{{ currencyFormat($totals['other_sale']) }}</td>
                            <td>{{ currencyFormat($totals['total_sale']) }}</td>
                            <td>{{ currencyFormat($totals['tip_amount']) }}</td>
                            <td><span class="text-danger">{{ currencyFormat($totals['refund_amount']) }}</span></td>
                            <td>{{ $totals['refund_count'] ?? 0 }}</td>
                            <td>{{ currencyFormat($totals['total_paid']) }}</td>
                            <td>{{ currencyFormat($totals['gross_paid']) }}</td>
                            <td>{{ currencyFormat($totals['cash_owned']) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if ($pagination)
                <div class="d-flex justify-content-center mt-3">
                    {{ $pagination->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>

        <!-- Modals -->
        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="emailReport" tabindex="-1"
            role="dialog" aria-labelledby="emailReportLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="emailReportLabel">Share Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="d-flex mb-4">
                            <a target="_blank"
                                href="{{ route('report.sales-by-employee-report-summary') }}?download=1&format=pdf&employee=EMPLOYEE_ID&start_date={{ $start_date }}&end_date={{ $end_date }}"
                                class="downloadBtn btn btn-dark m-1">PDF Download</a>
                            <a target="_blank"
                                href="{{ route('report.sales-by-employee-report-summary') }}?download=1&format=excel&employee=EMPLOYEE_ID&start_date={{ $start_date }}&end_date={{ $end_date }}"
                                class="downloadBtn btn btn-dark m-1">Excel Download</a>
                        </div>

                        <form id="reportMailForm" action="{{ route('report.sales-by-employee-report-summary') }}"
                            method="get">
                            <span id="reportMailFormMsg" class="mb-3"></span>
                            <input type="hidden" name="sendMail" value="1">
                            <input type="hidden" name="start_date" value="">
                            <input type="hidden" name="end_date" value="">
                            <input type="hidden" name="employee" value="">
                            <div class="mb-4">
                                <label for="email" class="col-form-label">Email</label>
                                <input name="email" type="email" required class="form-control" id="email"
                                    placeholder="Enter Email Address">
                            </div>
                            <div class="mb-4">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="format" id="pdf_radio" checked
                                        value="pdf">
                                    <label class="form-check-label" for="pdf_radio">PDF</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="format" id="excel_radio"
                                        value="excel">
                                    <label class="form-check-label" for="excel_radio">Excel</label>
                                </div>
                            </div>

                            <button id="reportMailFormBtn" class="btn btn-primary mt-1" type="submit">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="scheduleReport" tabindex="-1"
            role="dialog" aria-labelledby="scheduleReportLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scheduleReportLabel">Report Scheduler</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="reportScheduleForm" action="{{ route('report.schedule.save') }}" method="post">
                            @csrf
                            <span id="reportScheduleFormMsg" class="mb-3"></span>
                            <input type="hidden" name="report" value="employee_sales" />

                            <div class="mb-4">
                                <label for="format" class="col-form-label">Schedule:</label>
                                <br />
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="schedule[]" id="daily_checkbox"
                                        value="daily" {{ in_array("daily", $reportScheduleData['schedules']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="daily_checkbox">DAILY</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="schedule[]" id="weekly_checkbox"
                                        value="weekly" {{ in_array("weekly", $reportScheduleData['schedules']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="weekly_checkbox">WEEKLY</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="schedule[]" id="monthly_checkbox"
                                        value="monthly" {{ in_array("monthly", $reportScheduleData['schedules']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="monthly_checkbox">MONTHLY</label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="col-form-label">Email</label>
                                <input name="emails" type="text" required class="form-control" id="email"
                                    placeholder="john@gmail.com, doe@gmail.com" value="{{ implode(",", $reportScheduleData['emails']) }}">
                            </div>

                            <div class="mb-4">
                                <label for="format" class="col-form-label">Report Format:</label>
                                <br />
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="file_format" id="pdf_checkbox"
                                        value="pdf" {{ in_array("pdf", $reportScheduleData['formats']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pdf_checkbox">PDF</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="file_format" id="excel_checkbox"
                                        value="excel" {{ in_array("excel", $reportScheduleData['formats']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="excel_checkbox">Excel</label>
                                </div>
                            </div>

                            <button id="reportScheduleFormBtn" class="btn btn-primary mt-1" type="submit">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/reports/date-time-header-filter.js') }}"></script>
    <script>
        function updateExportFields() {
            document.getElementById("export_start_date").value = document.getElementById("start_date").value;
            document.getElementById("export_end_date").value = document.getElementById("end_date").value;

            const employeeInput = document.getElementById("employee");
            if (employeeInput) {
                document.getElementById("export_employee").value = employeeInput.value;
            }
        }
    </script>
    <script>
        $(".mailReport").on('click', function (e) {
            var employeeId = $(this).data('employee');
            $("#reportMailForm input[name='start_date']").val($(this).data('start_date'));
            $("#reportMailForm input[name='end_date']").val($(this).data('end_date'));
            $("#reportMailForm input[name='employee']").val(employeeId);

            $('.downloadBtn').each(function (e) {
                var link = $(this).attr('href');
                link = link.replace('EMPLOYEE_ID', employeeId);
                $(this).attr('href', link)
            });

            $("#emailReport").modal("show");
        });

        $("#reportMailForm").on("submit", function (e) {
            e.preventDefault();

            $("#reportMailFormBtn").attr('disabled', true);

            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: form.attr('method'),
                url: actionUrl,
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    $("#reportMailFormMsg").text(response.message).addClass(response.success ? 'text-success' : 'text-danger');
                    $("#reportMailFormBtn").attr('disabled', false);

                    if (response.success) {
                        setTimeout(() => {
                            form[0].reset();

                            $("#emailReport").modal("hide");
                            $("#reportMailFormMsg").text('');
                        }, 2000);
                    }
                },
                error: function (xhr) {
                    $("#reportMailFormBtn").attr('disabled', false);
                }
            });
        });

        $("#reportScheduleForm").on("submit", function (e) {
            e.preventDefault();
            
            $("#reportScheduleFormBtn").attr('disabled', true);

            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: form.attr('method'),
                url: actionUrl,
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    $("#reportScheduleFormMsg").text(response.message).addClass(response.success ? 'text-success' : 'text-danger');
                    $("#reportScheduleFormBtn").attr('disabled', false);

                    if (response.success) {
                        window.location.reload();
                    }
                },
                error: function (xhr) {
                    $("#reportScheduleFormBtn").attr('disabled', false);
                }
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