<div class="modal-header">
    <b>Reservation Booking List</b>
    <div>
        {!! Form::date('search_booking', '', [
            'class' => 'max30Length',
            'id' => 'search_booking',
            'required',
        ]) !!}
    </div>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">{{ trans('lang.arrived') }}</th>
                        <th scope="col">{{ trans('lang.date') }}</th>
                        <th scope="col">{{ trans('lang.party') }}</th>
                        <th scope="col">{{ trans('lang.name') }}</th>
                        <th scope="col">{{ trans('lang.mobile') }}</th>
                        <th scope="col">{{ trans('lang.table') }}</th>
                        <th scope="col">{{ trans('lang.massage') }}</th>
                        <th scope="col">{{ trans('lang.status') }}</th>
                    </tr>
                </thead>
                <tbody id="content_booking_list">

                    @if (count($reservations) > 0)
                        @foreach ($reservations as $key => $val)
                            <tr>
                                <td scope="row">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input flexCheckDefault flexCheckDefault_{{ $val->rId }}"
                                            type="checkbox" value="{{ $val->party_confirm }}"
                                            id="{{ $val->rcId }}!{{ $val->rId }}" name="flexCheckDefault"
                                            {{ $val->party_confirm == 2 ? 'disabled' : '' }}>
                                    </div>
                                </td>
                                <td>{{ $val->date }} {{ $val->time }}</td>
                                <td>{{ $val->party }}</td>
                                <td scope="row">{{ $val->name }}</td>
                                <td>{{ $val->mobile }}</td>
                                <td>{{ $val->tableNumber }}</td>
                                <td>{{ $val->message }}</td>
                                <td class="status{{ $val->rId }}">{{ ticketStatus($val->party_confirm) }}</td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="8"><center style="color: red;"> Reservation is not available.</center></td>
                    </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>
