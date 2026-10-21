<html>

<head>
    <title>Sales By Employees Report</title>

    @include('restaurant.Report.pdf.receipt_global_style')

    <style>
        .py-2 {
            padding-top: 2pt;
            padding-bottom: 2pt;
        }
    </style>
</head>

<body>

      <x-report-pdf-header :restaurantName="$restaurant->name" :restaurantAddress="$restaurant->address" :restaurantPhone="$restaurant->phone" :title="$report ?? ''" :startDate="$start_date"
        :endDate="$end_date" />

    @foreach ($reportRows as $row)

        <table>
            <tr>
                <td colspan="2" class="py-2 tab-heading" style="text-align: center; border-bottom: 1px dashed black;">
                    <h2 style="margin-top: 4px; margin-bottom: 4px;">{{ $row->employee ?? '' }}</h2>
                </td>
            </tr>

            <tr>
                <td style="text-align: center;" colspan="2" class="py-2">
                    <strong>Sales</strong>
                </td>
            </tr>

            <tr>
                <td>Cash Sales</td>
                <td style="text-align: right;">{{ $row->cash_sale ?? 0 }}</td>
            </tr>

            <tr>
                <td>Credit Sales</td>
                <td style="text-align: right;">{{ $row->credit_sale ?? 0 }}</td>
            </tr>

            <tr>
                <td>Crypto Sales</td>
                <td style="text-align: right;">{{ $row->crypto_sale ?? 0 }}</td>
            </tr>

            <tr>
                <td>Other Sales</td>
                <td style="text-align: right;">{{ $row->other_sale ?? 0 }}</td>
            </tr>

            <tr class="total-row">
                <td>Total Sales</td>
                <td style="text-align: right;">{{ $row->total_sale ?? 0 }}</td>
            </tr>

            <tr>
                <td style="text-align: center;" colspan="2" class="py-2">
                    <strong>Refund</strong>
                </td>
            </tr>

            <tr>
                <td>Refund Count</td>
                <td style="text-align: right;">{{ $row->refund_count ?? 0 }}</td>
            </tr>

            <tr>
                <td>Cash Refunds</td>
                <td style="text-align: right;">{{ $row->cash_refund ?? 0 }}</td>
            </tr>

            <tr>
                <td>Credit Refunds</td>
                <td style="text-align: right;">{{ $row->credit_refund ?? 0 }}</td>
            </tr>

            <tr>
                <td>Crypto Refunds</td>
                <td style="text-align: right;">{{ $row->crypto_refund ?? 0 }}</td>
            </tr>

            <tr>
                <td>Other Refunds</td>
                <td style="text-align: right;">{{ $row->other_refund ?? 0 }}</td>
            </tr>

            <tr class="total-row">
                <td>Total Refund</td>
                <td style="text-align: right;">{{ $row->refund_amount ?? 0 }}</td>
            </tr>

            <tr>
                <td style="text-align: center;" colspan="2" class="py-2">
                    <strong>Tip</strong>
                </td>
            </tr>

            <tr>
                <td>Cash Tips</td>
                <td style="text-align: right;">{{ $row->cash_tip ?? 0 }}</td>
            </tr>

            <tr>
                <td>Credit Tips</td>
                <td style="text-align: right;">{{ $row->credit_tip ?? 0 }}</td>
            </tr>

            <tr>
                <td>Crypto Tips</td>
                <td style="text-align: right;">{{ $row->crypto_tip ?? 0 }}</td>
            </tr>

            <tr>
                <td>Other Tips</td>
                <td style="text-align: right;">{{ $row->other_tip ?? 0 }}</td>
            </tr>

            <tr class="total-row">
                <td>Total Tip</td>
                <td style="text-align: right;">{{ $row->tip_amount ?? 0 }}</td>
            </tr>

            <tr>
                <td>Cash Owned</td>
                <td style="text-align: right;">{{ abs($row->cash_sale - $row->tip_amount) }}</td>
            </tr>

        </table>

    @endforeach
</body>