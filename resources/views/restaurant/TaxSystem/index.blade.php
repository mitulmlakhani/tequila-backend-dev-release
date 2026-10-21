@extends('layouts.master')

@section('content')
<!--Main Section Start-->
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
             <div class="row">
              <div class="main-heading">
               <div class="col-10 col-md-10 col-lg-10">
                <h4>
                 Tax Class 
                 <span id='sessionYear'>
                  {{ count($dataList) > 0 ? $dataList[0]->session : '' }}
                 </span>
                </h4>
               </div>

               <div class="col-2 col-md-2 col-lg-2">
                @can('tax-class-create')
                 <a href="javascript:void(0)" alt="viewPopBox" class="showActionPop" data-bs-toggle="modal">
                  Add Tax Class
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
                             {{-- <th scope="rowgroup">Session Year</th> --}}
                             <th scope="rowgroup"> {{ trans('lang.name') }} </th>
                             <th scope="rowgroup">{{ trans('lang.status') }}</th>
                             <th scope="rowgroup">{{ trans('lang.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($dataList) > 0 && $dataList[0]->tax_name)
                            @foreach ($dataList as $kys => $vls)
                            <tr>
                                <td>{{ $kys + 1 }}</td>

                                {{-- <td> {{$vls->session}} </td> --}}
                                <td> {{ $vls->tax_name }} </td>
                                <td>
                                    @if ($vls->status == 0)
                                    <div class="pending">
                                        <img src="{{ asset('assets/images/pending.png') }}" alt="reserved" class="me-2">
                                        <span>{{ trans('lang.inactive') }}</span>
                                    </div>
                                    @elseif($vls->status == 1)
                                    <div class="reserved">
                                        <img src="{{ asset('assets/images/reserved.png') }}" alt="reserved"
                                            class="me-2">
                                        <span>{{ trans('lang.active') }}</span>
                                    </div>
                                    @endif

                                </td>

                                <td>

                                    <span class="me-2">
                                        @can('tax-class-edit')
                                        <a aria-hidden="true" id="{{ $vls->tmId }}" class="editTaxForm"
                                            edit-atr="{{ $vls->tmId }}">
                                            <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                        </a>
                                        @endcan
                                    </span>

                                    {{-- <span class="me-2">
                                                        <a href="{{ route('tax.show', $vls->tax_session_id) }}">View
                                    </a>
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

<!--Modal Popup Add start-->
<div class="modal fade" id="viewPopBox" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="viewPopBoxLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <div class="col-6 col-md-6 col-lg-6">
                    <h5 class="modal-title" id="viewPopBoxLabel">{{ trans('lang.add') }} {{ trans('lang.session') }}
                    </h5>
                </div>
                <div class="col-5 col-md-5 col-lg-5">
                    <h5 class="modal-title">Session Year - <span id="show_session">Y-m-d</span></h5>
                </div>

                <button type="button" id="closeButton" class="btn-close closeButton" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            {!! Form::open(['route' => 'tax.store', 'method' => 'POST', 'id' => 'actionForm', 'name' => 'actionForm'])
            !!}

            <span id="patch">
                <!-- update record with form-->
            </span>
            {!! Form::hidden('tax_sessions_id', count($dataList) > 0 && $dataList[0]->tsID ? $dataList[0]->tsID : '', [
            'id' => 'tax_sessions_id',
            'required',
            ]) !!}
            {!! Form::hidden('record_id', old('record_id'), [
            'id' => 'record_id',
            'required',
            ]) !!}
            <div class="modal-body">
                <div class="row">
                    {{-- <div class="mb-3 col-4 col-md-4 col-lg-4">
                            <label class="form-label" for="tax_sessions_id">{{ trans('lang.session_date') }}</label>
                    {!! Form::hidden('tax_sessions_id', null, [
                    'id' => 'tax_sessions_id',
                    'required',
                    ]) !!}

                    @error('tax_sessions_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div> --}}
                <div class="mb-3 col-6 col-md-6 col-lg-6">
                    <label class="form-label" for="tax_name">{{ trans('lang.name') }}</label>
                    {!! Form::text('tax_name', old('tax_name'), [
                    'placeholder' => 'Tax Class Name',
                    'class' => 'form-control max30Length',
                    'id' => 'tax_name',
                    'required',
                    ]) !!}

                    @error('tax_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-6 col-md-6 col-lg-6">
                    <label class="form-label" for="status">Status </label>

                    {{ Form::select('status', ['' => 'Select Status', 1 => 'Active', 0 => 'Inactive'], old('status'), [
                                'class' => 'form-select',
                                'id' => 'status',
                            ]) }}

                    @error('status')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="modal-footer ">
            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> --}}

            <button type="button" id="submit_button"
                class="btn btn-primary submit_button">{{ __(trans('lang.add')) }}</button>
            <button class="btn btn-primary button_loader" id="button_loader" style="display: none;" disabled>
                <i class="fa fa-spinner fa-spin"></i> <span class="btnLoaderName">{{ __(trans('lang.add')) }}
                </span>
            </button>
        </div>
        {!! Form::close() !!}
    </div>

</div>
</div>
<!--Modal Popup Add end-->


@endsection

<script>
    @if(Session::has('errors'))
    var isValidError = true;
    @else
    var isValidError = false;
    @endif
</script>