@extends('layouts.master') @section('content') <style></style>
<!--Main Section Start-->
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="main-heading">
                        <div class="col-9 col-md-9 col-lg-9">
                            <h4>{{ trans('lang.tm') }}
                                <span id='sessionYear'>
                                    {{ count($dataList) > 0 ? $dataList[0]->session : '' }}</span>
                            </h4>
                        </div>
                        <div class="col-3 col-md-3 col-lg-3">
                            @can('tax-create')
                            <a href="javascript:void(0)" alt="viewPopBox"
                                class="showActionPop">{{ trans('lang.add') }}{{ trans('lang.tm') }}</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12">
                <div class="main-content p-3">
                    @if (count($dataList) > 0 && $dataList[0]->tax_name)
                    @foreach ($dataList as $kys => $vls)
                    <hr />
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 mb-2"> #: {{ $kys + 1 }}
                            <b>Tax {{ trans('lang.name') }}:</b> {{ $vls->tax_name }}
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 mb-2">
                            <b>{{ trans('lang.status') }}:</b>
                            @if ($vls->status == 0)
                            <span class="pending">
                                <img src="{{ asset('assets/images/pending.png') }}" alt="reserved" class="me-2">
                                <span>Inactive</span>
                            </span>
                            @elseif($vls->status == 1)
                            <span class="reserved">
                                <img src="{{ asset('assets/images/reserved.png') }}" alt="reserved" class="me-2">
                                <span>Active</span>
                            </span>
                            @endif
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 mb-3 text-right">
                            @if (count($subTaxName) > 0 && array_key_exists($vls->tmId, $subTaxName))
                            <i class="fa fa-arrow-down custom-pointer collapseexample" aria-hidden="true"
                                data-bs-toggle="collapse" data-bs-target="#collapseExample{{ $vls->tmId }}"
                                id="collapse_example-{{ $vls->tmId }}" aria-expanded="false"
                                aria-controls="collapseExample{{ $vls->tmId }}"></i>
                            @else
                            <i class="fa fa-exclamation text-danger custom-pointer" data-bs-toggle="tooltip"
                                title="Enter Tax Percentage" aria-hidden="true"></i>
                            @endif
                        </div>
                    </div>
                    @if (count($subTaxName) > 0 && array_key_exists($vls->tmId, $subTaxName))
                    <div class="collapse" id="collapseExample{{ $vls->tmId }}">
                        <div class="card card-body">
                            <div class="main-content p-3">
                                <table id="floormanagement" class="display nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th scope="rowgroup"></th>
                                            <th scope="rowgroup">{{ trans('lang.tct') }}</th>
                                            <th scope="rowgroup">{{ trans('lang.pt') }}</th>
                                            <th scope="rowgroup">{{ trans('lang.status') }}</th>
                                            <th scope="rowgroup">{{ trans('lang.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($subTaxName) > 0 && array_key_exists($vls->tmId, $subTaxName))
                                        @foreach ($subTaxName[$vls->tmId] as $key => $val)
                                        <tr>
                                            <td></td>
                                            <td> {{ $val['sub_tax_name'] }} </td>
                                            <td> {{ $val['tax_percent'] }} %</td>
                                            <td>
                                                @if ($val['status'] == 0)
                                                <div class="pending">
                                                    <img src="{{ asset('assets/images/pending.png') }}" alt="reserved"
                                                        class="me-2">
                                                    <span>{{ trans('lang.inactive') }}</span>
                                                </div>
                                                @elseif($val['status'] == 1)
                                                <div class="reserved">
                                                    <img src="{{ asset('assets/images/reserved.png') }}" alt="reserved"
                                                        class="me-2">
                                                    <span>{{ trans('lang.active') }}</span>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                @can('tax-edit')
                                                <span class="me-2 custom-pointer">
                                                    <a aria-hidden="true" id="{{ $val['id'] }}"
                                                        class="editTaxPercentage" edit-atr="{{ $val['id'] }}"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                </span>
                                                @if($val['status'] == 1)
                                                 <span
                                                     class="me-2 custom-pointer defaultAction activeIcon-{{ $val['id'] }}"
                                                     id="{{ $val['id'] . '#' . $val['tax_master_id'] . '#' . $val['default'] }}">
                                                     <img src="{{ $val['default'] == '1' ? asset('images/pic_bulbon.gif') : asset('images/pic_bulboff.gif') }}"
                                                         alt="reserved" class="me-2 changeImgActive_{{ $val['id'] }}"
                                                         id="active_icon_{{ $val['id'] . '#' . $val['tax_master_id'] . '#' . $val['default'] }}"
                                                         data-bs-toggle="tooltip"
                                                         title="{{ $val['default'] == '1' ? 'Default Tax' : 'Undefault Tax' }}">
                                                 </span>
                                                @endif
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @else
                    <hr />
                    <center class="text-danger"> Please enter the tax class name <a
                            href="{{ route('tax.show', count($dataList) > 0 ? $dataList[0]->tsID : '') }}"> Click
                            Here.. </a>
                    </center>
                    @endif
                    <hr />
                    <div class="d-flex justify-content-center"> @php $sorting = ['record_id' => '']; @endphp {!!
                        $dataList->appends($sorting)->links() !!} </div>
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
                    <h5 class="modal-title" id="viewPopBoxLabel">{{ trans('lang.add') }} {{ trans('lang.stn') }}</h5>
                </div>
                <div class="col-5 col-md-5 col-lg-5">
                    <h5 class="modal-title">{{ trans('lang.session_date') }} - <span id="show_session">Y-m-d</span>
                    </h5>
                </div>
                <button type="button" id="closeButton" class="btn-close closeButton" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            {!! Form::open(['route' => 'percent.store', 'method' => 'POST', 'id' => 'actionForm', 'name' =>
            'actionForm']) !!} {!! Form::hidden('tax_sessions_id', count($dataList) > 0 ? $dataList[0]->tsID : '', [
            'id' => 'tax_sessions_id',
            'required',
            ]) !!}
            <span id="patch">
                <!-- update record with form-->
            </span>
            {!! Form::hidden('record_id', old('record_id'), [
            'id' => 'record_id',
            'required',
            ]) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-6 col-md-6 col-lg-6">
                        <label class="form-label" for="tax_master_id">{{ trans('lang.tcn') }}</label>
                        {{ Form::select('tax_master_id', getTax('', 'get'), old('tax_master_id'), [
                                'class' => 'form-select',
                                'placeholder' => 'Select Tax Class Name',
                                'id' => 'tax_master_id',
                            ]) }}
                        @error('tax_master_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-6 col-md-6 col-lg-6">
                        <label class="form-label" for="sub_tax_name">{{ trans('lang.stn') }}</label>
                        {!! Form::text('sub_tax_name', old('sub_tax_name'), [
                        'placeholder' => 'Sub Tax Class Name',
                        'class' => 'form-control max30Length',
                        'id' => 'sub_tax_name',
                        'required',
                        ]) !!} @error('sub_tax_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-6 col-md-6 col-lg-6">
                        <label class="form-label" for="tax_percent">{{ trans('lang.pt') }}</label>
                        {!! Form::text('tax_percent', old('tax_percent'), [
                        'placeholder' => 'Tax Percent %',
                        'class' => 'form-control max30Length',
                        'id' => 'tax_percent',
                        'required',
                        ]) !!}
                        @error('tax_percent')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-6 col-md-6 col-lg-6">
                        <label class="form-label" for="status">{{ trans('lang.status') }}</label>
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
                <button type="button" id="submit_button"
                    class="btn btn-primary submit_button">{{ __(trans('lang.add')) }}</button>
                <button class="btn btn-primary button_loader" id="button_loader" style="display: none;" disabled>
                    <i class="fa fa-spinner fa-spin"></i>
                    <span class="btnLoaderName">{{ __(trans('lang.add')) }}
                    </span>
                </button>
            </div> {!! Form::close() !!}
        </div>
    </div>
</div>
<!--Modal Popup Add end-->
<!--Modal Popup Edit start-->
<div class="modal fade" id="deleteRole" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRoleLabel">Delete Tax Management</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="delete-img">
                    <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                </div>
                <div class="modalcontent">
                    <h4 class="text-center mt-3">Are you Sure?</h4>
                    <p class="text-center mt-3">Do you really want to delete these records? This process cannot be
                        undone.</p>
                </div>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Delete</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Popup Edit end-->
@endsection
<script>
    @if(Session::has('errors'))
    var isValidError = true;
    @else
    var isValidError = false;
    @endif
</script>