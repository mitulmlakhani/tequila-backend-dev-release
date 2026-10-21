@php
    $sectionsArr = explode(',', $sections ?? '');

    $html = $html ?? true;
@endphp

@if($html)

    <html>

    <head>
        <title>Sales Summary Report</title>

        @include('restaurant.Report.pdf.receipt_global_style')

        <style>
            .total-row td {
                border-top: 1px dashed black; padding-top: 2pt;
            }

            .multiline {
                padding-left: 5px;
                text-indent: -5px;
                white-space: normal; 
            }
        </style>
    </head>

    <body>
@endif

    <x-report-pdf-header :restaurantName="$restaurant->name" :restaurantAddress="$restaurant->address" :restaurantPhone="$restaurant->phone" :title="'Sales Summary Report'" :startDate="$start_date"
        :endDate="$end_date" />

    <table>
        <tr>
            <th colspan="3" class=" tab-heading" style="text-align: center;">
                <p style="margin-top: 4px; margin-bottom: 4px;">Summary</p>
            </th>
        </tr>
        <tr>
            <td colspan="2">Gross Sales</td>
            <td style="text-align: right;">{{ $summary['gross_sale'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Surcharge</td>
            <td style="text-align: right;">{{ $summary['surcharge'] }}</td>
        </tr>

        <tr>
            <td colspan="2">Discount</td>
            <td style="text-align: right;">{{ $summary['total_discount'] }}</td>
        </tr>

        <tr>
            <td colspan="2">Net Sales</td>
            <td style="text-align: right;">{{ $summary['net_sales'] }}</td>
        </tr>

        <tr>
            <td colspan="2">Tax</td>
            <td style="text-align: right;">{{ $summary['total_tax'] }}</td>
        </tr>

        <tr>
            <td colspan="2">Tips</td>
            <td style="text-align: right;">{{ $summary['tip_amount'] }}</td>
        </tr>

        <tr>
            <td colspan="2">Refund</td>
            <td style="text-align: right;">{{ $summary['refund_amount'] }}</td>
        </tr>
        <tr>
            <td colspan="2">Payment Fees</td>
            <td style="text-align: right;">{{ $summary['total_transaction_fees'] }}</td>
        </tr>
        <tr class="total-row">
            <td colspan="2">Total Sales</td>
            <td style="text-align: right;">{{ $summary['total_sale'] }}</td>
        </tr>
    </table>

    @if($sections == "all" || in_array('salesByPaymentType', $sectionsArr))
        <table class="def_hidden" id="salesByPaymentTypeTable">
            <tr>
                <th colspan="3" class=" tab-heading" style="text-align: center;">
                    <p style="margin-top: 4px; margin-bottom: 4px;">Payment Types</p>
                </th>
            </tr>
            @foreach($salesByPaymentType as $type => $amount)
                <tr class="{{ $type == 'total' ? 'total-row' : '' }}">
                    <td colspan="2">{{ ucfirst($type) }}</td>
                    <td style="text-align: right;">{{ $amount }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($sections == "all" || in_array('tipsByPaymentMethod', $sectionsArr))
        <table class="def_hidden" id="tipsByPaymentTypeTable">
            <tr>
                <th colspan="3" class=" tab-heading" style="text-align: center;">
                    <p style="margin-top: 4px; margin-bottom: 4px;">Tips By Payment Type</p>
                </th>
            </tr>
            @foreach($tipsByPaymentType as $type => $amount)
                <tr class="{{ $type == 'total' ? 'total-row' : '' }}">
                    <td colspan="2">{{ ucfirst($type) }}</td>
                    <td style="text-align: right;">{{ $amount }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($sections == "all" || in_array('tipsByStaff', $sectionsArr))
        <table class="def_hidden" id="TipsByStaffTable">
            <tr>
                <th colspan="3" class=" tab-heading" style="text-align: center;">
                    <p style="margin-top: 4px; margin-bottom: 4px;">Tips By Staff</p>
                </th>
            </tr>
            @foreach($tipsByStaff as $staffName => $data)
                <tr>
                    <td>{{ $staffName }}</td>
                    <td style="text-align: right;">{{ $data['count'] }}</td>
                    <td style="text-align: right;">{{ $data['amount'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($sections == "all" || in_array('OrderTypes', $sectionsArr))
        <table class="def_hidden" id="OrderTypesTable">
            <tr>
                <th colspan="3" class=" tab-heading" style="text-align: center;">
                    <p style="margin-top: 4px; margin-bottom: 4px;">Order Types</p>
                </th>
            </tr>
            @foreach($orderTypes as $type => $amount)
                <tr>
                    <td colspan="2" style="{{ $type == 'total' ? 'font-weight: bold;' : '' }}">{{ ucfirst($type) }}</td>
                    <td style="text-align: right;">{{ $amount }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($sections == "all" || in_array('ItemTypes', $sectionsArr))
        <table class="def_hidden" id="ItemTypesTable">
            <tr>
                <th colspan="3" class=" tab-heading" style="text-align: center;">
                    <p style="margin-top: 4px; margin-bottom: 4px;">Item Types</p>
                </th>
            </tr>
            @foreach($itemTypeRows as $row)
                <tr>
                    <td>{{ ucfirst($row['name']) }}</td>
                    <td style="text-align: right;">
                        <div>{{ $row['quantity'] }}</div>
                    </td>
                    <td style="text-align: right;">{{ $row['amount'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($sections == "all" || in_array("voidedByStaff", $sectionsArr))
        <table class="def_hidden" id="VoidedByStaffTable">
            <tr>
                <th colspan="3" class=" tab-heading" style="text-align: center;">
                    <p style="margin-top: 4px; margin-bottom: 4px;">Voided By Staff</p>
                </th>
            </tr>
            @foreach($voidedByStaff as $staffName => $data)
                <tr>
                    <td>{{ $staffName }}</td>
                    <td style="text-align: right;">{{ $data['count'] }}</td>
                    <td style="text-align: right;">{{ $data['amount'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($sections == "all" || in_array("refundedOrders", $sectionsArr))
        <table class="def_hidden" id="RefundedOrdersTable">
            <tr>
                <th colspan="3" class=" tab-heading" style="text-align: center;">
                    <p style="margin-top: 4px; margin-bottom: 4px;">Refunded Tickets</p>
                </th>
            </tr>
            @foreach($refundedOrders as $order)
                <tr>
                    <td style="width: 40%;">{{ $order['name'] }}</td>
                    <td style="width: 20%;">#{{ $order['id'] }}</td>
                    <td style="width: 20%; text-align: right;">{{ $order['total_amount'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($sections == "all" || in_array('Category', $sectionsArr))
        <table class="def_hidden" id="CategoryTable">
            <tr>
                <th colspan="3" class=" tab-heading" style="text-align: center;">
                    <p style="margin-top: 4px; margin-bottom: 4px;">Category</p>
                </th>
            </tr>
            @foreach($categoryRows as $row)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td class="multiline" style="width: 40%;"><span style="font-size: 8px; vertical-align: middle; height: 100%;">•</span>{{ ucfirst($row['name']) }}</td>
                    <td style="width:20%; text-align: right;">
                        <div>{{ $row['quantity'] }}</div>
                    </td>
                    <td style="width:20%; text-align: right;">{{ $row['amount'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($sections == "all" || in_array('Item', $sectionsArr))
        <table class="def_hidden" id="ItemTable">
            <tr>
                <th colspan="3" class=" tab-heading" style="text-align: center;">
                    <p style="margin-top: 4px; margin-bottom: 4px;">Item</p>
                </th>
            </tr>
            @foreach($itemRows as $row)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td class="multiline" style="width: 40%;"><span style="font-size: 8px; vertical-align: middle; height: 100%;">•</span>{{ ucfirst($row['name']) }}</td>
                    <td style="width:20%; text-align: right;">
                        <div>{{ $row['quantity'] }}</div>
                    </td>
                    <td style="width:20%; text-align: right;">{{ $row['amount'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($sections == "all" || in_array('Batch', $sectionsArr))
        <table class="def_hidden" id="BatchTable">
            <tr>
                <th colspan="3" class=" tab-heading" style="text-align: center;">
                    <p style="margin-top: 4px; margin-bottom: 4px;">Batch</p>
                </th>
            </tr>
            @foreach($batchRows as $row)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="width: 40%;">{{ ucfirst($row['terminal_sn']) }}</td>
                    <td style="width:20%; text-align: right;">
                        <div>{{ $row['count'] }}</div>
                    </td>
                    <td style="width:20%; text-align: right;">{{ $row['amount'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($html)
        </body>
    </html>
    @endif