<html>

<head>
    <title>{{ $report ?? '' }}</title>

    @include('restaurant.Report.pdf.receipt_global_style')
</head>

<body>

    <x-report-pdf-header :restaurantName="$restaurant->name" :restaurantAddress="$restaurant->address" :restaurantPhone="$restaurant->phone" :title="$report ?? ''" :startDate="$start_date"
        :endDate="$end_date" />

    @foreach ($rows as $date => $row)
        <table class="content" style="{{ $loop->last ? 'margin-bottom: 0;' : '' }}">
            <tr>
                <th colspan="2" class=" tab-heading" style="text-align: center;">
                    <p style="margin-top: 1pt; margin-bottom: 1pt;">{!! $date !!}</p>
                </th>
            </tr>

            @foreach ($row as $title => $value)
                <tr>
                    <td>{{ $title }}</td>
                    <td style="text-align: right;">{{ $value }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach

</body>

</html>