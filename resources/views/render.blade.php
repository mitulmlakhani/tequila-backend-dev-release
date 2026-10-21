@extends('layouts.render')
@section('content')

    @if ($condition['action'] == 'searchPermissions')
        @php
            $permissionArrary = [];
            $hasPrmisn = [];
            $totalAssineds = 0;
        @endphp
        @if (count($permissions) > 0)          
            @foreach ($permissions as $key => $vls)
                @php
                    $permissionArrary = explode(',', $vls->permisison);
                    if($vls->hasPermisison) {
                        $hasPrmisn = explode(',', $vls->hasPermisison);
                    }
                        

                    $totalAssineds += count($hasPrmisn);
                @endphp
                <tr id="{{ (count($permissions) == ($key + 1)) ? $totalAssineds : '' }}" class="{{ (count($permissions) == ($key + 1)) ? 'assignedRolePermission' : '' }}">
                    <td>{{ $key + 1 }}</td>
                    <td>
                        {{ $vls->group_name }}
                    </td>
                    <td><input class="form-check-input allPermission" name="allPermission[]" type="checkbox"
                            value="{{ $key + 1 }}" id="permission_ids-{{ $key + 1 }}" {{ count(explode(',', $vls->hasPermisison)) == 4 ? 'checked' : '' }} > All</td>
                    @if (count($permissionArrary) > 0)
                        
                        @foreach($permissionArrary as $ky => $vl)
                            @php                                                            
                                [$pname, $pid] = explode('|', $vl);
                                $checked = '';
                                if (in_array($pid, $hasPrmisn)) {
                                    $checked = 'checked';
                                }
                            @endphp
                            <td><input class="form-check-input permissionCheck_{{ $key + 1 }} permission"
                                    name="permissionId[]" type="checkbox" value="{{ $pid }}"
                                    id="permission_ids_{{ $pid }}" {{ $checked }}> {{ ucwords($pname) }}</td>
                        @endforeach
                    @endif
                </tr>             
            @endforeach           
        @else
            <tr>
                <td colspan="7" id="0" class="blankData">
                    <center><b style="color:red;">Recourd not fount.</b></center>
                </td>
            </tr>
        @endif
    @endif
    @if ($condition['action'] == 'panelPage')

    @endif
@endsection
