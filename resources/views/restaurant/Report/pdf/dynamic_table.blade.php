@if($restaurant ?? null)
<table style="width: 100%;">
    <tr style="border:0">
        <td style="text-align: center;">
            <h2>{{ $restaurant->name }}</h2>
            <span>{{ $restaurant->address }}</span><br>
            <span>{{ $restaurant->phone }}</span>
        </td>
    </tr>
    <tr style="border:0">
        <td style="text-align: center;">
            @if($dates ?? null)
                <h3>{{ $report ?? "" }}</h3>
                <span>{{ $dates['start_date'] }} - {{ $dates['end_date'] }}</span>
            @endif
        </td>
    </tr>
</table>
<br>
@endif

<table border="1" cellspacing="0" cellpadding="5" style="width: 100%;">
    <thead>
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                @foreach ($row as $cell)
                    <td style="{{ ($wrapText ?? true) ? 'white-space: nowrap;' : '' }} {{ is_numeric(str_replace(",", "", $cell)) ? 'text-align: right;' : ''}}">
                        {{ $cell }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>