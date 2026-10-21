@foreach ($reservations as $reservation)
    <li class="list-group-item @if($reservation->is_cancelled) text-danger @endif">
        <a href="javascript:void(0)" class="textcolor editBooking @if($reservation->is_cancelled) text-danger @endif" data-id="{{ $reservation->id }}">
            <u>{{ $reservation->booking_id }}</u> ({{ $reservation->customer->mobile ? $reservation->customer->mobile : $reservation->customer->email }})
            @if($reservation->is_cancelled) 
            Cancelled
            @else
            {{ ticketStatus($reservation->party_confirm_status) }}
            @endif
            <sup class="new beta">
                @if (strtotime(date('Y-m-d', strtotime($reservation->created_at))) == strtotime(date('Y-m-d')))
                    <span class="translate-middle badge rounded-pill text-success">
                        New
                    </span>
                @endif
            </sup>
        </a>
        <sub class="text-right"> {{ calculate_time_span($reservation->created_at) }} </sub>
    </li>
@endforeach