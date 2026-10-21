@extends('layouts.master')
@section('title', 'Cash In/Out Report')
@section('css')
<link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection
@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Cash In/Out Report</h4>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#emailReport">Email</button>
                
                <form method="GET" action="{{ route('export-cash-in-out-report') }}" target="_blank">
                    <input type="hidden" name="start_date" value="{{ request('start_date', date('m-d-Y')) }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date', date('m-d-Y')) }}">
                    <input type="hidden" name="cashier_id" value="{{ request('cashier_id') }}">
                    <button type="submit" class="btn btn-dark">Excel</button>
                </form>
                <form method="GET" action="{{ route('export-cash-in-out-report') }}" target="_blank">
                    <input type="hidden" name="start_date" value="{{ request('start_date', date('m-d-Y')) }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date', date('m-d-Y')) }}">
                    <input type="hidden" name="cashier_id" value="{{ request('cashier_id') }}">
                    <input type="hidden" name="file_format" value="pdf">
                    <button type="submit" class="btn btn-dark">PDF</button>
                </form>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card p-3 shadow-sm">
            <form method="GET" action="{{ route('cash-in-out-report') }}" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="text" id="start_date" name="start_date" class="form-control datepicker" value="{{ request('start_date', date('m-d-Y')) }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="text" id="end_date" name="end_date" class="form-control datepicker" value="{{ request('end_date', date('m-d-Y')) }}">
                </div>
                <div class="col-md-4">
                    <label for="cashier_id" class="form-label">Select Cashier</label>
                    <select name="cashier_id" class="form-select">
                        <option value="">All Cashiers</option>
                        @foreach($cashiers as $id => $name)
                            <option value="{{ $id }}" {{ request('cashier_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>

        <!-- Report Table -->
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Cashier</th>
                        <th>Date</th>
                        <th>Transaction Type</th>
                        <th>Amount</th>
                        <th>Comment</th>
                        <th>Cash Out To</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report as $data)
                        <tr>
                            <td>{{ $data['cashier_name'] }}</td>
                            <td>{{ $data['date'] }}</td>
                            <td>{{ $data['transaction_type'] == 1 ? 'Cash In' : 'Cash Out' }}</td>
                            <td>{{ $data['amount'] }}</td>
                            <td>{{ $data['comment'] }}</td>
                            <td>{{ $data['cash_out_to'] }}</td>
                        </tr>
                    @endforeach
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

<x-restaurant.email-report action="{{ route('export-cash-in-out-report') }}">
    @section('additionalFields')
        <input type="hidden" name="type" value="email">
        <input type="hidden" name="start_date" value="{{ request('start_date', date('m-d-Y')) }}">
        <input type="hidden" name="end_date" value="{{ request('end_date', date('m-d-Y')) }}">
        <input type="hidden" name="cashier_id" value="{{ request('cashier_id') }}">
    @endsection
</x-restaurant.email-report>
@endsection
@section('js')
<script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
<script>
    flatpickr(".datepicker", { dateFormat: "m-d-Y" });
</script>
@endsection
