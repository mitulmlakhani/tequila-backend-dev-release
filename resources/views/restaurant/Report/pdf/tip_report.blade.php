<html>

<head>
    <title>Tip Report</title>
    
    @include('restaurant.Report.pdf.receipt_global_style')
</head>

<body>

    <x-report-pdf-header :restaurantName="$restaurant->name" :restaurantAddress="$restaurant->address" :restaurantPhone="$restaurant->phone" :title="'Tip Report'" :startDate="$start_date"
        :endDate="$end_date" />


    @foreach ($rows as $date => $row)
        <table>
            <tr>
                <td colspan="2" class="tab-heading" style="text-align: center; border-bottom: 1px solid black; padding-bottom: 1pt;">
                    <p>{{ $date }}</p>
                </td>
            </tr>

            @foreach ($row as $rec)
                <tr>
                    <td colspan="2" style="text-align:center; font-weight: bold;">{{ $rec['Employee'] }}</td>
                </tr>
                <tr>
                    <td>Cash Tip</td>
                    <td style="text-align: right;">{{ $rec['Cash Tip'] }}</td>
                </tr>
                <tr>
                    <td>Credit Card Tip</td>
                    <td style="text-align: right;">{{ $rec['Credit Card Tip'] }}</td>
                </tr>
                <tr>
                    <td>Credit Card Tip</td>
                    <td style="text-align: right;">{{ $rec['Credit Card Tip'] }}</td>
                </tr>
                <tr>
                    <td>Crypto Tip</td>
                    <td style="text-align: right;">{{ $rec['Crypto Tip'] }}</td>
                </tr>
                <tr>
                    <td>Other Tip</td>
                    <td style="text-align: right;">{{ $rec['Other Tip'] }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Shared Tips</td>
                    <td style="text-align: right; font-weight: bold;">{{ $rec['Shared Tips'] }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Transactions</td>
                    <td style="text-align: right;">{{ $rec['Total Transactions'] }}</td>
                </tr>
                <tr>
                    <td>Total Tip</td>
                    <td style="text-align: right;">{{ $rec['Total Tip'] }}</td>
                </tr>
                <tr>
                    <td style="border: 0;" colspan="2"><br /></td>
                </tr>
            @endforeach
        </table>
    @endforeach

</body>