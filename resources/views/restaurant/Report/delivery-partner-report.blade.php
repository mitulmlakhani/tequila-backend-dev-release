@extends('layouts.master')
@section('title')
    Delivery Partners Report
@endsection
@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Delivery Partners Report</h4>
                <div class="d-flex gap-1">
                    <form method="GET" action="{{ route('report.export-delivery-partner-report') }}">
                        <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                        <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                        <input type="hidden" name="file_format" value="excel">
                        <button type="submit" class="btn btn-success btn-sm">Excel</button>
                    </form>

                    <form method="GET" action="{{ route('report.export-delivery-partner-report') }}">
                        <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                        <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                        <input type="hidden" name="file_format" value="pdf">
                        <button type="submit" class="btn btn-success btn-sm">Pdf</button>
                    </form>

                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>
                </div>
            </div>

            <div class="card p-3 shadow-sm">

                <form method="GET" action="{{ route('report.delivery-partner-report') }}" class="row g-3 align-items-center"
                    id="filter-form">
                    @include('restaurant.Report.partials.date-time-header', [
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                    ])

                    <div class="col-md-3">
                        <label for="delivery_partner" class="form-label">Delivery Partner</label>
                        <select name="delivery_partner" class="form-select">
                            <option value="">All</option>
                            <option value="ubereats" {{ request('delivery_partner') == 'ubereats' ? 'selected' : '' }}>
                                UberEats</option>
                            <option value="doordash" {{ request('delivery_partner') == 'doordash' ? 'selected' : '' }}>
                                DoorDash</option>
                            <option value="grubhub" {{ request('delivery_partner') == 'grubhub' ? 'selected' : '' }}>GrubHub
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="order_status" class="form-label">Status</label>
                        <select name="order_status" class="form-select">
                            <option value="">All</option>
                            <option value="completed" {{ request('order_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>


                </form>
            </div>

            @include('layouts.flash-msg')
            <div class="table-responsive mt-4 fixed-table">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Order At</th>
                            <th>Delivery Partner</th>
                            <th>Delivery Partner Order ID</th>
                            <th>OrderId</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report as $row)
                            <tr>
                                <td>{{ $row->order_date }}</td>
                                <td>{{ ucfirst($row->delivery_partner) }}</td>
                                <td>{{ $row->delivery_partner_order_id }}</td>
                                <td>{{ $row->order_id }}</td>
                                <td>${{ number_format($row->total_amount, 2) }}</td>
                                <td>{{ ucfirst(\App\Models\Order::orderStatusNameById($row->order_status)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="border-top: 3px solid #000;">
                        <tr>
                            <th colspan="2">Total</th>
                            <th>{{ $aggregation['total_orders'] }}</th>
                            <th>{{ number_format($aggregation['total_amount'], 2) }}</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="2">Completed Total</th>
                            <th>{{ $aggregation['completed_orders'] }}</th>
                            <th>{{ number_format($aggregation['completed_amount'], 2) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- Pagination Links -->
            @if ($pagination)
                <div class="d-flex justify-content-center mt-3">
                    {{ $pagination->links('pagination::bootstrap-4') }}
                </div>
            @endif

        </div>
    </div>
    <!--Main Section End-->

    <x-restaurant.email-report action="{{ route('report.export-delivery-partner-report') }}">
        @section('additionalFields')
            <input type="hidden" name="type" value="email">
            <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
            <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
        @endsection
    </x-restaurant.email-report>
@endsection
@section('js')
    <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/reports/date-time-header-filter.js') }}"></script>
@endsection
