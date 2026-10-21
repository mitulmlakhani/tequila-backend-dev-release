@php
    $sectionsArr = explode(',', $sections ?? '');

    $html = $html ?? true;
@endphp

@if($html)

    <html>

    <head>
        <title>Sales Summary Report</title>
        <style>
            @page {
                size: {{ config('services.receipt.width_mm', '') }}mm {{ config('services.receipt.default_height') }};
                margin: {{ config('services.receipt.margin_x_mm', 1) }}mm;
            }

            body {
                width: {{ config('services.receipt.width_mm', '') - (config('services.receipt.margin_x_mm', 1) * 2) }}mm;
                margin: 0;
                padding: 10px 0px;
                font-size: 11px;
                box-sizing: border-box;
            }
        </style>

        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                font-family: Arial, sans-serif !important;
            }

            th,
            td {
                border: 0.5px solid #424040ff;
                font-size: 8px;
                padding: 1px 1px;
                word-break: break-word;
            }
        </style>
    </head>

    <body>
@endif

    <table>
        <tr>
            <td colspan="3" style="border: 0; text-align: center;">
                <h1>{{ $restaurant->name }}</h1>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="border: 0; text-align: center;">
                <span>{{ $restaurant->address }}</span><br>
                <span>{{ $restaurant->phone }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 8px; border: 0; text-align: center;">
                <span style="padding:0; border-bottom:1px solid #222;">{{ $start_date }} - {{
    $end_date }}</span>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class=" tab-heading" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Summary</h2>
            </td>
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
        <tr>
            <td colspan="2"><strong>Total Sales</strong></td>
            <td style="text-align: right;"><strong>{{ $summary['total_sale'] }}</strong></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Delivery Partner Sales</strong></td>
            <td style="text-align: right;"><strong>{{ $summary['delivery_partner_sale'] }}</strong></td>
        </tr>
    </table>

    @if($sections == "all" || in_array('salesByPaymentType', $sectionsArr))
        <table class="def_hidden" id="salesByPaymentTypeTable">
            <tr>
                <td colspan="3" class=" tab-heading" style="text-align: center;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">Payment Types</h2>
                </td>
            </tr>
            @foreach($salesByPaymentType as $type => $amount)
                <tr>
                    <td colspan="2" style="{{ $type == 'total' ? 'font-weight: bold;' : '' }}">{{ ucfirst($type) }}</td>
                    <td style="text-align: right;">{{ $amount }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($sections == "all" || in_array('tipsByPaymentMethod', $sectionsArr))
        <table class="def_hidden" id="tipsByPaymentTypeTable">
            <tr>
                <td colspan="3" class=" tab-heading" style="text-align: center;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">Tips By Payment Type</h2>
                </td>
            </tr>
            @foreach($tipsByPaymentType as $type => $amount)
                <tr>
                    <td colspan="2" style="{{ $type == 'total' ? 'font-weight: bold;' : '' }}">{{ ucfirst($type) }}</td>
                    <td style="text-align: right;">{{ $amount }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($sections == "all" || in_array('tipsByStaff', $sectionsArr))
        <table class="def_hidden" id="TipsByStaffTable">
            <tr>
                <td colspan="3" class=" tab-heading" style="text-align: center;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">Tips By Staff</h2>
                </td>
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
                <td colspan="3" class=" tab-heading" style="text-align: center;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">Order Types</h2>
                </td>
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
                <td colspan="3" class=" tab-heading" style="text-align: center;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">Item Types</h2>
                </td>
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
                <td colspan="3" class=" tab-heading" style="text-align: center;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">Voided By Staff</h2>
                </td>
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
                <td colspan="3" class=" tab-heading" style="text-align: center;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">Refunded Tickets</h2>
                </td>
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
                <td colspan="3" class=" tab-heading" style="text-align: center;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">Category</h2>
                </td>
            </tr>
            @foreach($categoryRows as $row)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="width: 40%;">{{ ucfirst($row['name']) }}</td>
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
                <td colspan="3" class=" tab-heading" style="text-align: center;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">Item</h2>
                </td>
            </tr>
            @foreach($itemRows as $row)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="width: 40%;">{{ ucfirst($row['name']) }}</td>
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
                <td colspan="3" class=" tab-heading" style="text-align: center;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">Batch</h2>
                </td>
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

        @if($sections == "all" || in_array('DeliveryPartner', $sectionsArr))
        <table class="def_hidden" id="DeliveryPartnerTable">
            <tr>
                <td colspan="3" class=" tab-heading" style="text-align: center;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">Delivery Partner</h2>
                </td>
            </tr>
            @foreach($deliveryPartnerRows as $row)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="width: 40%;">{{ ucfirst($row['delivery_partner']) }}</td>
                    <td style="width:20%; text-align: right;">
                        <div>{{ $row['order_count'] }}</div>
                    </td>
                    <td style="width:20%; text-align: right;">{{ $row['total_amount'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if($html)
        </body>
    </html>
    @endif