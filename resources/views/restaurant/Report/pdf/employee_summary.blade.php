<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    @include('restaurant.Report.pdf.receipt_global_style')

    <style>
        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .title-row th {
            border-bottom: 1px dashed black;
            font-weight: 500;
        }

        .py-2 {
            padding-top: 2pt;
            padding-bottom: 2pt;
        }

        .tab-heading {
            font-weight: 600 !important;
        }
    </style>
</head>

<body>
    <x-report-pdf-header :restaurantName="$user->restaurant->name" :restaurantAddress="$user->restaurant->address" :restaurantPhone="$user->restaurant->phone" :title="'Sales By Employee Summary'" :startDate="$report['start_date']"
        :endDate="$report['end_date']" />

    <!-- SALES SUMMARY -->
    <table>
        <tr class="title-row">
            <th class="py-2 tab-heading left">
                Sales
            </th>
            <th class="right"></th>
        </tr>
        <tr>
            <td>Ticket Count</td>
            <td class="right">{{ $report['total_tickets'] }}</td>
        </tr>
        <tr>
            <td>Total Sale</td>
            <td class="right">{{ $report['gross_sale'] }}</td>
        </tr>
        @if ($report['gross_sale'] - $report['total_sale'] > 0)
            <tr>
                <td>Payment Fees</td>
                <td class="right">-{{ $report['gross_sale'] - $report['total_sale'] }}</td>
            </tr>
        @endif
        <tr>
            <td>Tips</td>
            <td class="right">{{ $report['tip_amount'] }}</td>
        </tr>
        <tr>
            <td>Tax</td>
            <td class="right">{{ $report['total_tax'] }}</td>
        </tr>
        <tr>
            <td>Discount</td>
            <td class="right">{{ $report['total_discount'] }}</td>
        </tr>
        <tr>
            <td>Refund</td>
            <td class="right">{{ $report['refund_amount'] }}</td>
        </tr>
        <tr class="total-row">
            <td>Total Sales</td>
            <td class="right">{{ $report['total_sale'] }}</td>
        </tr>
    </table>

    <!-- PAYMENT TYPE -->
    <table>
        <tr class="title-row">
            <th class="py-2 tab-heading left">
                Payment
            </th>
            <th class="right">{{ $report['final_sale'] }}</th>
        </tr>
        <tr>
            <td>Credit Card</td>
            <td class="right">{{ $report['credit_sale'] }}</td>
        </tr>
        <tr>
            <td>Cash</td>
            <td class="right">{{ $report['cash_sale'] }}</td>
        </tr>
        <tr>
            <td>Crypto</td>
            <td class="right">{{ $report['crypto_sale'] }}</td>
        </tr>
        <tr>
            <td>Other</td>
            <td class="right">{{ $report['other_sale'] }}</td>
        </tr>
    </table>

    <!-- SALE TYPE -->
    <table>
        <tr class="title-row">
            <th class="py-2 tab-heading left">
                Sale Type
            </th>
            <th class="right">{{ $report['final_sale'] }}</th>
        </tr>
        <tr>
            <td>Dine In</td>
            <td class="right">{{ $report['dinein_total'] }}</td>
        </tr>
        <tr>
            <td>Take Away</td>
            <td class="right">{{ $report['takeaway_total'] }}</td>
        </tr>
        @if ($report['online_total'] > 0)
            <tr>
                <td>Online</td>
                <td class="right">{{ $report['online_total'] }}</td>
            </tr>
        @endif
        @if ($report['giftcard_total'] > 0)
            <tr>
                <td>Gift Card</td>
                <td class="right">{{ $report['giftcard_total'] }}</td>
            </tr>
        @endif
        @if ($report['deliveryparner_total'] > 0)
            <tr>
                <td>Delivery Prt.</td>
                <td class="right">{{ $report['deliveryparner_total'] }}</td>
            </tr>
        @endif
    </table>

    <!-- ITEM TYPE -->
    <table>
        <tr class="title-row">
            <th class="py-2 tab-heading left">
                Item Type
            </th>
            <th class="right">{{ $report['final_sale'] }}</th>
        </tr>
        @foreach ($report['item_types'] ?? [] as $id => $iType)
            <tr>
                <td>{{ $iType['name'] }}</td>
                <td class="right">{{ $iType['total'] }}</td>
            </tr>
        @endforeach
    </table>

    <!-- TIPS SECTION -->
    <table>
        <tr class="title-row">
            <th class="py-2 tab-heading left">
                Tips Paid
            </th>
            <th class="right">{{ $report['tip_amount'] }}</th>
        </tr>
        <tr>
            <td>Tipped Sales</td>
            <td class="right">{{ $report['sales_with_tip'] }}</td>
        </tr>
        <tr>
            <td>Tips
                <strong>{{ $report['tip_amount'] ? number_format(($report['tip_amount'] / $report['sales_with_tip']) * 100, 2) : 0 }}%</strong> of
                Tipped Sales
            </td>
            <td class="right">{{ $report['tip_amount'] }}</td>
        </tr>
        <tr>
            <td>Credit Card</td>
            <td class="right">{{ $report['cc_tip_amount'] }}</td>
        </tr>
        <tr>
            <td>Cash</td>
            <td class="right">{{ $report['cash_tip_amount'] }}</td>
        </tr>
        <tr>
            <td>Other</td>
            <td class="right">{{ $report['other_tip'] }}</td>
        </tr>
    </table>

    @if (($report['pool_tips']['total'] ?? 0) > 0)
        <!-- Pool Tip SECTION -->
        <div class="section-title">
            <span class="sec1">Pool Tips Received</span>
            <span class="sec2">{{ $report['pool_tips']['total'] }}</span>
        </div>
        <table>
            @foreach ($report['pool_tips']['rows'] ?? [] as $row)
                <tr>
                    <td>{{ $row['tip_date'] }}</td>
                    <td>{{ $row['pool_name'] ?? 'N/A' }}</td>
                    <td class="right">{{ $row['tip_amount'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if (($report['tips_shared']['total'] ?? 0) > 0)
        <!-- Pool Tip SECTION -->
        <div class="section-title">
            <span class="sec1">Tips Shared</span>
            <span class="sec2">{{ $report['tips_shared']['total'] }}</span>
        </div>
        <table>
            @foreach ($report['tips_shared']['rows'] ?? [] as $row)
                <tr>
                    <td>{{ $row['tip_date'] }}</td>
                    <td>{{ $row['to_employee'] ?? 'N/A' }}</td>
                    <td class="right">{{ $row['tip_amount'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <!-- CASH OWED -->
    <table>
        <tr class="title-row">
            <th class="py-2 tab-heading left">
                Cash Owed
            </th>
            <th class="right"></th>
        </tr>
        <tr>
            <td>Cash On Hand</td>
            <td class="right">{{ $report['cash_sale'] }}</td>
        </tr>
        <tr>
            <td>Total Tips</td>
            <td class="right">{{ $report['tip_amount'] }}</td>
        </tr>
        <tr>
            <td>Owed to House</td>
            <td class="right">{{ $report['cash_owned_house'] }}</td>
        </tr>
        <tr>
            <td>Owed to {{ $user->name }}</td>
            <td class="right">{{ $report['cash_owned_employee'] }}</td>
        </tr>
    </table>

    <!-- TICKET DETAILS -->
    <table class="table-border">
        <thead>
            <tr class="title-row">
                <th colspan="2" class="py-2 tab-heading left">
                    Tickets
                </th>
                <th colspan="2" class="right">{{ $report['tickets']['total'] ?? 0 }}</th>
            </tr>
            <tr>
                <th class="center">Date</th>
                <th class="center">#Ticket</th>
                <th class="right">Tip</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report['tickets']['items'] ?? [] as $id => $ticket)
                <tr>
                    <td style="width:35%" class="center">{{ $ticket['date'] }}</td>
                    <td style="width:22%" class="center">{{ $id }}</td>
                    <td style="width:18%" class="right">{{ $ticket['tip'] }}</td>
                    <td style="width:20%" class="right">{{ $ticket['amount'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- CATEGORY SUMMARY -->
    <table class="table-border">
        <thead>
            <tr class="title-row">
                <th colspan="1" class="py-2 tab-heading left">
                    Category Summary
                </th>
                <th colspan="2" class="right">{{ $report['tickets']['total'] ?? 0 }}</th>
            </tr>
            <tr>
                <th class="left">Category</th>
                <th class="center">Qty</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report['categories'] ?? [] as $id => $category)
                <tr>
                    <td class="left">{{ $category['name'] }}</td>
                    <td class="center">{{ $category['qty'] }}</td>
                    <td class="right">{{ $category['total'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- CATEGORY DETAILS -->
    <table class="table-border" style="margin-top: 5px;">
        <tr class="title-row">
            <th colspan="4" class="py-2 tab-heading left">
                Category Details
            </th>
            <th colspan="0" class="right">{{ $report['tickets']['total'] ?? 0 }}</th>
        </tr>
    </table>
    @foreach ($report['categories'] ?? [] as $cid => $category)
        <table class="table-border" style="margin-bottom: 5px;">
            <thead>
                <tr>
                    <th colspan="4">
                        {{ $category['name'] }}
                    </th>
                </tr>

                <tr>
                    <th style="padding-bottom: 2pt; border-bottom: 1px dotted black;" class="left">Item</th>
                    <th style="padding-bottom: 2pt; border-bottom: 1px dotted black;" class="center">Qty</th>
                    <th style="padding-bottom: 2pt; border-bottom: 1px dotted black;" class="right">Price</th>
                    <th style="padding-bottom: 2pt; border-bottom: 1px dotted black;" class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($category['items'] ?? [] as $iid => $item)
                    <tr>
                        <td style="width:40%" class="left">{{ $item['name'] }}</td>
                        <td style="width:20%" class="center">{{ $item['qty'] }}</td>
                        <td style="width:20%" class="right">{{ $item['price'] }}</td>
                        <td style="width:20%" class="right">{{ $item['total'] }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td class="center">Total</td>
                    <td class="center">{{ $category['qty'] }}</td>
                    <td></td>
                    <td class="right">{{ $category['total'] }}</td>
                </tr>
            </tfoot>
        </table>
    @endforeach
</body>

</html>
