<div class="payment-details-div">
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
                            <b>Location</b>
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
                        @can('reservations.payments')
                        <div class="col-md-12 col-lg-7">
                            <div class="input-group mb-3">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control total_pay_amt" id="total_pay_amt" name="total_pay_amt" aria-label="Dollar amount (with dot and two decimal places)" value="">
                                <input type="hidden" name="pay_details_id" class="pay_details_id" id="pay_details_id" value="">
                                <select class="form-select pay_type" id="pay_type">
                                    <option value="">Select Payment Type</option>
                                    @foreach(paytype() as $key => $payType)
                                        <option value="{{$key}}">{{$payType}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-lg-2">
                            <button type="button" class="btn btn-primary pay_deposit" style="width: 128px; float: right;" id="pay_deposit" disabled>Pay Deposit</button>
                        </div>
                        @endcan

                        @can('reservations.cancel')
                        <div class="col-md-12 col-lg-3 refaund_cancel">
                            <button type="button" class="btn btn-danger cancel_button" name="cancel_button" id="cancel_button" style="height: 40px; float: right;" disabled>Cancel</button>
                        </div>
                        @endcan
                    </div>
                </div>
                
            </div><br>
            <div class="col-md-12 col-lg-12">
                <table class="table table-striped table-hover caption-top">                
                    <thead>
                        <tr>
                            <th scope="col">Booking ID</th>
                            <th scope="col">Total AMT</th>
                            <th scope="col">Pay AMT</th>
                            <th scope="col">Due AMT</th>
                            <th scope="col">Date / Time</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="payCustomerDetails">
                        <tr>
                            <td colspan="6">
                                <center style="color: red;"> Reservation Payment is not available.</center>
                            </td>                        
                        </tr>                    
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>

