@extends('layouts.master')
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="main-heading">
                            <div class="col-8 col-md-8 col-lg-8">
                             <h4>Tax {{ trans('lang.session_date') }} </h4>
                            </div>
                            <div class="col-2 col-md-2 col-lg-2">
                             @can('tax-class-create')
                              <a href="javascript:void(0)" alt="addSession" class="showActionPop"
                              data-bs-toggle="modal">
                               {{ trans('lang.add') }} {{ trans('lang.session') }}
                              </a>
                             @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table id="tax_class" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup"></th>
                                    <th scope="rowgroup">{{ trans('lang.start_datetime') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.end_datetime') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.session_date') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.createAt') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.status') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.action') }}</th>
                                </tr>

                            </thead>
                            <tbody>
                                @if ($dataList)
                                    @foreach ($dataList as $kys => $vls)
                                        <tr>
                                            <td>{{ $kys + 1 }}</td>
                                            <td> {{ $vls->start_date }} </td>
                                            <td> {{ $vls->end_date }} </td>
                                            <td> {{ $vls->session }} </td>
                                            <td> {{ dateFormat($vls->created_at) }} {{ timeFormat($vls->created_at) }}
                                            </td>
                                            <td>
                                                @if ($vls->status == 0)
                                                    <div class="pending" id="statusID_{{ $vls->id }}">
                                                        <img src="assets/images/pending.png" alt="reserved"
                                                            class="me-2 statusImg_{{ $vls->id }}"
                                                            id="{{ $vls->id }}_{{ $vls->status }}">
                                                        <span
                                                            class="span_{{ $vls->id }}">{{ trans('lang.inactive') }}</span>
                                                    </div>
                                                @elseif($vls->status == 1)
                                                    <div class="reserved" id="statusID_{{ $vls->id }}">
                                                        <img src="assets/images/reserved.png" alt="reserved"
                                                            class="me-2 statusImg_{{ $vls->id }}"
                                                            id="{{ $vls->id }}_{{ $vls->status }}">
                                                        <span
                                                            class="span_{{ $vls->id }}">{{ trans('lang.active') }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="me-2">
                                                    @can('tax-class-edit')
                                                        <a aria-hidden="true" id="{{ $vls->id }}"
                                                            class="getSessionRecord custom-pointer"
                                                            edit-atr="{{ $vls->id }}">
                                                            <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                        </a>
                                                    @endcan
                                                </span>
                                                @can('tax-class-list')
                                                    <span class="me-2">

                                                        <a href="{{ route('tax.show', $vls->id) }}" id="{{ $vls->id }}"
                                                            data-bs-toggle="tooltip" title="View Tax Class" class="view"
                                                            edit-atr="{{ $vls->id }}">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </a>

                                                    </span>
                                                    <span
                                                        class="me-2 custom-pointer session_button sessionIcon-{{ $vls->id }}"
                                                        id="{{ $vls->id . '#' . $vls->status }}">
                                                        <img src="{{ $vls->status == 1 ? asset('images/pic_bulbon.gif') : asset('images/pic_bulboff.gif') }}"
                                                            alt="reserved" class="me-2 changeImgActive_{{ $vls->id }}"
                                                            id="session_icon_{{ $vls->id . '#' . $vls->status }}"
                                                            data-bs-toggle="tooltip"
                                                            title="{{ $vls->status == 0 ? 'Select Session' : 'Unselect Session' }}">
                                                    </span>
                                                @endcan
                                                {{-- <span class="me-2">
                           <a href="{{ route('tax.show', $vls->tax_session_id) }}">View </a>
                                    </span> --}}
                                                {{--                                                     
                           <span><a aria-hidden="true" href="#" data-bs-toggle="modal"
                              data-bs-target="#deleteRole" data-bs-whatever="@mdo">
                           <img src="assets/images/dustbin.png" alt="dustbin">
                           </a></span> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->
    <!--Modal Popup Add Session-->
    <div class="modal fade" id="addSession" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="viewPopBoxLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSessionLabel">{{ trans('lang.add') }} {{ trans('lang.session') }}</h5>
                    <button type="button" id="closeButton" class="btn-close closeButton" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'session', 'method' => 'POST', 'id' => 'actionFormOne', 'name' => 'actionFormOne']) !!}
                <div class="modal-body">
                    <div class="row">

                        <div class="mb-3 col-6 col-md-6 col-lg-6">
                            <label class="form-label" for="start_date">{{ trans('lang.start_datetime') }}</label>
                            {!! Form::date('start_date', null, [
                                'placeholder' => 'Start Session',
                                'class' => 'form-control max30Length session_date',
                                'id' => 'start_date',
                                'required',
                            ]) !!}
                            @error('start_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-6 col-md-6 col-lg-6">
                            <label class="form-label" for="end_date">{{ trans('lang.end_datetime') }}</label>
                            {!! Form::date('end_date', null, [
                                'placeholder' => 'End Session',
                                'class' => 'form-control max30Length session_date',
                                'id' => 'end_date',
                                'required',
                            ]) !!}
                            @error('end_date')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="mb-3 col-4 col-md-4 col-lg-4">
                            <label class="form-label" for="status">Status </label>
                            {{ Form::select('status', ['' => 'Select Status', 1 => 'Active', 0 => 'Inactive'], null, [
                                'class' => 'form-select',
                                'id' => 'status',
                            ]) }}
                    @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div> --}}
                    </div>
                    <div id="loading2" class="hideImg">
                        <img id="loading-image2" src="{{ asset('img/loader-images/05.svg') }}" alt="Loading..." />
                    </div>
                </div>
                <div class="modal-footer ">
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> --}}
                    <button type="button" id="submit_button" class="btn btn-primary submit_button">
                        {{ __(trans('lang.add')) }}
                    </button>
                    <button class="btn btn-primary button_loader" id="button_loader" style="display: none;" disabled>
                        <i class="fa fa-spinner fa-spin"></i>
                        <span class="btnLoaderName">
                            {{ __(trans('lang.add')) }}
                        </span>
                    </button>
                </div>
                {!! Form::close() !!}

            </div>

        </div>
    </div>
    <!--Modal Popup Add end-->
@endsection
