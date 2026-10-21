@extends('layouts.master')
@section('title')
    Locations
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Location Management</h4>
                        @can('floor-create')
                            <a href="{{ route('branch-create') }}" >Add Location</a>
                        @endcan
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">#</th>
                                    {{-- <th scope="rowgroup">Name</th> --}}
                                    <th scope="rowgroup">Location Name</th>
                                    <th scope="rowgroup">Email </th>
                                    <th scope="rowgroup">Phone</th>
                                    <th scope="rowgroup">Created by </th>
                                    <th scope="rowgroup">Status</th>
                                    <th scope="rowgroup"> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($branches as $branch)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        {{-- <td>{{ $branch->name }}</td> --}}
                                        <td>{{ $branch->branch_name }}</td>
                                        <td>{{ $branch->email }}</td>
                                        <td>{{ $branch->phone }}</td>
                                        <td>{{ $branch->createdBy->name }}</td>
                                        <td>
                                            <div class="{{ $branch->status == "Active" ? 'reserved' : 'pending' }}">
                                                <img src="{{ $branch->status == "Active" ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                    class="me-2" alt="{{ $branch->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $branch->status == "Active" ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="me-2">
                                                <a aria-hidden="true"
                                                    href="{{ route('branch-edit', ['id' => $branch->id]) }}">
                                                    <img src="{{asset('assets/images/edit.png')}}" alt="edit">
                                                </a>
                                            </span>
                                            <span>
                                                <a aria-hidden="true" data-bs-toggle="modal" id="deletebranch"
                                                    data-bs-target="#deleteBranchModal"
                                                    data-url="{{ route('branch-delete', ['id' => $branch->id]) }}">
                                                    <img src="{{asset('assets/images/dustbin.png')}}" alt="dustbin">
                                                </a>
                                            </span>
                                            <span>
                                                <a href="{{ route('impersonate', $branch->adminUser()->id) }}"> | Login Location</a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->

    <!--Delete Modal Popup Start-->
    <div class="modal fade" id="deleteBranchModal" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-img">
                        <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                    </div>
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you Sure?</h4>
                        <p class="text-center mt-3">Do you really want to delete ? <br>This process cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-primary" id="deleteBranchtBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal Popup end-->
@endsection
@section('js')
<script>
    $(document).on('click', '#deletebranch', function(e) {
        var url = $(this).data('url');
        $('#deleteBranchtBtn').attr('href',url);
    });
</script>
@endsection
