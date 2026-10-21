<div class="modal-header">
    <b>Reservation Payment</b>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 col-lg-12" id="ticketDetails">
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
                        <b>{{ trans('lang.booking') }} {{ trans('lang.id') }}</b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_location">{{$reservation->booking_id}}</div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.party') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_party">{{$reservation->party}}</div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.name') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_name">{{$reservation->customer->name}}</div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.mobile') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_mobile">{{ $reservation->customer->mobile}}</div>
                </div>
                <div class="row">
                    <div class="col-1 col-md-1 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.sms') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_msg">{{$reservation->message}}</div>
                </div>
                <div class="row">
                    <div class="col-1 col-md-1 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.table') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_table">
                        @foreach($selectedTables as $selectedTable)
                            {{$selectedTable->table_no}}-{{$selectedTable->floor->name ?? ""}}
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-1 col-md-1 col-lg-1"></div>
                    <div class="col-md-12 col-lg-3">
                        <b>
                            {{ trans('lang.date') . '/' . trans('lang.time') }}:
                        </b>
                    </div>
                    <div class="col-md-12 col-lg-8" id="payment_time">{{ get_day_name($reservation->date) }}
                        {{ dateFormat($reservation->date) }} {{ timeFormat($reservation->time) }}</div>
                </div>
            </div>
            <br>
            <div class="text-left">

                <div class="row">
                    
                    @if(!$reservation->is_cancelled)
                    @can('reservations.payments')
                    <div class="col-md-12 col-lg-7">
                        <div class="input-group mb-3">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control total_pay_amt" id="amount" name="total_pay_amt" aria-label="Dollar amount (with dot and two decimal places)" value="">
                            <input type="hidden" name="pay_details_id" class="pay_details_id" id="reservation_id" value="{{$reservation->id}}">
                            <select class="form-select pay_type" id="pay_type">
                                <option value="">Select Payment Type</option>
                                @foreach(paytype() as $key => $payType)
                                    <option value="{{$key}}">{{$payType}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-12 col-lg-2">
                        <button type="button" class="btn btn-primary pay_deposit" style="width: 128px; float: right;" id="pay_deposit">Pay Deposit</button>
                    </div>
                    @endcan
                    @endif
                    @if($reservation->is_cancelled)
                    <div class="col-md-12 col-lg-5 offset-lg-5">
                        <span> <i>Total Amount:</i> <b>{{currencyFormat($reservation->cancel_detail->amount)}}</b> | <i>Refund Amount:</i> <b>{{currencyFormat($reservation->cancel_detail->refund_amount)}}</b></span>
                    </div>
                    <div class="col-md-12 col-lg-2">
                        <button type="button" class="btn btn-danger" id="cancelled_booking" style="height: 40px; float: right;" data-total-amount="{{$reservation->cancel_detail->amount}}" data-refund-type="{{$reservation->cancel_detail->refund_pay}}" data-refund-amount="{{$reservation->cancel_detail->refund_amount}}" disabled>Cancelled</button>
                    </div>
                    @else
                    @can('reservations.cancel')
                    <div class="col-md-12 col-lg-3 refaund_cancel">
                        <button type="button" class="btn btn-danger cancel_button" name="cancel_button" id="cancel_booking" style="height: 40px; float: right;" data-reservation-id="{{$reservation->id}}" data-is-payments="{{count($payments)}}" data-total-payment="{{$reservation->total_payment}}">Cancel</button>
                    </div>
                    @endcan
                    @endif
                </div>
            </div>
            
        </div><br>
        <div class="col-md-12 col-lg-12">
            <table class="table table-striped table-hover caption-top">                
                <thead>
                    <tr>
                        <th scope="col">Booking Id</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Pay AMT</th>
                        <th scope="col">Due AMT</th>
                        <th scope="col">Date / Time</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="payCustomerDetails">
                    @if(count($payments) > 0)
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->reservation->booking_id }}</td>
                            <td>{{ $payment->amount }}</td>
                            <td>{{ $payment->final_amount }}</td>
                            <td>{{ $payment->amount - $payment->final_amount }}</td>
                            <td>{{ dateFormat($payment->created_at) }} / {{ timeFormat($payment->created_at) }}</td>
                            <td>{{ $payment->pay_status == '2' ? 'Refund' : payStatus($payment->pay_type) }}</td>
                        </tr>
                    @endforeach  
                    @else
                    <tr>
                        <td colspan="6">
                            <center style="color: red;"> Reservation Payment is not available.</center>
                        </td>                        
                    </tr>  
                    @endif                 
                </tbody>
            </table>
        </div>


    </div>
</div>


