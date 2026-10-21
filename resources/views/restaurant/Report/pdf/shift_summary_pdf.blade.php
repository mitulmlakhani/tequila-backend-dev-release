<html>

<head>
    <title>{{ $report_title ?? '' }}</title>

    @include('restaurant.Report.pdf.receipt_global_style')

    <style>
        .tab-heading {
            padding-bottom: 2pt;
        }
    </style>
</head>

<body>
    <x-report-pdf-header :restaurantName="$restaurant->name" :restaurantAddress="$restaurant->address" :restaurantPhone="$restaurant->phone" :title="$report_title ?? ''" :startDate="$start_date"
        :endDate="$end_date" />

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <p>Summary</p>
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
            <td style="text-align: right;">{{ $report['summary']['total_refund'] ?? 0 }}</td>
        </tr>

        <tr>
            <td colspan="2">Net Sales + Tax</td>
            <td style="text-align: right; font-weight: bold;">
                {{ ($report['summary']['net_sales'] ?? 0) + ($report['summary']['total_tax'] ?? 0) }}</td>
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

        <tr class="total-row">
            <td colspan="2">Total Sale</td>
            <td style="text-align: right;">{{ $report['summary']['total_sales'] ?? 0 }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <p>Payment Type</p>
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

        <tr class="total-row">
            <td colspan="2">Total</td>
            <td style="text-align: right;">{{ $report['payment_type']['total_payment'] ?? 0 }}</td>
        </tr>
    </table>

    <table>

        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <p>Credit Card</p>
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

        <tr class="total-row">
            <td colspan="2">Total</td>
            <td style="text-align: right;">{{ $report['credit_card']['credit_payment'] ?? 0 }}</td>
        </tr>

    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <p>Cash</p>
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

        <tr class="total-row">
            <td colspan="2">Total</td>
            <td style="text-align: right;">{{ $report['cash']['cash_payment'] ?? 0 }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <p>Sale Type</p>
            </td>
        </tr>

        @foreach (($report['sale_type'] ?? []) as $title => $amount)
            @if ($amount > 0)
                <tr>
                    <td>{{ ucwords($title) ?? 0 }}</td>
                    <td style="text-align: right;">{{ $report['sale_type_count'][$title] ?? 0 }}</td>
                    <td style="text-align: right;">{{ $amount ?? 0 }}</td>
                </tr>
            @endif
        @endforeach

        <tr class="total-row">
            <td>Total</td>
            <td style="text-align: right;">{{ $report['summary']['ticket_count'] ?? 0 }}</td>
            <td style="text-align: right;">{{ $report['summary']['total_paid'] ?? 0 }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <p>Tips</p>
            </td>
        </tr>

        @if (($report['tips']['credit_tip'] ?? 0) > 0)
            <tr>
                <td colspan="2">Credit Tip</td>
                <td style="text-align: right;">{{ $report['tips']['credit_tip'] ?? 0 }}</td>
            </tr>
        @endif

        @if (($report['tips']['cash_tip'] ?? 0) > 0)
            <tr>
                <td colspan="2">Cash Tip</td>
                <td style="text-align: right;">{{ $report['tips']['cash_tip'] ?? 0 }}</td>
            </tr>
        @endif

        @if (($report['tips']['crypto_tip'] ?? 0) > 0)
            <tr>
                <td colspan="2">Crypto Tip</td>
                <td style="text-align: right;">{{ $report['tips']['crypto_tip'] ?? 0 }}</td>
            </tr>
        @endif

        @if (($report['tips']['other_tip'] ?? 0) > 0)
            <tr>
                <td colspan="2">Other Tip</td>
                <td style="text-align: right;">{{ $report['tips']['other_tip'] ?? 0 }}</td>
            </tr>
        @endif

        <tr class="total-row">
            <td colspan="2">Total</td>
            <td style="text-align: right;">{{ $report['tips']['total_tip'] ?? 0 }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <p>Total Discount</p>
            </td>
        </tr>

        <tr>
            <td>Discount</td>
            <td style="text-align: right;">{{ $report['discount']['total_discount_tickets'] ?? 0 ?? 0 }}</td>
            <td style="text-align: right;">{{ $report['discount']['total_discount'] ?? 0 }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <p>Cash Drawer (Cash)</p>
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

        <tr class="total-row">
            <td colspan="2">Expected Cash</td>
            <td style="text-align: right;">{{ $report['cash_drawer']['expected_cash'] ?? 0 }}</td>
        </tr>

    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <p>Cash Owned</p>
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

        <tr class="total-row">
            <td colspan="2">Cash Owned House</td>
            <td style="text-align: right;">
                {{ ($report['cash_drawer']['cash_sales'] ?? 0) - ($report['tips']['cash_tip'] ?? 0) }}</td>
        </tr>

    </table>

    <table>
        <tr>
            <td colspan="3" class="tab-heading" style="text-align: center;">
                <p>Staff Tips</p>
            </td>
        </tr>

        @foreach ($report['staff_tips'] ?? [] as $staffName => $tipAmount)
            <tr>
                <td colspan="2">{{ $staffName ?? 0 }}</td>
                <td style="text-align: right;">{{ $tipAmount ?? 0 }}</td>
            </tr>
        @endforeach

        <tr class="total-row">
            <td colspan="2">Total</td>
            <td style="text-align: right;">{{ $report['tips']['total_tip'] ?? 0 }}</td>
        </tr>

    </table>

</body>

</html>
