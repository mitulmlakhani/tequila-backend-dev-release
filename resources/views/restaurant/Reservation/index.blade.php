@extends('layouts.master')
@section('title')
 Reservation
@endsection
@section('css')
    <style>
        button.btn {
            min-width: auto !important;
        }

        .textDown {
            margin-top: 14px !important;
            color: black;
        }

        .textcolor {
            color: black;
        }

        .input-group1 {
            width: auto !important;
        }

        .text-anchors {
            color: #0980B2;
        }

        .list-group-item.active {
            background-color: #0980B2;
            border-color: #0980B2;
        }

        .header-blue {
            background-color: #192c53;
            color: azure;
        }

        sub {
            bottom: -1.25em !important;
            font-size: 12px !important;
            float: right !important;
        }

        /* hide scrollbar but allow scrolling */
.overflow-auto-scrol {
  -ms-overflow-style: none; /* for Internet Explorer, Edge */
  scrollbar-width: none; /* for Firefox */
  overflow-y: scroll; 
}

.overflow-auto-scrol::-webkit-scrollbar {
  display: none; /* for Chrome, Safari, and Opera */
}

/* other styling */
.overflow-auto-scrol {
  /* border: solid 5px black; */
  /* border-radius: 5px; */
  height: 475px;
  /* padding: 10px; */
  /* width: 200px; */
}
</style>

@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid" style="margin-top: 3px;">
            <div class="row">
                <!-- Start Menu to reservation-->
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="modal-content">
                        <div class="modal-body">
                            <ul class="nav nav-pills nav-pills-primary  btn-group" role="tablist">
                                <li class="nav-item action_button">
                                    <a class="list-group-item list-group-item-action active" id="reservation-list"
                                        data-bs-toggle="list" href="#reservation" role="tab"
                                        aria-controls="reservation">Reservations</a>
                                </li>
                                <li class="nav-item action_button">
                                    <a class="list-group-item list-group-item-action" id="payments-list"
                                        data-bs-toggle="list" href="#payments" role="tab"
                                        aria-controls="payments">Payments</a>
                                </li>
                                {{-- <li class="nav-item action_button">
                                    <a class="list-group-item list-group-item-action" id="view-booking-list"
                                        data-bs-toggle="list" href="#view-booking" role="tab"
                                        aria-controls="view-booking">View Bookings</a>
                                </li> --}}
                                @can('reservations.list')
                                <li class="nav-item action_button">
                                    <a class="list-group-item list-group-item-action" id="restaurant-booking-list"
                                        data-bs-toggle="list" href="#restaurant-booking" role="tab"
                                        aria-controls="restaurant-booking">Restaurant Bookings</a>
                                </li>
                                @endcan
                                {{-- <li class="nav-item action_button">
                                    <a class="list-group-item list-group-item-action" id="template-list"
                                        data-bs-toggle="list" href="#template" role="tab"
                                        aria-controls="template">Email/SMS Template</a>
                                </li> --}}
                                @can('reservations.settings')
                                <li class="nav-item action_button">
                                    <a class="list-group-item list-group-item-action" id="setting-list"
                                        data-bs-toggle="list" href="#setting" role="tab"
                                        aria-controls="setting">Reservation Settings </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid" style="margin-top: 3px;">
            <div class="row">
                <!-- Start Menu to reservation-->
                @include('restaurant.Reservation.booking_left_list')
                <div class="col-md-12 col-lg-8" id="main_content_view">
                    <div class="modal-content">
                        <div class="tab-content" id="nav-tabContent">

                            <div class="tab-pane fade show active" id="reservation" role="tabpanel"
                                aria-labelledby="reservation-list">
                                @include('restaurant.Reservation.reservation')
                            </div>

                            <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-list">
                                @include('restaurant.Reservation.payment')
                            </div>

                            <div class="tab-pane fade" id="view-booking" role="tabpanel"
                                aria-labelledby="view-booking-list">
                                @include('restaurant.Reservation.view_booking')
                            </div>

                            <div class="tab-pane fade" id="restaurant-booking" role="tabpanel"
                                aria-labelledby="restaurant-booking-list">
                                @include('restaurant.Reservation.restaurant_booking')
                            </div>

                            <div class="tab-pane fade" id="template" role="tabpanel" aria-labelledby="template-list">
                                @include('restaurant.Reservation.template')
                            </div>

                            <div class="tab-pane fade" id="setting" role="tabpanel" aria-labelledby="setting-list">
                                @include('restaurant.Reservation.setting')
                            </div>

                        </div>
                    </div>
                </div>
            </div><br><br>
        </div>
    </div>
    <!--Main Section End-->
    <!-- Modal -->
<div class="modal fade" id="cancelBooking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cancel Reservation Booking</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="cancel_booking_form">
        <div class="modal-body">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-12 mb-3">
                <input type="hidden" value="" id="reservation_id">
                <label class="form-label" for="refund_type">Refund Type</label>
                <select class="form-select" name="refund_type" id="refund_type" required>
                    <option value="">Select Refund Type</option>
                    <option value="1">Full Refund </option>
                    <option value="2">Partial Refund </option>
                    <option value="3">No Refund </option>
                </select>
            </div>
            <div class="col-12 col-md-12 col-lg-12 mb-3 partial_refund_type_div" style="display: none">
                <label class="form-label" for="partial_refund_type">Partial Refund Type</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="partial_refund_type" value="percentage" id="partial_refund_type_percentage" checked>
                    <label class="form-check-label" for="partial_refund_type_percentage">
                        Percentage
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="partial_refund_type" value="fixed_amount" id="partial_refund_type_fixed_amount">
                    <label class="form-check-label" for="partial_refund_type_fixed_amount">
                        Fixed amount
                    </label>
                </div>
                {{-- <select class="form-select" name="partial_refund_type" id="partial_refund_type">
                    <option value="">Select Partial Refund Type</option>
                    <option value="percentage">Percentage </option>
                    <option value="fixed_amount">Fixed amount </option>
                </select> --}}
            </div>
            <div class="col-12 col-md-12 col-lg-12 partial_refund_type_div" style="display: none">
                <label class="form-label" for="partial_refund">Partial Refund</label>
                <div class="input-group">
                    <span class="input-group-text">%</span>
                    <input class="form-control percentageInput" name="partial_refund" id="partial_refund" placeholder="Enter percentage">
                </div>
            </div>
          </div>
          <div class="mt-3 refund-payment-amount"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger">Accept</button>
        </div>
        </form>
      </div>
    </div>
</div>
@endsection
@section('js')
<script>
    var getBookingListUrl = "{{route('booking-list')}}";
    var getCustomerByMobileUrl = "{{route('get-customer-by-mobile')}}";
    var reservationDetailUrl = "{{route('reservation-detail',['reservationId'=>':id'])}}";
    var paymentDepositUrl = "{{route('payDeposit')}}";
    var cancelBookingUrl = "{{route('cancelBooking')}}";
    var changeBookingStatusUrl = "{{route('reservationChangeStatus')}}";
    var reservationListFilterByDateUrl = "{{route('reservationListFilterByDate')}}";
    var reservationListFilterByStatusUrl = "{{route('reservationListFilterByStatus')}}";
    var changeDaysOpenSettingsUrl = "{{route('changeDaysOpenSettingsUrl')}}";
    var searchReservationUrl = "{{route('searchReservation')}}";
</script>
<script src="{{ asset('assets/js/reservation.js') }}"></script> 
@endsection
