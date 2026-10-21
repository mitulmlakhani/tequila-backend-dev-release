@extends('layouts.master')
@section('title')
    Report Notifications
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                @can('report-notification.store')
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>Report Notifications</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('settings.report-notifications.save') }}" method="POST" enctype='multipart/form-data' id="report-notification-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" placeholder="Email" id="email" name="email" class="form-control" required value="{{ old('email','') }}">
                                @error('email')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
                @endcan

                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-heading">
                        <h4>Report Notifications Management</h4>
                    </div>
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">#</th>
                                    <th scope="rowgroup">Email</th>
                                    <th scope="rowgroup">Created by</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notifications as $notification)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $notification->email }}</td>
                                        <td>{{ $notification->createdBy->name ?? 'N/A' }}</td>
                                        <td>
                                            @can('report-notification.destroy')
                                            <button type="button" class="btn btn-danger" id="deleteItemTag" data-url="{{ route('settings.report-notifications.delete', $notification->id) }}" data-bs-toggle="modal" data-bs-target="#deleteItemTagModal">Delete</button>
                                            @endcan
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
    <div class="modal fade" id="deleteItemTagModal" tabindex="-1" aria-labelledby="deleteItemTagLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteItemTagLabel">Delete Item Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-img">
                        <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                    </div>
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you Sure?</h4>
                        <p class="text-center mt-3">Do you really want to delete this item type?<br>This process cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="delete-item-tag-form" method="POST">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal Popup end-->
@endsection

@section('js')
    <script>
        $(document).on('click', '#deleteItemTag', function () {
            var url = $(this).data('url');
            $('#delete-item-tag-form').attr('action', url);
        });

        $('#deleteItemTagModal').on('hidden.bs.modal', function () {
            $('#delete-item-tag-form').attr('action', '');
        });
    </script>
@endsection
