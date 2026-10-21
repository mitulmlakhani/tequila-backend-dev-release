@extends('layouts.master')
@section('title')
    Role Permissions
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>{{ $panel }} Management</h4>
                    </div>
                </div>
                @include('layouts.flash-msg')
                @include('layouts.validation-error-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        {!! Form::open(['route' => $base_route . '.store', 'method' => 'POST', "id"=>"update-permissions"]) !!}
                        <div class="row main-heading">
                            <div class="col-12 col-lg-4 col-md-4">
                                {!! Form::select('guard_name', $data['applicationtype'], null, [
                                    'class' => 'form-select ',
                                    'required',
                                    'id' => 'loadrole',
                                ]) !!}
                            </div>
                            <div class="col-12 col-lg-3 col-md-3">
                                {!! Form::select('roles', $data['role'], null, [
                                    'class' => 'form-select ',
                                    'required',
                                    'id' => 'loadpermission',
                                ]) !!}
                            </div>
                            <div class="col-12 col-lg-3 col-md-3">
                                @can($permission_prefix . '-store')
                                    <button type="submit" class="btn btn-primary float-end">Save</button>
                                @endcan
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12 mt-3">
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th scope="col">Permission Group</th>
                                        <th scope="col" colspan="5">Permissions</th>
                                    </tr>
                                </thead>
                                <tbody id="permisison_list">
                                    <tr>
                                        <td colspan="5">No Data Found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->
@endsection
@section('js')
    <script>
        var loadRoleUrl = '{{ route('load-role') }}';
        var loadRolePermissionUrl = '{{ route('load-role-permission') }}';
    </script>
    <script src="{{ asset('assets/js/role-permission.js') }}"></script> 
@endsection
