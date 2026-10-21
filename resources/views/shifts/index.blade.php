@extends('layouts.master')
@section('title')
    Shift Management
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Shifts</h4>
                        @can('shifts.store')
                            <a href="#" data-bs-toggle="modal" data-bs-target="#shift-add-modal" id="shift-add"
                        data-bs-whatever="@mdo">Add Shift</a>
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
                                    <th scope="rowgroup">Name</th>
                                    <th scope="rowgroup">Start Time</th>
                                    <th scope="rowgroup">End Time</th>
                                    <th scope="rowgroup">Created by</th>
                                    <th scope="rowgroup">Status</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shifts as $record)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $record->name }}</td>
                                        <!-- <td>{{ $record->start_time }}</td>
                                        <td>{{ $record->end_time }}</td> -->
                                        <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $record->start_time)->format('h:i A') }}</td> <!-- Format start_time to HH:MM AM/PM -->
                                        <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $record->end_time)->format('h:i A') }}</td> <!-- Format end_time to HH:MM AM/PM -->
                                        <td>{{ $record->createdBy->name }}</td>
                                        <td>
                                            <div class="{{ $record->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $record->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                     class="me-2" alt="{{ $record->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $record->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @can('shifts.update')
                                            <span class="me-2">
                                                <a href="#" id="shift-edit"
                                                   data-bs-toggle="modal" data-id="{{ $record->id }}"
                                                   data-bs-target="#shift-add-modal" data-bs-whatever="@mdo">
                                                    <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                </a>
                                            </span>
                                            @endcan
                                            @can('shifts.destroy')
                                            <span>
                                                <a href="#" data-bs-toggle="modal" id="deleteShift"
                                                   data-bs-target="#deleteShiftModal"
                                                   data-url="{{ route('shifts.destroy', ['id' => $record->id
                                                   ]) }}">
                                                    <img src="{{ asset('assets/images/dustbin.png') }}" alt="delete">
                                                </a>
                                            </span>
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

    <!-- Modal Popup Add/Edit start -->
    <div class="modal fade" id="shift-add-modal" tabindex="-1" aria-labelledby="addShiftLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addShiftLabel">Add Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="shift-form" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" placeholder="Name" id="name" name="name" class="form-control" required>
                                @error('name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="start_time">Start Time</label>
                                <input type="time" placeholder="Start Time" id="start_time" name="start_time" class="form-control" required>
                                @error('start_time')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="end_time">End Time</label>
                                <input type="time" placeholder="End Time" id="end_time" name="end_time" class="form-control" required>
                                @error('end_time')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">In-active</option>
                                </select>
                                @error('status')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Popup Add/Edit end -->

    <!-- Delete Modal Popup Start -->
    <div class="modal fade" id="deleteShiftModal" tabindex="-1" aria-labelledby="deleteShiftLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteShiftLabel">Delete Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-img">
                        <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                    </div>
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you sure?</h4>
                        <p class="text-center mt-3">Do you really want to delete this shift?<br>This process cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="delete-shift-form" method="POST">
                        @csrf
                        @method('GET')
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal Popup end -->
@endsection
@section('js')
<script>
    @if(Session::has('errors'))
        var isValidationError = true;
    @else
        var isValidationError = false;
    @endif

    var shiftCreateUrl = '{{ route('shifts.store') }}';
    var shiftUpdateUrl = '{{ route('shifts.update', ':id') }}';
    var shiftDetailUrl = '{{ route('shifts.show', ':id') }}';

    $(document).on('click', '#shift-add', function () {
        $('#shift-form').attr('action', shiftCreateUrl);
        $('#shift-form').find('input[name="_method"]').remove();
        $('#shift-form').trigger('reset');
        $('.modal-title').text('Add Shift');
    });

    $(document).on('click', '#shift-edit', function () {
        var id = $(this).data('id');
        var url = shiftDetailUrl.replace(':id', id);

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                if (response.status == 'success') {
                    $('#name').val(response.data.name);
                    $('#start_time').val(response.data.start_time);
                    $('#end_time').val(response.data.end_time);
                    $('#status').val(response.data.status ? 1 : 0);
                    $('.modal-title').text('Edit Shift');
                    $('#shift-add-modal').modal('show');

                    $('#shift-form').attr('action', shiftUpdateUrl.replace(':id', id));
                    if (!$('#shift-form').find('input[name="_method"]').length) {
                        $('#shift-form').append('<input type="hidden" name="_method" value="POST">');
                    }
                }
            }
        });
    });

    $(document).on('click', '#deleteShift', function () {
        var url = $(this).data('url');
        $('#delete-shift-form').attr('action', url);
    });

    $('#deleteShiftModal').on('hidden.bs.modal', function () {
        $('#delete-shift-form').attr('action', '');
    });

    $('#shift-add-modal').on('hidden.bs.modal', function () {
        if (isValidationError) {
            $('#shift-add-modal').modal('show');
            isValidationError = false;
        } else {
            $('#shift-form').trigger('reset');
            $('#shift-form').attr('action', shiftCreateUrl);
            $('#shift-form').find('input[name="_method"]').remove();
        }
    });
</script>

@endsection