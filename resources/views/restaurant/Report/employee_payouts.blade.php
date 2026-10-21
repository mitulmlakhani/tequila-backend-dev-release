@extends('layouts.master')
@section('title', 'Employee Salary Report')
@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>

    <style>
        thead th {
            text-align: center;
        }
        tr td {
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Employee Salary Report</h4>
                {{-- <div class="d-flex">
                    <form method="GET" action="{{ route('report.export-sales-by-employee-report') }}" id="export-form">
                        <input type="hidden" name="start_date" id="export_start_date" value="{{ $start_date }}">
                        <input type="hidden" name="end_date" id="export_end_date" value="{{ $end_date }}">
                        <input type="hidden" name="employee" id="export_employee" value="{{ request('employee') }}">
                        <button type="submit" class="btn btn-success">Export</button>
                    </form>

                    <a class="btn btn-primary print_btn ms-2"
                        href="{{ route('report.print-sales-by-employee-report', ['start_date' => $start_date, 'end_date' => $end_date]) }}">Print</a>

                    <a class="btn btn-dark ms-2" data-bs-toggle="modal" data-bs-target="#scheduleReport">Schedule</a>
                </div> --}}
            </div>

            <div class="card p-3 shadow-sm">
                <form method="GET" action="{{ route('report.employee-salaries-report') }}"
                    class="row g-3 align-items-center" id="filter-form">
                    @include('restaurant.Report.partials.date-time-header', [
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                    ])
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
                            <th>Salary Period</th>
                            <th>Salary</th>
                            <th>Federal Tax</th>
                            <th>State Tax</th>
                            <th>Social Security</th>
                            <th>Medicare</th>
                            <th>Total Employee Tax</th>
                            <th>Net Salary</th>
                            <th>Futa</th>
                            <th>Suta</th>
                            <th>Total Employer Tax</th>
                            <th>Pay Date</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $totals = [
                                'salary' => 0,
                                'federal_income_tax' => 0,
                                'state_income_tax' => 0,
                                'social_security_employee' => 0,
                                'social_security_employer' => 0,
                                'medicare_employee' => 0,
                                'medicare_employer' => 0,
                                'net_salary' => 0,
                                'total_employee_tax' => 0,
                                'futa_tax' => 0,
                                'suta_tax' => 0,
                                'total_employer_tax' => 0,
                    ];
                        @endphp

                        @forelse ($report as $row)
                            <tr>

                                @php
                                    $totals['salary'] += $row->salary_amount;
                                    $totals['federal_income_tax'] += $row->federal_income_tax;
                                    $totals['state_income_tax'] += $row->state_income_tax;
                                    $totals['social_security_employee'] += $row->social_security_employee;
                                    $totals['social_security_employer'] += $row->social_security_employer;
                                    $totals['medicare_employer'] += $row->medicare_employer;
                                    $totals['medicare_employee'] += $row->medicare_employee;
                                    $totals['net_salary'] += $row->net_salary_amount;
                                    $totals['total_employee_tax'] += $row->total_employee_tax;
                                    $totals['futa_tax'] += $row->futa_tax;
                                    $totals['suta_tax'] += $row->suta_tax;
                                    $totals['total_employer_tax'] += $row->total_employer_tax;
                                @endphp

                                <td>{{ $row->user->name }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($row->salary_from_date)->format(config('app.display_date_format')) }}<br />
                                    {{ \Carbon\Carbon::parse($row->salary_to_date)->format(config('app.display_date_format')) }}</td>
                                <td class="text-end pe-3">{{ roundUp($row->salary_amount, 4) }}</td>
                                <td class="text-end pe-3">
                                    {{ roundUp($row->federal_income_tax, 4) }}
                                </td>
                                <td class="text-end pe-3">{{ roundUp($row->state_income_tax, 4) }}</td>
                                <td class="text-end pe-3">
                                    Employee: {{ roundUp($row->social_security_employee, 4) }} <br />
                                    Employer: {{ roundUp($row->social_security_employer, 4) }}
                                </td>
                                <td class="text-end pe-3">
                                    Employee: {{ roundUp($row->medicare_employee, 4) }} <br />
                                    Employer: {{ roundUp($row->medicare_employer, 4) }}
                                </td>
                                <td class="text-end pe-3 fw-bold">{{ roundUp($row->total_employee_tax, 4) }}</td>
                                <td class="text-end pe-3 fw-bold">{{ roundUp($row->net_salary_amount, 4) }}</td>
                                <td class="text-end pe-3">{{ roundUp($row->futa_tax, 4) }}</td>
                                <td class="text-end pe-3">{{ roundUp($row->suta_tax, 4) }}</td>
                                <td class="text-end pe-3 fw-bold">{{ roundUp($row->total_employer_tax, 4) }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->pay_date)->format(config('app.display_date_format')) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No Salaries found for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold bg-light">
                            <td>Total</td>
                            <td class="text-center">-</td>
                            <td class="text-end pe-3">{{ roundUp($totals['salary'], 4) }}</td>
                            <td class="text-end pe-3">{{ roundUp($totals['federal_income_tax'], 4) }}</td>
                            <td class="text-end pe-3">{{ roundUp($totals['state_income_tax'], 4) }}</td>
                            <td class="text-end pe-3">
                                Employee: {{ roundUp($totals['social_security_employee'], 4) }} <br />
                                Employer: {{ roundUp($totals['social_security_employer'], 4) }}
                            </td>
                            <td class="text-end pe-3">
                                Employee: {{ roundUp($totals['medicare_employee'], 4) }} <br />
                                Employer: {{ roundUp($totals['medicare_employer'], 4) }}
                            </td>
                            <td class="text-end pe-3">{{ roundUp($totals['total_employee_tax'], 4) }}</td>
                            <td class="text-end pe-3">{{ roundUp($totals['net_salary'], 4) }}</td>
                            <td class="text-end pe-3">{{ roundUp($totals['futa_tax'], 4) }}</td>
                            <td class="text-end pe-3">{{ roundUp($totals['suta_tax'], 4) }}</td>
                            <td class="text-end pe-3">{{ roundUp($totals['total_employer_tax'], 4) }}</td>
                            <td class="text-center">-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/reports/date-time-header-filter.js') }}"></script>
@endsection
