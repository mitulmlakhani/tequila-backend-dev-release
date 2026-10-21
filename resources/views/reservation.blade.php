@extends('layouts.render')
@section('content')

    @if (in_array($condition['action'], ['reservation-list', 'payments-list']))
        @if ($dataList)
            
        @else
            <li class="list-group-item">
                <center style="color: red;"> No data available in table. </center>
            </li>
        @endif
    @elseif ($condition['action'] == 'getBooking')
        @if (count($dataList) > 0)
            @foreach ($dataList as $key => $val)
                <tr>
                    <td scope="row">
                        <div class="form-check">
                            <input class="form-check-input flexCheckDefault flexCheckDefault_{{ $val->rId }}"
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
                <td colspan="8">
                    <center style="color: red;"> Reservation is not available on {{ $html['week_date'] }}.</center>
                </td>
            </tr>
        @endif
    @elseif($condition['action'] == 'pay_details')
        @if (count($dataList) > 0)
            @foreach ($dataList as $key => $val)
                <tr>
                    <td>{{ $val->booking_id }}</td>
                    <td>{{ $val->amount }}</td>
                    <td>{{ $val->final_amount }}</td>
                    <td>{{ $val->amount - $val->final_amount }}</td>
                    <td>{{ dateFormat($val->created_at) }} / {{ timeFormat($val->created_at) }}</td>
                    <td>{{ $val->pay_status == '2' ? 'Refund' : payStatus($val->pay_type) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">
                    <center style="color: red;"> Reservation Payment is not available.</center>
                </td>
            </tr>
        @endif
    @elseif($condition['action'] == 'payDetails')
        @if (count($html) > 0)
            @foreach ($html as $key => $val)
                <div class="modal-header">
                    <b>
                        Deposit Required: $10
                    </b>
                    <div id="payment_status"> {{ $val->party }} guests (Payment Pending) </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12 col-lg-1"></div>
                        <div class="col-md-12 col-lg-3">
                            <b>
                                {{ trans('lang.booking') }} {{ trans('lang.id') }}:
                            </b>
                        </div>
                        <div class="col-md-12 col-lg-8" id="payment_location"> {{ $val->booking_id }} </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-1"></div>
                        <div class="col-md-12 col-lg-3">
                            <b>
                                {{ trans('lang.party') }}:
                            </b>
                        </div>
                        <div class="col-md-12 col-lg-8" id="payment_party">{{ $val->party }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-1"></div>
                        <div class="col-md-12 col-lg-3">
                            <b>
                                {{ trans('lang.name') }}:
                            </b>
                        </div>
                        <div class="col-md-12 col-lg-8" id="payment_name">{{ $val->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-1"></div>
                        <div class="col-md-12 col-lg-3">
                            <b>
                                {{ trans('lang.mobile') }}:
                            </b>
                        </div>
                        <div class="col-md-12 col-lg-8" id="payment_mobile">{{ $val->mobile }}</div>
                    </div>
                    <div class="row">
                        <div class="col-1 col-md-1 col-lg-1"></div>
                        <div class="col-md-12 col-lg-3">
                            <b>
                                {{ trans('lang.sms') }}:
                            </b>
                        </div>
                        <div class="col-md-12 col-lg-8" id="payment_msg">{{ $val->message }}</div>
                    </div>
                    <div class="row">
                        <div class="col-1 col-md-1 col-lg-1"></div>
                        <div class="col-md-12 col-lg-3">
                            <b>
                                {{ trans('lang.table') }}:
                            </b>
                        </div>
                        <div class="col-md-12 col-lg-8" id="payment_table">{{ $val->tableNumber }}</div>
                    </div>
                    <div class="row">
                        <div class="col-1 col-md-1 col-lg-1"></div>
                        <div class="col-md-12 col-lg-3">
                            <b>
                                {{ trans('lang.date') . '/' . trans('lang.time') }}:
                            </b>
                        </div>
                        <div class="col-md-12 col-lg-8" id="payment_time">{{ get_day_name($val->date) }}
                            {{ dateFormat($val->date) }} {{ timeFormat($val->time) }}</div>
                    </div>
                </div>
                <br>
                <div class="text-left">
                    <div class="row">
                        @if ($val->status == 1)
                            <div class="col-md-12 col-lg-3 refaund_cancel">
                                <button type="button" class="btn btn-danger cancel_button" name="cancel_button"
                                    id="cancel_button" style="height: 40px; float: right;">Cancel</button>
                            </div>
                            <div class="col-md-12 col-lg-2">
                                <button type="button" class="btn btn-primary pay_deposit"
                                    style="width: 128px; float: right;" id="pay_deposit">Pay Deposit</button>
                            </div>
                            <div class="col-md-12 col-lg-7">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control total_pay_amt" id="total_pay_amt"
                                        name="total_pay_amt" aria-label="Dollar amount (with dot and two decimal places)"
                                        value="">
                                    <input type="hidden" name="pay_details_id" class="pay_details_id" id="pay_details_id"
                                        value="{{ $val->rcId }}!{{ $val->rId }}">

                                    {{ Form::select('pay_type', payStatus(null, null, 'getAll'), null, [
                                        'class' => 'form-select pay_type',
                                        'id' => 'pay_type',
                                    ]) }}
                                </div>
                            </div>
                        @else
                            <div class="col-md-12 col-lg-12">
                                <center style="color: red;">
                                    This ticket has been deleted.
                                </center>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="modal-header">
                <b>
                    Deposit Required: $<span id="totalAmount">...</span>
                </b>
                <div id="payment_status"> ... guests (Payment Pending) </div>
            </div>
            <div class="card">
                <div class="row">
                    <div class="col-md-12 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.booking') }} {{ trans('lang.id') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_location">...</div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.party') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_party">...</div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.name') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_name">...</div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.mobile') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_mobile">...</div>
                </div>
                <div class="row">
                    <div class="col-1 col-md-1 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.sms') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_msg">...</div>
                </div>
                <div class="row">
                    <div class="col-1 col-md-1 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.table') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_table">...</div>
                </div>
                <div class="row">
                    <div class="col-1 col-md-1 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.date') . '/' . trans('lang.time') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_time">...</div>
                </div>
            </div>
            <br>
            <div class="text-left">

                <div class="row">
                    <div class="col-md-12 col-lg-3 refaund_cancel">
                        <button type="button" class="btn btn-danger cancel_button" name="cancel_button"
                            id="cancel_button" style="height: 40px; float: right;" disabled>Cancel
                        </button>
                    </div>
                    <div class="col-md-12 col-lg-2">
                        <button type="button" class="btn btn-primary pay_deposit" style="width: 128px; float: right;" id="pay_deposit" disabled>Pay Deposit</button>
                    </div>
                    <div class="col-md-12 col-lg-7">
                        <div class="input-group mb-3">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control total_pay_amt" id="total_pay_amt"
                                name="total_pay_amt" aria-label="Dollar amount (with dot and two decimal places)"
                                value="">
                            <input type="hidden" name="pay_details_id" class="pay_details_id" id="pay_details_id"
                                value="">

                            {{ Form::select('pay_type', payStatus(null, null, 'getAll'), null, [
                                'class' => 'form-select pay_type',
                                'id' => 'pay_type',
                            ]) }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @endif


@endsection
