@extends('layouts.master')

@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>{{trans("lang.rolePremission")}}</h4>
                    </div>
                </div>
                @include('layouts.flash-msg')
                @include('layouts.validation-error-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <div class="row main-heading">                           
                            <div class="col-12 col-lg-3 col-md-3">
                                {!! Form::select('roles', userRole(), null, [
                                    'class' => 'form-select ',
                                    'required',
                                    'id' => 'rolePermission',
                                ]) !!}
                            </div>
                            <div class="col-12 col-lg-3 col-md-3">                               
                                <button type="button" name="{{count($permissions)}}" class="btn btn-primary float-end assignPermission" id="assignPermission">Save</button>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12 mt-3">
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th style="width: 25px;">#</th>
                                        <th scope="col" style="width: 40%;">{{trans("lang.name")}}</th>
                                        <th scope="col" colspan="5"><center>{{trans("lang.type")}}</center></th>
                                    </tr>
                                </thead>
                                <tbody id="permisison_list">
                                    @if($permissions)
                                        @foreach($permissions as $kys => $vls)
                                            @php
                                                $permissionArrary = explode(",", $vls->permisison);
                                            @endphp
                                            <tr>
                                                <td>{{$kys + 1}}</td>
                                                <td>{{$vls->group_name}}</td>
                                                <td><input class="form-check-input allPermission" name="allPermission[]" type="checkbox" value="{{$kys + 1}}" id="permission_ids-{{$kys + 1}}"> All</td>
                                                @if(count($permissionArrary) > 0) 
                                                    @foreach($permissionArrary as $ky => $vl)
                                                        @php
                                                            list($pname, $pid) = explode("|", $vl);
                                                        @endphp
                                                        <td><input class="form-check-input permissionCheck_{{$kys + 1}} permission" name="permission[]" type="checkbox" value="{{$pid}}" id="permission_ids_{{$pid}}"> {{ ucwords($pname) }} </td>
                                                    @endforeach
                                                @endif    
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6"><center style="color:red">No Data Found<center></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->
@endsection
