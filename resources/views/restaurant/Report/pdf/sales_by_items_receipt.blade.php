<html>

<head>
    <title>{{ $report ?? '' }}</title>

    @include('restaurant.Report.pdf.receipt_global_style')
</head>

<body>
    <x-report-pdf-header :restaurantName="$restaurant->name" :restaurantAddress="$restaurant->address" :restaurantPhone="$restaurant->phone" :title="$report ?? ''" :startDate="$start_date"
        :endDate="$end_date" />

    <table>
        <tr>
            <td style="font-weight: bold; text-align:left; width: 42%;">{{ $headers[0] }}</td>
            <td style="font-weight: bold; text-align:center; width: 15%;">{{ $headers[1] }}</td>
            <td style="font-weight: bold; text-align:right; width: 18%;">{{ $headers[2] }}</td>
            <td style="font-weight: bold; text-align:right; width: 25%;">{{ $headers[3] }}</td>
        </tr>

        @foreach ($rows as $row)
            <tr>
                <td style="width: 42%; text-align: left;" class="icon-cell">{{ $row[0] }}</td>
                <td style="width: 15%; text-align: center;">{{ $row[1] }}</td>
                <td style="width: 18%; text-align: right;">{{ $row[2] }}</td>
                <td style="width: 25%; text-align: right;">{{ $row[3] }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>