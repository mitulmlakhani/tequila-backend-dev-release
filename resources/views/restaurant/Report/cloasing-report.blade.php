@extends('layouts.master')
@section('title')
    Closing Report
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
                        <h4>Closing Report</h4>
                        @can('table-create')
                            {{-- <a href="{{ route('table-create') }}" id="table-add" data-bs-toggle="modal"
                                data-bs-target="#table-add-modal">Add Order</a> --}}
                        @endcan
                    </div>
                </div>
                @include('layouts.flash-msg')
                @include('layouts.validation-error-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">


                        <!-- Filter Section -->
                        <form method="GET" action="{{ route('cloasing-report') }}"
                            class="d-flex justify-content-center align-items-end mb-4">
                            <div class="">
                                <label for="date" class="form-label">Select Date</label>
                                <input type="text" id="date" name="date" class="form-control datetimepicker"
                                    value="{{ request('start_date', $date) }}">
                            </div>
                            <div class="ms-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>

                        <div class="d-flex align-items-center justify-content-end">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#emailReport">Email</button>

                            <a target="_blank"
                                href="{{ route('cloasing-report-export') }}?file_format=pdf&date={{ $date }}"
                                class="downloadBtn btn btn-dark m-1">PDF Download</a>

                            <a target="_blank"
                                href="{{ route('cloasing-report-export') }}?file_format=excel&date={{ $date }}"
                                class="downloadBtn btn btn-dark m-1">Excel Download</a>
                        </div>
                    </div>


                    <div class="table-responsive mt-5">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">Time</th>
                                    <th scope="rowgroup">#Ticket</th>
                                    <th scope="rowgroup">User</th>
                                    <th scope="rowgroup">Payment Method</th>
                                    <th scope="rowgroup">Tax</th>
                                    <th scope="rowgroup">Service Charge</th>
                                    <th scope="rowgroup">Sub Total</th>
                                    <th scope="rowgroup">Tip</th>
                                    <th scope="rowgroup">Discount</th>
                                    <th scope="rowgroup">Amount Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $payment)
                                    <tr>
                                        <td>{{ $payment['time'] }}</td>
                                        <td><a target="_blank"
                                                href="{{ route('order', [$payment['ticket']]) }}">#{{ $payment['ticket'] }}</a>
                                        </td>
                                        <td>{{ $payment['cashier'] }}</td>
                                        <td>
                                            @php
                                                $formatedPaymentDetail = $payment['payment_method'];
                                            @endphp
                                            <div class="d-flex">
                                                @if ($formatedPaymentDetail['type'] == 'card')
                                                    <span
                                                        class="badge bg-dark text-capitalize">{{ $formatedPaymentDetail['type'] ?: 'Card' }}</span>
                                                @elseif($formatedPaymentDetail['type'] == 'cash')
                                                    <span
                                                        class="badge bg-primary text-capitalize">{{ $formatedPaymentDetail['type'] ?: 'Cash' }}</span>
                                                @elseif($formatedPaymentDetail['type'] == 'voucher')
                                                    <span
                                                        class="badge bg-success text-capitalize">{{ $formatedPaymentDetail['type'] ?: 'Voucher' }}</span>
                                                @elseif($formatedPaymentDetail['type'] == 'gift card')
                                                    <span
                                                        class="badge bg-warning text-capitalize">{{ $formatedPaymentDetail['type'] ?: 'Gift Card' }}</span>
                                                @endif

                                                <strong class="ms-2">{{ $formatedPaymentDetail['number'] }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ currencyFormat($payment['tax']) }}</td>
                                        <td>
                                            {{ currencyFormat($payment['service_charge']) }}
                                            <span
                                                class="ms-2 badge bg-dark text-capitalize">{{ $payment['waiter'] }}</span>
                                        </td>
                                        <td>{{ currencyFormat($payment['sub_total']) }}</td>
                                        <td>{{ currencyFormat($payment['tip']) }}</td>
                                        <td>{{ currencyFormat($payment['discount']) }}</td>
                                        <td>{{ currencyFormat($payment['amount_paid']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="border-top: 2px solid black;">
                                <tr style="font-size: 20px;">
                                    <th>Total</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>{{ currencyFormat($totalTax) }}</th>
                                    <th>{{ currencyFormat($totalServiceCharge) }}</th>
                                    <th>{{ currencyFormat($totalSubAmount) }}</th>
                                    <th>{{ currencyFormat($totalTip) }}</th>
                                    <th>{{ currencyFormat($totalDiscount) }}</th>
                                    <th>{{ currencyFormat($total) }}</th>
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
            </div>
        </div>
    </div>
    </div>
    <!--Main Section End-->

    <x-restaurant.email-report action="{{ route('cloasing-report-export') }}">
        @section('additionalFields')
            <input type="hidden" name="type" value="email">
            <input type="hidden" name="date" value="{{ $date }}">
        @endsection
    </x-restaurant.email-report>
@endsection

@section('js')
<script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
<script>
    flatpickr(".datetimepicker", {
        dateFormat: "Y-m-d"
    });
</script>
@endsection
