@extends('layouts.master')
@section('title', 'Sales by Category Report')

@section('css')
<link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
@endsection

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Sales by Category Report</h4>

            <div class="d-flex gap-2">
                <form method="GET" action="{{ route('export-sales-by-category-report') }}">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <input type="hidden" name="sort_by" value="{{ $sort_by }}">
                    <button type="submit" class="btn btn-success">Export</button>
                </form>

                <form method="GET" action="{{ route('export-sales-by-category-report') }}">
                    <input type="hidden" name="format" value="pdf">
                    <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                    <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                    <input type="hidden" name="sort_by" value="{{ $sort_by }}">
                    <button type="submit" class="btn btn-success">PDF</button>
                </form>

                <a target="_blank" class="btn btn-success" href="{{ route('export-sales-by-category-report') . '?format=receipt&start_date=' . $start_date_utc . '&end_date=' . $end_date_utc . '&sort_by=' . $sort_by }}">Print Receipt</a>

                <button type="button" class="btn btn-success print_btn" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>
            </div>
        </div>

        <div class="card p-3 shadow-sm">
            <form method="GET" action="{{ route('sales-by-category-report') }}" class="row g-3 align-items-center" id="filter-form">
                @include('restaurant.Report.partials.date-time-header', ['start_date' => $start_date, 'end_date' => $end_date])

                <div class="d-flex gap-2">
                    <div>
                        <input type="radio" class="btn-check sort_radio" name="sort_by" value="most_sold" id="most_sold" {{ $sort_by == "most_sold" ? 'checked' : '' }}>
                        <label class="btn btn-success" for="most_sold">Most sold</label>
                    </div>

                    <div>
                        <input type="radio" class="btn-check sort_radio" name="sort_by" value="least_sold" id="least_sold" {{ $sort_by == "least_sold" ? 'checked' : '' }}>
                        <label class="btn btn-success" for="least_sold">Least sold</label>
                    </div>

                    <div>
                        <input type="radio" class="btn-check sort_radio" name="sort_by" value="total_sales" id="total_sales" {{ $sort_by == "total_sales" ? 'checked' : '' }}>
                        <label class="btn btn-success" for="total_sales">Total Sales</label>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Category Name</th>
                        <th>Total Quantity</th>
                        <th>Total Discount</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $data)
                    <tr>
                        <td>{{ $data->category_name }}</td>
                        <td>{{ $data->total_quantity }}</td>
                        <td>{{ number_format($data->total_discount, 2) }}</td>
                        <td>{{ number_format($data->total_sales, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No sales found.</td>
                    </tr>
                    @endforelse
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

<x-restaurant.email-report action="{{ route('email-sales-by-category-report') }}">
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
    $(document).ready(function () {
        $(".sort_radio").on('change', function () {
            $(".date-time-filter-submit-btn").click();
        });
    });
</script>
@endsection
