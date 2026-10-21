@extends('layouts.master') @section('content')
    <style></style>
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">

                <div class="col-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="main-heading">
                            <div class="col-10 col-md-10 col-lg-10">
                                <h4>{{ trans('lang.tip') }} {{ trans('lang.percentage') }}</h4>
                            </div>
                            <div class="col-2 col-md-2 col-lg-2">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-12">
                    {{-- <div class="modal-dialog modal-lg modal-dialog-centered"> --}}
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">  
                                    <label class="form-label" for="tax_percent">
                                        <b>{{ trans('lang.set') }} {{ trans('lang.tip') }} {{ trans('lang.suggestions') }} %</b>
                                    </label> 
                                    <div class="mb-3 col-1 col-md-1 col-lg-1">
                                        {!! Form::hidden('record_id', $tipList ? $tipList->id : '', [
                                            'id' => 'record_id',
                                            'required',
                                        ]) !!}
                                        &nbsp;
                                    </div>                                
                                    <div class="mb-3 col-2 col-md-2 col-lg-2">
                                        {!! Form::text('set_tip_one', $tipList ? $tipList->set_tip_one : '', [
                                            'placeholder' => trans('lang.tippOne'),
                                            'class' => 'form-control max30Length set_tip_one tip-percentage',
                                            'id' => 'tax_percent',
                                            'required',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-2 col-md-2 col-lg-2">
                                        {!! Form::text('set_tip_two', $tipList ? $tipList->set_tip_two : '', [
                                            'placeholder' => trans('lang.tippTwo'),
                                            'class' => 'form-control max30Length set_tip_two tip-percentage',
                                            'id' => 'tax_percent',
                                            'required',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-2 col-md-2 col-lg-2">
                                        {!! Form::text('set_tip_three', $tipList ? $tipList->set_tip_three : '', [
                                            'placeholder' => trans('lang.tippThree'),
                                            'class' => 'form-control max30Length set_tip_three tip-percentage',
                                            'id' => 'tax_percent',
                                            'required',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-2 col-md-2 col-lg-2">
                                        {!! Form::text('set_tip_four', $tipList ? $tipList->set_tip_four : '', [
                                            'placeholder' => trans('lang.tippFour'),
                                            'class' => 'form-control max30Length set_tip_four tip-percentage',
                                            'id' => 'tax_percent',
                                            'required',
                                        ]) !!}
                                    </div>
                                    <div class="mb-3 col-3 col-md-3 col-lg-3">
                                        &nbsp;
                                    </div> 
                                </div>
                                <div class="row">  
                                     
                                    <div class="mb-3 col-1 col-md-1 col-lg-1">
                                        &nbsp;
                                    </div>                                
                                    <div class="mb-3 col-3 col-md-3 col-lg-3">
                                        <label class="form-label" for="tax_percent">
                                            {{ trans('lang.tip') }} {{ trans('lang.suggestions') }} {{ trans('lang.label') }} 
                                        </label>
                                    </div>
                                    <div class="mb-3 col-4 col-md-4 col-lg-4">
                                        {!! Form::text('tip_suggestion_label', $tipList ?$tipList->tip_suggestion_label : '', [
                                            'placeholder' => trans('lang.tip') ." ". trans('lang.suggestions') ." ". trans('lang.label'),
                                            'class' => 'form-control max30Length tip_suggestion_label tip-percentage',
                                            'id' => 'tip_suggestion_label',
                                            'required',
                                        ]) !!}
                                    </div>
                                    
                                    <div class="mb-3 col-4 col-md-4 col-lg-4">
                                        &nbsp;
                                    </div> 
                                </div>
                            </div>
                        </div>
                    {{-- </div> --}}







                </div>






            </div>
        </div>
    </div>
    <!--Main Section End-->
@endsection
<script>
    @if (Session::has('errors'))
        var isValidError = true;
    @else
        var isValidError = false;
    @endif
</script>
