@extends('layouts.master')
@section('title')
    Sales Summary Report
@endsection
@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
    <style>
        .def_hidden {
            display: none;
            table-layout: fixed;
            margin-top: 20px;
        }

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
                <h4>Sales Summary Report</h4>

                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#emailReport">Email</button>

                    <form method="GET" action="{{ route('report.export-sales-summary-report-excel') }}">
                        <input type="hidden" name="sections" class="section_input">
                        <input type="hidden" name="start_date" value="{{ $start_date_utc }}">
                        <input type="hidden" name="end_date" value="{{ $end_date_utc }}">
                        <button type="submit" class="btn btn-success btn-sm">Export</button>
                    </form>

                    <form method="GET" action="{{ route('report.export-sales-summary-report-pdf') }}" target="_blank">
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
                <div class="col-sm-12 col-md-4 col-lg-2 offset-sm-0 offset-md-1 offset-lg-2">

                    <div class="card p-2">
                        <table>
                            <tr>
                                <td>
                                    <label for="select_all" class="form-check-label" style="width: 100%;">All</label>
                                </td>
                                <td>
                                    <input id="select_all" class="form-check-input" type="checkbox">
                                </td>
                            </tr>

                            <tr style="height: 15px;"></tr>

                            <tr>
                                <td>
                                    <label for="salesByPaymentType" class="form-check-label" style="width: 100%;">Payment
                                        Types</label>
                                </td>
                                <td>
                                    <input id="salesByPaymentType" class="form-check-input report_check_box" type="checkbox"
                                        data-target="#salesByPaymentTypeTable" />
                                </td>
                            </tr>

                            <tr style="height: 15px;"></tr>

                            <tr>
                                <td>
                                    <label for="tipsByPaymentMethod" class="form-check-label" style="width: 100%;">Tips (By
                                        Payment Method)</label>
                                </td>
                                <td>
                                    <input id="tipsByPaymentMethod" class="form-check-input report_check_box"
                                        type="checkbox" data-target="#tipsByPaymentTypeTable" />
                                </td>
                            </tr>

                            <tr style="height: 15px;"></tr>

                            <tr>
                                <td>
                                    <label for="tipsByStaff" class="form-check-label" style="width: 100%;">Tips (By
                                        Staff)</label>
                                </td>
                                <td>
                                    <input id="tipsByStaff" class="form-check-input report_check_box" type="checkbox"
                                    data-target="#TipsByStaffTable" />
                                </td>
                            </tr>

                            <tr style="height: 15px;"></tr>

                            <tr>
                                <td>
                                    <label for="OrderTypes" class="form-check-label" style="width: 100%;">Order
                                        Types</label>
                                </td>
                                <td>
                                    <input id="OrderTypes" class="form-check-input report_check_box" type="checkbox"
                                        data-target="#OrderTypesTable" />
                                </td>
                            </tr>

                            <tr style="height: 15px;"></tr>

                            <tr>
                                <td>
                                    <label for="ItemTypes" class="form-check-label" style="width: 100%;">Item Types</label>
                                </td>
                                <td>
                                    <input id="ItemTypes" class="form-check-input report_check_box" type="checkbox"
                                        data-target="#ItemTypesTable" />
                                </td>
                            </tr>

                            <tr style="height: 15px;"></tr>

                            <tr>
                                <td>
                                    <label for="voidedByStaff" class="form-check-label" style="width: 100%;">Order
                                        Voided (By Staff)</label>
                                </td>
                                <td>
                                    <input id="voidedByStaff" class="form-check-input report_check_box" type="checkbox"
                                        data-target="#VoidedByStaffTable" />
                                </td>
                            </tr>

                            <tr style="height: 15px;"></tr>

                            <tr>
                                <td>
                                    <label for="refundedOrders" class="form-check-label" style="width: 100%;">Refunded Tickets</label>
                                </td>
                                <td>
                                    <input id="refundedOrders" class="form-check-input report_check_box" type="checkbox"
                                        data-target="#RefundedOrdersTable" />
                                </td>
                            </tr>

                            <tr style="height: 15px;"></tr>

                            <tr>
                                <td>
                                    <label for="Category" class="form-check-label" style="width: 100%;">Category</label>
                                </td>
                                <td>
                                    <input id="Category" class="form-check-input report_check_box" type="checkbox"
                                        data-target="#CategoryTable" />
                                </td>
                            </tr>

                            <tr style="height: 15px;"></tr>

                            <tr>
                                <td>
                                    <label for="Item" class="form-check-label" style="width: 100%;">Item</label>
                                </td>
                                <td>
                                    <input id="Item" class="form-check-input report_check_box" type="checkbox"
                                        data-target="#ItemTable" />
                                </td>
                            </tr>

                            <tr style="height: 15px;"></tr>

                            <tr>
                                <td>
                                    <label for="Batch" class="form-check-label" style="width: 100%;">Batch</label>
                                </td>
                                <td>
                                    <input id="Batch" class="form-check-input report_check_box" type="checkbox"
                                        data-target="#BatchTable" />
                                </td>
                            </tr>

                            <tr style="height: 15px;"></tr>

                            <tr>
                                <td>
                                    <label for="DeliveryPartner" class="form-check-label" style="width: 100%;">Delivery Partner</label>
                                </td>
                                <td>
                                    <input id="DeliveryPartner" class="form-check-input report_check_box" type="checkbox"
                                        data-target="#DeliveryPartnerTable" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 col-lg-4">
                    <div id="printableArea" class="card p-3">
                        @include('restaurant.Report.pdf.sales_summary', [
                            'restaurant' => $restaurant,
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                            'report' => $summary,
                            'salesByPaymentType' => $salesByPaymentType,
                            'tipsByPaymentType' => $tipsByPaymentType,
                            'orderTypes' => $orderTypes,
                            'itemTypeRows' => $itemTypeRows,
                            'categoryRows' => $categoryRows,
                            'itemRows' => $itemRows,
                            'view' => 1
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->

    <x-restaurant.email-report action="{{ route('report.email-sales-summary-report') }}">
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

    <script>
        $(document).ready(function (e) {
            $('.report_check_box').on('change', function () {

                var isChecked = $(this).is(':checked');
                var target = $(this).data('target');
                console.log({ isChecked, target });
                isChecked ? $(target).show() : $(target).hide();

                $('html, body').animate({
                    scrollTop: ($($(this).data('target')).offset().top - 50)
                }, 100);

                $('.report_check_box:checked').length === $('.report_check_box').length ?
                    $('#select_all').prop('checked', true) : $('#select_all').prop('checked', false);
                
                $(".section_input").val($('.report_check_box:checked').map(function() {
                    return this.id;
                }).get().join(','));
            });

            $("#select_all").on('change', function () {
                var isChecked = $(this).is(':checked');
                $('.report_check_box').prop('checked', isChecked).trigger('change');
            });

        });
    </script>
@endsection