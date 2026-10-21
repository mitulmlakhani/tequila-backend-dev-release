@extends('layouts.master')
@section('title', 'Customer Purchase History')

@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection

@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">



            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Customer Purchase History</h4>

                <div class="d-flex align-items-center justify-content-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#emailReport">Email</button>

                    <a target="_blank"
                        href="{{ route('report.export-customer-purchase-history') }}?file_format=pdf&start_date={{ $filters['start_date'] }}&end_date={{ $filters['end_date'] }}"
                        class="downloadBtn btn btn-dark m-1">PDF Download</a>

                    <a target="_blank"
                        href="{{ route('report.export-customer-purchase-history') }}?file_format=excel&start_date={{ $filters['start_date'] }}&end_date={{ $filters['end_date'] }}"
                        class="downloadBtn btn btn-dark m-1">Excel Download</a>
                </div>
            </div>

            <div class="card p-3 shadow-sm">
                <form method="GET" action="{{ route('report.customer-purchase-history') }}"
                    class="row g-3 align-items-center" id="filter-form">
                    @include('restaurant.Report.partials.date-time-header', [
                        'start_date' => $filters['start_date'],
                        'end_date' => $filters['end_date'],
                    ])
                </form>
            </div>



            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped w-100 text-nowrap">
                    <thead class="table-dark">
                        <tr>
                            <th>Customer Name</th>
                            <th>Total Items</th>
                            <th>Total Orders</th>
                            <th>Total Spent</th>
                            <th>First Order</th>
                            <th>Last Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report as $row)
                            <tr>
                                <td>{{ $row->customer_name }}</td>
                                <td>{{ $row->total_items }}</td>
                                <td>{{ $row->total_orders }}</td>
                                <td>{{ number_format($row->total_spent, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->first_order_date)->format('d-m-Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->last_order_date)->format('d-m-Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No data found for selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pagination)
                <div class="d-flex justify-content-center mt-3">
                    {{ $pagination->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    <x-restaurant.email-report action="{{ route('report.export-customer-purchase-history') }}">
        @section('additionalFields')
            <input type="hidden" name="type" value="email">
            <input type="hidden" name="start_date" value="{{ $filters['start_date'] }}">
            <input type="hidden" name="end_date" value="{{ $filters['end_date'] }}">
        @endsection
    </x-restaurant.email-report>
@endsection

@section('js')
    <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/reports/date-time-header-filter.js') }}"></script>
@endsection