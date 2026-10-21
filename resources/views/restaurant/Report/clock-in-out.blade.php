@extends('layouts.master')
@section('title', 'Attendance & Shift Report')
@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>

    <style>
        .fixed-table {
            height: 435px;
            overflow-y: auto;
        }

        .fixed-table .table thead th {
            position: sticky;
            top: 0;
        }
    </style>
@endsection
@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Attendance & Shift Report</h4>
            
            <div class="d-flex gap-2">
                <form method="GET" action="{{ route('export-clock-in-out-report') }}">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <input type="hidden" name="employee_id" value="{{ request('employee_id') }}">
                    <button type="submit" class="btn btn-success">Export</button>
                </form>
                
                <form method="GET" action="{{ route('pdf-clock-in-out-report') }}" target="_blank">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <input type="hidden" name="employee_id" value="{{ request('employee_id') }}">
                    <button type="submit" class="btn btn-success">Print</button>
                </form>

                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card p-3 shadow-sm">
            <form method="GET" class="row g-3 align-items-center" id="filter-form">

                @php ob_start(); @endphp

                <div class="col-md-3">
                    <label for="employee_id" class="form-label">Select Employee</label>
                    <select name="employee_id" class="form-select">
                        <option value="">All Employees</option>
                        @foreach($employees as $id => $name)
                            <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @php
                    $child = ob_get_clean();
                @endphp

                @include('restaurant.Report.partials.date-time-header', [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    // 'time_enable' => false,
                    // 'format' => 'm-d-Y',
                    'child' => $child,
                ])
            </form>
        </div>

        {{-- Employee Table --}}
        <div class="table-responsive mt-4 fixed-table">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th style="white-space: nowrap;">Employee</th>
                        <th>Total Hours</th>
                        <th>Total Break Hours</th>
                        <th>Total Meal Break Hours</th>
                        <th>Total Tip</th>
                        <th>SubTotal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $eTotals = ['work' => 0, 'break' => 0, 'mbreak' => 0, 'tip' => 0, 'amount' => 0, 'total' => 0]; @endphp
                    @forelse ($report['employees'] ?? [] as $employee)
                    <tr>
                        <td class="d-flex align-items-center justify-content-between px-2" style="white-space: nowrap;">
                            {{ ucwords($employee['name']) }}

                            @if($employee['shift_active'])
                                <div class="pending d-flex align-items-center justify-content-center px-2 mx-2">
                                    <img src="{{ asset('assets/images/pending.png') }}" class="me-2" alt="pending">
                                    <span>Active</span>
                                </div>
                            @endif
                        </td>
                        <td>{{ \Carbon\CarbonInterval::minutes($employee['work'])->cascade()->format('%H:%I') }}</td>
                        <td>{{ \Carbon\CarbonInterval::minutes($employee['break'])->cascade()->format('%H:%I') }}</td>
                        <td>{{ \Carbon\CarbonInterval::minutes($employee['mbreak'])->cascade()->format('%H:%I') }}</td>
                        <td>{{ $employee['total_tip'] ?? 0 }}</td>
                        <td>{{ $employee['total_amount'] ?? 0 }}</td>
                        <td>{{ ($employee['total_tip'] ?? 0) + ($employee['total_amount'] ?? 0) }}</td>
                    </tr>
                    @php
                        $eTotals['work'] += $employee['work'];
                        $eTotals['break'] += $employee['break'];
                        $eTotals['mbreak'] += $employee['mbreak'];
                        $eTotals['tip'] += $employee['total_tip'];
                        $eTotals['amount'] += $employee['total_amount'];
                        $eTotals['total'] += ($employee['total_tip'] + $employee['total_amount']);
                    @endphp
                    @endforeach
                    @if(count($report['employees'] ?? []))
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>{{ \Carbon\CarbonInterval::minutes($eTotals['work'] ?? 0)->cascade()->format('%H:%I') }}</strong>
                            </td>
                            <td><strong>{{ \Carbon\CarbonInterval::minutes($eTotals['break'] ?? 0)->cascade()->format('%H:%I') }}</strong>
                            </td>
                            <td><strong>{{ \Carbon\CarbonInterval::minutes($eTotals['mbreak'] ?? 0)->cascade()->format('%H:%I') }}</strong>
                            </td>
                            <td><strong>{{ $eTotals['tip'] ?? 0 }}</strong></td>
                            <td><strong>{{ $eTotals['amount'] ?? 0 }}</strong></td>
                            <td><strong>{{ $eTotals['total'] ?? 0 }}</strong></td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="7" class="text-center">No records found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Records Table -->
        <h3 class="mt-5">Total Records: {{ count($report['records']) }}</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Role</th>
                        <th>Clock In</th>
                        <th>Breaks</th>
                        <th>Meal Breaks</th>
                        <th>Clock Out</th>
                        <th>Hours</th>
                        <th>Rate</th>
                        <th>SubTotal</th>
                        <th>Tip</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $uRecords = collect($report['records'] ?? [])->groupBy('user_id')->all() @endphp

                    @forelse ($uRecords as $byUserRows)
                        @foreach ($byUserRows->all() as $kk => $data)
                            <tr>
                                <td>{{ $data['shift_date'] ?? 'N/A' }}</td>
                                <td>{{ ucwords($data['name'] ?? 'N/A') }}</td>
                                <td>{{ ucwords($data['role_name'] ?? 'N/A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($data['clockIn'])->format('h:i A') }}</td>
                                <td>{{ \Carbon\CarbonInterval::minutes(($data['break'] ?? 0))->cascade()->format('%H:%I') }}
                                </td>
                                <td>{{ \Carbon\CarbonInterval::minutes(($data['mbreak'] ?? 0))->cascade()->format('%H:%I') }}
                                </td>
                                <td class="{{ $data['active_shift'] ? 'px-1' : '' }}">
                                    @if($data['active_shift'])
                                        <div class="pending">
                                            <img src="{{ asset('assets/images/pending.png') }}" class="me-2" alt="pending">
                                            <span>Active</span>
                                        </div>
                                    @else
                                        {{ \Carbon\Carbon::parse($data['clockOut'])->format('h:i A') }}
                                    @endif
                                </td>
                                <td>{{ \Carbon\CarbonInterval::minutes($data['work'])->cascade()->format('%H:%I') }}</td>
                                <td>{{ $data['rate'] ?? '-' }}</td>
                                <td>{{ ($data['total_amount'] ?? 0) }}</td>
                                <td>{{ $data['total_tip'] ?? 0 }}</td>
                                <td>{{ ($data['total_tip'] ?? 0) + ($data['total_amount'] ?? 0) }}</td>
                                <td>
                                    <span class="me-2">
                                        <a aria-hidden="true" href="javascript:void(0)" class="shift-edit"
                                            data-from_id="{{ $data['clockInId'] }}"
                                            data-to_id="{{ $data['active_shift'] ? $data['lastRecordId'] : $data['clockOutId'] }}">
                                            <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    <!--Modal Popup Edit Shift start-->
    <div class="modal fade" id="user-shift-modal" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleLabel">Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('shifts.save') }}" method="POST" enctype='multipart/form-data'
                    id="user-shift-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row" id="shift_fields">
                            <div class="col-6 mb-5">
                                <label class="form-label" for="user">Select user </label>
                                <select class="form-select" id="user_field" name="user" required>
                                </select>
                            </div>
                            <div class="col-6 mb-5">
                                <label class="form-label" for="role">Select role </label>
                                <select class="form-select" id="role_field" name="role" required>
                                </select>
                            </div>
                            <div class="col-4 mb-5">
                                <label class="form-label" for="name">Payroll Amount</label>
                                <input oninput="validateInput(this)" type="text" placeholder="Payroll Amount"
                                    name="payroll_amount" id="payroll_amount" class="form-control" required value="">
                            </div>

                            <div class="col-4 mb-5">
                                <label class="form-label" for="name">OverTime Amount</label>
                                <input oninput="validateInput(this)" type="text" placeholder="OverTime Amount"
                                    name="overtime_amount" id="overtime_amount" class="form-control" value="">
                            </div>

                            <div class="col-4 mb-5">
                                <label class="form-label" for="name">OverTime Hours After</label>
                                <input oninput="validateInput(this)" type="text" placeholder="OverTime Hours After"
                                    name="overtime_hours_after" id="overtime_hours_after" class="form-control" value="">
                            </div>

                            <div class="col-6 mb-4" id="clockInContainer"></div>

                            <div class="col-6 mb-4" id="clockOutContainer"></div>

                            <div class="col-12">
                                <div class="row" id="breakContainer"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-restaurant.email-report action="{{ route('email-clock-in-out-report') }}">
        @section('additionalFields')
            <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
            <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
        @endsection
    </x-restaurant.email-report>
    <!--Modal Popup Edit Shift end-->

    @endsection
    @section('js')
        <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
        <script src="{{ asset('assets/js/reports/date-time-header-filter.js') }}"></script>

        <script>
            var getShiftUrl = "{{ route('shifts.get') }}";

            var logTitle = {
                'clock_in': 'Clock In',
                'clock_out': 'Clock Out',
                'start_break': 'Break Start',
                'end_break': 'Break End',
                'start_meal_break': 'Meal Break Start',
                'end_meal_break': 'Meal Break End',
            };

            $(document).on('click', '.shift-edit', function (e) {
                let fromId = $(this).data('from_id');
                let toId = $(this).data('to_id');
                let shiftForm = $('#user-shift-form');

                $.ajax({
                    url: getShiftUrl,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        from_id: fromId,
                        to_id: toId
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            let shiftData = response.data;
                            let shiftFields = $('#shift_fields');

                            $("#payroll_amount").val(shiftData.payroll_amount);
                            $("#overtime_amount").val(shiftData.overtime_amount);
                            $("#overtime_hours_after").val(shiftData.overtime_hours_after);

                            $('#role_field').empty(); // Clear existing options
                            (shiftData?.roles || []).map(function (role) {
                                $('#role_field').append(
                                    $('<option>', {
                                        value: role.id,
                                        text: role.name,
                                        selected: role.id == shiftData.role_id
                                    })
                                );
                            });

                            $('#user_field').empty(); // Clear existing options
                            (shiftData?.users || []).map(function (user) {
                                $('#user_field').append(
                                    $('<option>', {
                                        value: user.id,
                                        text: user.name,
                                        selected: user.id == shiftData.user_id
                                    })
                                );
                            });

                            $("#breakContainer").html('');
                            (shiftData?.logs || []).map(function (log) {
                                var displayTime = log.local_timestamp || log.timestamp;
                                if (log.action == "clock_in") {
                                    $("#clockInContainer").html(`
                                        <label  class="form-label" for="name">${logTitle[log.action]}</label>
                                        <input type="text" name="logs[${log.id}]" class="form-control mb-4 datetimepicker"
                                                required value="${displayTime}">
                                    `);
                                } else if (log.action == "clock_out") {
                                    $("#clockOutContainer").html(`
                                        <label  class="form-label" for="name">${logTitle[log.action]}</label>
                                        <input type="text" name="logs[${log.id}]" class="form-control mb-4 datetimepicker"
                                                required value="${displayTime}">
                                    `);
                                } else {
                                    $("#breakContainer").append(`
                                        <div class="col-6 mb-4">
                                            <label class="form-label" for="name">${logTitle[log.action]}</label>
                                            <input type="text" name="logs[${log.id}]" class="form-control datetimepicker"
                                                    required value="${displayTime}">
                                        </div>
                                    `);
                                }
                            });

                            flatpickr(".datetimepicker", {
                                enableTime: true,
                                // dateFormat: "m-d-Y H:i",
                                dateFormat: "Y-m-d H:i:s",
                                allowInput: true
                            });

                            $('#user-shift-modal').modal('show');
                        }
                    }
                });
            });


            function validateInput(input) {
                // Only allow digits and a maximum of one decimal point
                var value = input.value;
                // Regular expression for a valid decimal number with up to two decimal places
                var valid = /^\d*\.?\d{0,2}$/;
                if (!valid.test(value)) {
                    input.value = value.slice(0, -1); // Remove last invalid character
                }
            }
        </script>
    @endsection