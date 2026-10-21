<div class="row">
    <div class="col-12 col-md-12 col-lg-12">
        <div class="modal-header" style="display: flow;">
            <b>
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <a href="javascript:void(0)" class="text-anchors reservation_filter" data-type="upcoming"> {{ trans('lang.upcoming') }} </a> |
                        <a href="javascript:void(0)" class="text-anchors reservation_filter" data-type="today"> {{ trans('lang.today') }} </a> |
                        <a href="javascript:void(0)" class="text-anchors reservation_filter" data-type="previous"> {{ trans('lang.pre') }} </a> |
                        <a href="javascript:void(0)" class="text-anchors reservation_filter" data-type="all"> {{ trans('lang.all') }} </a>
                    </div>
                    <div class="col-md-12 col-lg-6" style="text-align: right;">
                        <a href="javascript:void(0)" class="text-anchors reservation_filter_2" data-type="all"> {{ trans('lang.all') }} </a> ({{count($reservations)}}) |
                        <a href="javascript:void(0)" class="text-anchors reservation_filter_2" data-type="open"> Open </a> ({{$reservationCount['openReservations']}}) |
                        <a href="javascript:void(0)" class="text-anchors reservation_filter_2" data-type="completed"> Completed </a> ({{$reservationCount['completedReservations']}}) |
                        <a href="javascript:void(0)" class="text-anchors reservation_filter_2" data-type="cancelled"> Cancelled </a> ({{$reservationCount['cancelledReservations']}})
                    </div>
                </div>
            </b>
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-12">
        <div class="main-content p-3">
            <table id="floormanagement" class="display nowrap w-100">
                <thead>
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">{{ trans('lang.date') }}</th>
                        <th scope="col">{{ trans('lang.name') }}</th>
                        <th scope="col">{{ trans('lang.mobile') }}</th>
                        <th scope="col">{{ trans('lang.table') }}</th>
                        <th scope="col">{{ trans('lang.status') }}</th>
                    </tr>
                </thead>
                <tbody id="reservation_list_div1">
                    @if (count($reservations) > 0)
                        @foreach ($reservations as $key => $reservation)
                            <tr>
                                <td >{{$loop->iteration}}</td>
                                <td>{{ dateFormat($reservation->date) }} {{ timeFormat($reservation->time) }}</td>
                                <td>{{ $reservation->customer->name }}</td>
                                <td>{{ $reservation->customer->mobile }}</td>
                                <td>
                                    {{-- {{dump($reservation->tableDetails($reservation->table))}} --}}
                                    @foreach($reservation->tableDetails($reservation->table) as $selectedTable)
                                        {{$selectedTable->table_no}}-{{$selectedTable->floor->name ?? ""}}
                                        @if(!$loop->last),@endif
                                    @endforeach
                                </td>
                                <td class="">
                                    @can('reservations.edit')
                                    @if($reservation->is_cancelled || $reservation->party_confirm ==  2 || $reservation->party_confirm ==  3)
                                    {{ $reservation->party_confirm_status }}
                                    @else
                                    <select id="change_reservation_status" data-reservation-id={{$reservation->id}}>
                                        @foreach(reservationStatus() as $key => $status)
                                            @if($key != 2)
                                            <option value="{{$key}}" @if($reservation->party_confirm == $key) selected @endif>{{$status}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @endif
                                    @else
                                    {{ $reservation->party_confirm_status }}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @else
                    {{-- <tr>
                        <td colspan="6"><center style="color: red;"> Reservation is not available.</center></td>
                    </tr> --}}
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>
<script>
    $('#floormanagement').DataTable()
</script>