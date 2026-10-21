<div class="modal-header">
    <b>
        {{ trans('lang.est') }}
    </b>
</div>
<div class="modal-body">
    <div class="row">       
        <div class="col-md-12 col-lg-12">           
            <div class="row">
                <div class="mb-3 col-md-12 col-lg-12">
                    <label class="form-label"
                        for="template">Choose your {{ trans('lang.template') }} {{ trans('lang.name') }}</label><br>
                    {!! Form::radio('template', 'email', [
                        'placeholder' => trans('lang.tamplate'),
                        'class' => 'form-control max30Length',
                        'id' => 'template',
                        'required',
                    ]) !!} Email &nbsp;&nbsp;&nbsp;&nbsp;
                    {!! Form::radio('template', 'sms', [
                        'placeholder' => trans('lang.tamplate'),
                        'class' => 'form-control max30Length',
                        'id' => 'template',
                        'required',
                    ]) !!} SMS
                    @error('template')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-md-12 col-lg-12">
                    <label class="form-label"
                        for="subject">{{ trans('lang.subject') }}</label><br>
                    {!! Form::text('subject', old('subject'), [
                        'placeholder' => trans('lang.subject'),
                        'class' => 'form-control max30Length',
                        'id' => 'subject',
                        'required',
                    ]) !!}
                    The email/SMS subject for admin notification.
                    @error('subject')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
               
                {{-- <div class="mb-3col-md-12 col-lg-12">
                    <label class="form-label"
                        for="message">{{ trans('lang.sms') }}</label><br>
                    {!! Form::textarea('message', old('message'), [
                        'placeholder' => trans('lang.sms'),
                        'class' => 'form-control max30Length',
                        'id' => 'message',
                        'rows' => 3,
                        'required',
                    ]) !!}
                    @error('message')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}
                <div class="mb-3col-md-12 col-lg-12">
                    <label class="form-label"
                        for="message">{{ trans('lang.sms') }}</label><br>
                        <div id="summernote"></div>
                    @error('message')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
        
    </div>
</div>
<div class="modal-footer">
    <center>
        <button type="button" id="submit_button"
            class="btn btn-primary submit_button">{{ __(trans('lang.save')) }}
        </button>
        <button class="btn btn-primary button_loader" id="button_loader"
            style="display: none;" disabled>
            <i class="fa fa-spinner fa-spin"></i>
            <span class="btnLoaderName">{{ __(trans('lang.save')) }}
            </span>
        </button>
    </center>
</div>
