<div class="modal-body">
    <form action="{{route('reservation-update',['reservationId'=>$reservation->id])}}" method="POST" id="reservation_save" name="actionForm1" data-type="edit">
    @csrf
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <b>Contact Details</b>
            <hr>
            <div class="row">
                <div class="mb-3 col-12 col-md-12 col-lg-12">
                    <label class="form-label" for="mobile">Mobile<span class="text-danger">*</span></label>
                    <input type="text" class="form-control max10Length" name="mobile" id="mobile" placeholder="Mobile" required onkeyup=formatPhone(this) value="{{$reservation->customer->mobile}}">
                    @error('mobile')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-12 col-md-12 col-lg-12">
                    <label class="form-label" for="names">Name (optional)</label>
                    <input type="text" class="form-control max30Length" name="names" id="names" placeholder="Name" value="{{$reservation->customer->name}}">
        
                    @error('names')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3 col-12 col-md-12 col-lg-12">
                    <label class="form-label" for="email_id">Email (optional)</label>
                    <input type="email" class="form-control max30Length" name="email_id" id="email_id" placeholder="email" value="{{$reservation->customer->email}}">
                    @error('email_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 col-12 col-md-12 col-lg-12">
                    <label class="form-label" for="message">Message (optional)</label>
                    <textarea class="form-control" name="message" id="message" rows="4">{{$reservation->message}}</textarea>
                    @error('message')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-6">
            <b>Booking Details</b>
            <hr>
            <div class="row">
                <div class="mb-3 col-12 col-md-12 col-lg-12">
                    <label class="form-label" for="date">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control max30Length" name="date" id="date" required value="{{$reservation->date}}">
                    <div class="text-danger">{{Auth::user()->restaurant->unavailabilityText()}}</div>
                    @error('date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 col-12 col-md-12 col-lg-12">
                    <label class="form-label" for="time">{{ trans('lang.time') }} <span
                            class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="time" id="time" required value="{{$reservation->time}}">
                    @error('time')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 col-12 col-md-12 col-lg-12">
                    <label class="form-label" for="party">{{ trans('lang.party') }} <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="party" id="party" placeholder="Party Size" required value="{{$reservation->party}}">
                    @error('party')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 col-12 col-md-12 col-lg-12">
                    <label class="form-label" for="table">Tables <span class="text-danger">*</span></label>
                    <select class="form-control selectpicker" name="table[]" id="table" multiple data-live-search="true" required>
                        @foreach ($tables as $table)
                            <option value="{{$table->id}}" @if(in_array($table->id,$selectedTablesArray)) selected @endif>{{$table->table_no}} - {{$table->floor->name ?? ""}}</option>
                        @endforeach
                    </select>
                    @error('table')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                {{-- <div class="mb-3 col-12 col-md-12 col-lg-12">
                    <div class="form-check">
                        <div class="row">
                            <div class="mb-3 col-md-6 col-lg-4">
                                <input class="form-check-input giftcard" type="checkbox" id="giftcard" name="giftcard">
                                <label class="form-check-label" for="giftcard">
                                    {{ trans('lang.giftcard') }}
                                </label>
                            </div>
                            <div class="mb-3 col-md-6 col-lg-8">
                                {!! Form::text('gift_card_number', old('gift_card_number'), [
                                    'placeholder' => trans('lang.giftcard') . ' ' . trans('lang.number'),
                                    'class' => 'form-control max30Length',
                                    'id' => 'gift_card_number',
                                    'required',
                                    'disabled',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    @error('gift_card_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}
            </div>
        </div>
        <div class="modal-footer" style="justify-content: center !important;">
            @can('reservations.edit')
            <button type="submit" id="submit_button1" class="btn btn-primary submit_button1">Update</button>
            <button class="btn btn-primary button_loader" id="button_loader" style="display: none;" disabled>
                <i class="fa fa-spinner fa-spin"></i>
                <span class="btnLoaderName"> 
                    {{ __(trans('lang.booking')) }} 
                </span>
            </button>
            <button class="btn btn-danger button_reset" id="button_reset" type="reset"> Reset </button>
            @endcan
        </div>
    </div>
    </form>
</div>