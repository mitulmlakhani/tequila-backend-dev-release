@php
    $html = $html ?? true;
@endphp

@if($html)

    <html>

    <head>
        <title>Shift Summary Report</title>
        <style>
            @page {
                size: 40mm 1000pt;
                margin: 1mm;
            }

            body {
                width: 38mm;
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
                <h2 style="margin-top: 0px; margin-bottom: 0px;">{{ $report_title ?? '' }}</h2>
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
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Summary</h2>
            </td>
        </tr>
        <tr>
            <td colspan="2">Ticket Count</td>
            <td style="text-align: right;">{{ $report['summary']['ticket_count'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Non Taxable Sales</td>
            <td style="text-align: right;">{{ $report['summary']['nontaxable_sales'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Taxable Sales</td>
            <td style="text-align: right;">{{ $report['summary']['taxable_sales'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Discount</td>
            <td style="text-align: right;">-{{ $report['summary']['total_discount'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Net Sales</td>
            <td style="text-align: right;">{{ $report['summary']['net_sales'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Tax</td>
            <td style="text-align: right;">{{ $report['summary']['total_tax'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Refund</td>
            <td style="text-align: right;">{{ '-'.($report['summary']['total_refund'] ?? 0) }}</td>
        </tr>

        <tr>
            <td colspan="2" style="font-weight: bold;">Net Sales + Tax</td>
            <td style="text-align: right; font-weight: bold;">{{ ($report['summary']['net_sales'] ?? 0) + ($report['summary']['total_tax'] ?? 0) }}</td>
        </tr>

        <tr>
            <td colspan="2">Payment Fees</td>
            <td style="text-align: right;">{{ $report['summary']['total_transaction_fees'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Service Charge</td>
            <td style="text-align: right;">{{ $report['summary']['total_service_charge'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Tips</td>
            <td style="text-align: right;">{{ $report['summary']['total_tip'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2" style="font-weight: bold;">Total Sale</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['summary']['total_sales'] ?? 0 }}</td>
        </tr>
        
        <tr>
            <td colspan="2" style="font-weight: bold;">Delivery Partner Sales</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['summary']['total_delivery_partner_sales'] ?? 0 }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Payment Type</h2>
            </td>
        </tr>

        <tr>
            <td colspan="2">Cash</td>
            <td style="text-align: right;">{{ $report['payment_type']['cash_payment'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Credit</td>
            <td style="text-align: right;">{{ $report['payment_type']['credit_payment'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Crypto</td>
            <td style="text-align: right;">{{ $report['payment_type']['crypto_payment'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Other</td>
            <td style="text-align: right;">{{ $report['payment_type']['other_payment'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2" style="font-weight: bold;">Total</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['payment_type']['total_payment'] ?? 0 }}</td>
        </tr>
    </table>


    <table>

        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Credit Card</h2>
            </td>
        </tr>

        <tr>
            <td colspan="2">Sales</td>
            <td style="text-align: right;">{{ $report['credit_card']['credit_net_sales'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Transaction Fees Charge</td>
            <td style="text-align: right;">{{ $report['credit_card']['credit_transaction_fee'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Tips</td>
            <td style="text-align: right;">{{ $report['credit_card']['credit_tip'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2" style="font-weight: bold;">Total</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['credit_card']['credit_payment'] ?? 0 }}</td>
        </tr>

    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Cash</h2>
            </td>
        </tr>

        <tr>
            <td colspan="2">Sales</td>
            <td style="text-align: right;">{{ $report['cash']['cash_net_sales'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Tips</td>
            <td style="text-align: right;">{{ $report['cash']['cash_tip'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2" style="font-weight: bold;">Total</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['cash']['cash_payment'] ?? 0 }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Sale Type</h2>
            </td>
        </tr>

        @foreach (($report['sale_type'] ?? []) as $title => $amount)
            @if($amount > 0)
                <tr>
                    <td>{{ ucwords($title) }}</td>
                    <td style="text-align: right;">{{ $report['sale_type_count'][$title] ?? 0 }}</td>
                    <td style="text-align: right;">{{ $amount }}</td>
                </tr>
            @endif
        @endforeach

        <tr>
            <td style="font-weight: bold;">Total</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['summary']['ticket_count'] ?? 0 }}</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['summary']['total_paid'] ?? 0 }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Tips</h2>
            </td>
        </tr>

        @if(($report['tips']['credit_tip'] ?? 0) > 0)
            <tr>
                <td colspan="2">Credit Tip</td>
                <td style="text-align: right;">{{ $report['tips']['credit_tip'] }}</td>
            </tr>
        @endif

        @if(($report['tips']['cash_tip'] ?? 0) > 0)
            <tr>
                <td colspan="2">Cash Tip</td>
                <td style="text-align: right;">{{ $report['tips']['cash_tip'] }}</td>
            </tr>
        @endif

        @if(($report['tips']['crypto_tip'] ?? 0) > 0)
            <tr>
                <td colspan="2">Crypto Tip</td>
                <td style="text-align: right;">{{ $report['tips']['crypto_tip'] }}</td>
            </tr>
        @endif

        @if(($report['tips']['other_tip'] ?? 0) > 0)
            <tr>
                <td colspan="2">Other Tip</td>
                <td style="text-align: right;">{{ $report['tips']['other_tip'] }}</td>
            </tr>
        @endif

        <tr>
            <td colspan="2" style="font-weight: bold;">Total</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['tips']['total_tip'] ?? 0 }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Total Discount</h2>
            </td>
        </tr>

        <tr>
            <td>Discount</td>
            <td style="text-align: right;">{{ $report['discount']['total_discount_tickets'] ?? 0 }}</td>
            <td style="text-align: right;">{{ $report['discount']['total_discount'] ?? 0 }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Cash Drawer (Cash)</h2>
            </td>
        </tr>

        <tr>
            <td colspan="2">Starting Cash</td>
            <td style="text-align: right;">{{ $report['cash_drawer']['starting_cash'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Cash Sales</td>
            <td style="text-align: right;">{{ $report['cash_drawer']['cash_sales'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Cash Out</td>
            <td style="text-align: right;">{{ $report['cash_drawer']['cast_out'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2" style="font-weight: bold;">Expected Cash</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['cash_drawer']['expected_cash'] ?? 0 }}</td>
        </tr>

    </table>

    <table>
        <tr>
            <td colspan="3" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Cash Owned</h2>
            </td>
        </tr>

        <tr>
            <td colspan="2">Cash Sales</td>
            <td style="text-align: right;">{{ $report['cash_drawer']['cash_sales'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Cash Tips</td>
            <td style="text-align: right;">-{{ $report['tips']['cash_tip'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2" style="font-weight: bold;">Cash Owned House</td>
            <td style="text-align: right; font-weight: bold;">{{ ($report['cash_drawer']['cash_sales'] ?? 0) - ($report['tips']['cash_tip'] ?? 0) }}</td>
        </tr>

    </table>

    <table>
        <tr>
            <td colspan="3" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Staff Tips</h2>
            </td>
        </tr>

        @foreach ($report['staff_tips'] ?? [] as $staffName => $tipAmount)
        <tr>
            <td colspan="2">{{ $staffName }}</td>
            <td style="text-align: right;">{{ $tipAmount }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="2" style="font-weight: bold;">Total</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['tips']['total_tip'] ?? 0 }}</td>
        </tr>

    </table>

    <table>
        <tr>
            <td colspan="4" style="text-align: center;">
                <h2 style="margin-top: 4px; margin-bottom: 4px;">Delivery Partner Sales</h2>
            </td>
        </tr>

        @foreach ($report['delivery_partner_sales'] ?? [] as $partnerName => $data)
        <tr>
            <td colspan="2">{{ $partnerName }}</td>
            <td style="text-align: right;">{{ $data['orders'] }}</td>
            <td style="text-align: right;">{{ $data['sales'] }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="2" style="font-weight: bold;">Total</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['summary']['total_delivery_partner_orders'] ?? 0 }}</td>
            <td style="text-align: right; font-weight: bold;">{{ $report['summary']['total_delivery_partner_sales'] ?? 0 }}</td>
        </tr>

    </table>

    @if($html)
        </body>

        </html>
    @endif