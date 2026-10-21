<div class="modal-header header-blue" style="display: flow;">
    <h5>
        Booking Schedule Option
    </h5>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 col-lg-1">&nbsp;</div>
        <div class="col-md-12 col-lg-3">
            Schedule
        </div>
        <div class="col-md-12 col-lg-7">
            Choose what times each week your restaurant is available to book reservations.
            <div class="modal-header">
                <div>
                    Days of the Week
                    <br><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input days_open" type="checkbox" id="1" value="1" @if(empty($reservationSetting)) checked @elseif(in_array(1,$reservationSetting['days_open'])) checked @endif>
                        <label class="form-check-label" for="1">Mon</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input days_open" type="checkbox" id="2" value="2" @if(empty($reservationSetting)) checked @elseif(in_array(2,$reservationSetting['days_open'])) checked @endif>
                        <label class="form-check-label" for="2">Tus</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input days_open" type="checkbox" id="3" value="3" @if(empty($reservationSetting)) checked @elseif(in_array(3,$reservationSetting['days_open'])) checked @endif>
                        <label class="form-check-label" for="3">Wed</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input days_open" type="checkbox" id="4" value="4" @if(empty($reservationSetting)) checked @elseif(in_array(4,$reservationSetting['days_open'])) checked @endif>
                        <label class="form-check-label" for="4">Thr</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input days_open" type="checkbox" id="5" value="5" @if(empty($reservationSetting)) checked @elseif(in_array(5,$reservationSetting['days_open'])) checked @endif>
                        <label class="form-check-label" for="5">Fri</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input days_open" type="checkbox" id="6" value="6" @if(empty($reservationSetting)) checked @elseif(in_array(6,$reservationSetting['days_open'])) checked @endif>
                        <label class="form-check-label" for="6">Sat</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input days_open" type="checkbox" id="7" value="7" @if(empty($reservationSetting)) checked @elseif(in_array(7,$reservationSetting['days_open'])) checked @endif>
                        <label class="form-check-label" for="7">Sun</label>
                    </div>


                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal-footer d-none">
    <center>
        <button type="button" id="submit_button" class="btn btn-primary submit_button">{{ __(trans('lang.booking')) }}
        </button>
        <button class="btn btn-primary button_loader" id="button_loader" style="display: none;" disabled>
            <i class="fa fa-spinner fa-spin"></i>
            <span class="btnLoaderName"> {{ __(trans('lang.booking')) }} </span>
        </button>
    </center>
</div>
