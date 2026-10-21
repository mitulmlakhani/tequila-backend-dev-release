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
                                <h4>{{ trans('lang.quickbooks') }}</h4>
                            </div>
                            <div class="col-2 col-md-2 col-lg-2">
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="modal-content">
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route($isConnected ? 'quickbooks.disconnect' : 'quickbooks.connect') }}" class="btn {{ $isConnected ? 'btn-danger' : 'btn-primary' }}">
                                        @if($isConnected ?? 0)
                                        {{ trans('lang.disconnect_quickbooks') }}
                                        @else
                                        {{ trans('lang.connect_quickbooks') }}
                                        @endif
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!--Main Section End-->
@endsection
