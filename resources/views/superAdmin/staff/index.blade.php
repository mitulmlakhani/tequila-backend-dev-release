@extends('layouts.web')
@section('title', "Staff Management")
@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                   
                    <div class="row align-items-center"> 
                        <div class="col-lg-3 col-md-3 col-sm-3 col-3 mb-2"><b> {{trans("lang.staff")}} {{trans("lang.mnge")}} </b></div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-5 mb-2"></div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-4 mb-2">
                            <a class="btn btn-primary float-right dg-add-btn" href="{{ route('{role}.staff.create', ['role' => $routeUrl]) }}">{{trans("lang.addStaff")}}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                    </div>
                    <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th class="disabled-sorting">#</th>
                          <th>{{trans("lang.name")}}</th>
                          <th>{{trans("lang.mobile")}}</th>
                          <th>{{trans("lang.email")}}</th>
                          <th>{{trans("lang.location")}}</th>
                          <th>{{trans("lang.createAt")}}</th>
                          <th class="disabled-sorting">{{trans("lang.action")}}</th>
                        </tr>
                      </thead>
                    <tbody>
                    @if($dataList)
                @foreach($dataList as $key => $vls)
                  <tr>                
                    <td >{{ $key + 1 }}</td>
                    <td>
                      {{$vls->fullname}}<br>
                      <span class="badge badge-pill {{ $vls->colourlabel }} badges"><b>{{$vls->roleName}}</b></span>
                    </td>
                    <td>{{$vls->phone_number}}</td>
                    <td>{{$vls->email}}</td>
                    <td>
                      @if($vls->city) 
                        {{$vls->city}} <br>{{$vls->province}} ({{$vls->postal_code}}) 
                      @endif
                    </td>
                    <td>{{$vls->created_at}}</td>
                    <td>
                    @if($vls->role_id != 1)
                      
                        <a draggable="false" data-tooltip="Edit" id="{{$vls->id}}" href="{{ route('{role}.staff.edit', ['role' => $routeUrl, 'staff' => $vls->id]) }}" class="btn btn-success btn-icon btn-sm linkbtnd userRemove{{$vls->id}}_{{$vls->status}} " style="{{$vls->status ? '' : 'display: none;'}}">
                          <i class="fa fa-edit"></i>
                        </a>
                      
                      @if(!$vls->password)
                        <a draggable="false" data-tooltip="Resend Verification Email" class="btn btn-danger btn-icon btn-sm linkbtnd invitation user  user_remove userRemove{{$vls->id}}_{{$vls->status}} reSendMail" id="{{$vls->id}}_{{$vls->status}}" href="javascript:void(0)" style="{{$vls->status ? '' : 'display: none; float: left;'}}" >
                          <i class="fa fa-paper-plane"></i>
                        </a>
                      @endif
                      <a class="btn btn-icon btn-sm {{$vls->status ? 'btn-info':'btn-danger'}} btn-xs {{$vls->status ? 'del':''}} staffBtn active_inactive block_{{$vls->id}}_{{$vls->status}} linkbtnd" id="{{$vls->id}}_{{$vls->status}}" draggable="false" data-tooltip="{{$vls->status ? 'Block':'Unblock'}}" href="javascript:void(0)"><i class="nc-icon {{$vls->status ? 'nc-lock-circle-open':'nc-lock-circle-open'}} "></i></a>

                      <!-- <a class="btn btn-icon btn-sm btn-danger btn-xs  user_remove userRemove{{$vls->id}}_{{$vls->status}} linkbtnd " style="{{$vls->status ? 'display: none;' : ''}}" id="{{$vls->id}}_{{$vls->status}}" draggable="false" data-tooltip="Remove" href="javascript:void(0)">
                      <i class='fa fa-trash-o' style='color: white'></i>
                      </a> -->
                    @endif
                    </td>                  
                  </tr> 
                @endforeach
              @endif 
                        </tbody>
                    </table>
                </div><!-- end content-->
            </div><!--  end card  -->
        </div> <!-- end col-md-12 -->
    </div> <!-- end row -->
</div>


@endsection