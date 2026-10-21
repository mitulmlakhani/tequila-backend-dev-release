@extends('layouts.master')
@section('title')
    Floors
@endsection
@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <!-- Left Side: Add/Edit Form -->
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4 id="form-title">Add Floor</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('floor-create') }}" method="POST" enctype="multipart/form-data" id="floor-form">
                            @csrf
                            <input type="hidden" id="floor_id" name="floor_id" value="">
                            <div class="mb-3">
                                <label class="form-label" for="name">Floor Name</label>
                                <input type="text" placeholder="Floor Name" id="name" name="name" class="form-control" required>
                                @error('name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="sequence">Sequence</label>
                                <input type="number" id="sequence" name="sequence" class="form-control" min="1" required>
                                @error('sequence')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="bg_color" class="form-label">Background Color</label>
                                <input type="color" class="form-control form-control-color" name="bg_color" id="bg_color" value="#563d7c">
                                @error('bg_color')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="status">Status </label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1">Active</option>
                                    <option value="0">In-active</option>
                                </select>
                                @error('status')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="bg_image">Background Image </label>
                                <input type="file" id="bg_image" name="bg_image" class="form-control">
                                @error('bg_image')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                                <img src="" class="list-image mt-2" id="edit_bg_image" style="display: none; width: 100px;">
                            </div>
                            <button type="submit" class="btn btn-primary" id="form-submit-btn">Add</button>
                        </form>
                    </div>
                </div>

                <!-- Right Side: Table -->
                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-heading">
                        <h4>Floor Management</h4>
                    </div>
                    <div class="main-content p-3">
                        <div class="table-responsive">
                            <table id="floor_management" class="table table-striped table-hover" style="line-height: 1;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Sequence</th>
                                        <th>Color</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($floors as $index => $floor)
                                        <tr>
                                            <td>{{ $floors->firstItem() + $index }}</td>
                                            <td>{{ $floor->name }}</td>
                                            <td>{{ $floor->sequence }}</td>
                                            <td>
                                                <span style="display: inline-block; width: 60px; height: 35px; background-color: {{ $floor->bg_color }}"></span>
                                            </td>
                                            <td>
                                                <a href="#" class="edit-floor" 
                                                data-id="{{ $floor->id }}" 
                                                data-name="{{ $floor->name }}" 
                                                data-sequence="{{ $floor->sequence }}" 
                                                data-bg_color="{{ $floor->bg_color }}" 
                                                data-status="{{ $floor->status }}" 
                                                data-image="{{ $floor->image_url }}">
                                                    <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                </a>
                                                <a href="#" class="delete-floor" data-url="{{ route('floor-delete', ['id' => $floor->id]) }}">
                                                    <img src="{{ asset('assets/images/dustbin.png') }}" alt="delete">
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Links -->
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $floors->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteFloorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Floor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <h4>Are you sure?</h4>
                    <p>This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="deleteFloorBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/floor.js') }}"></script>

    <script>
        $(document).ready(function () {
            // Handle edit button click
            $(document).on('click', '.edit-floor', function () {

                $("#floor_management").find('tr').each(function (i, tr) {
                    this.style.removeProperty('background-color');
                });

                var floorRow = $(this).closest('tr');
                floorRow.css('background-color', '#ffe');

                var floorId = $(this).data('id');
                var name = $(this).data('name');
                var sequence = $(this).data('sequence');
                var bgColor = $(this).data('bg_color');
                var status = $(this).data('status');
                var image = $(this).data('image');

                $('#floor_id').val(floorId);
                $('#name').val(name).focus();
                $('#sequence').val(sequence);
                $('#bg_color').val(bgColor);
                $('#status').val(status);
                $('#form-title').text('Edit Floor');
                $('#form-submit-btn').text('Update');

                if (image) {
                    $('#edit_bg_image').attr('src', image).show();
                } else {
                    $('#edit_bg_image').hide();
                }

                $('#floor-form').attr('action', '{{ route('floor-edit', ':id') }}'.replace(':id', floorId));
            });

            // Handle delete button click
            $(document).on('click', '.delete-floor', function () {
                var url = $(this).data('url');
                $('#deleteFloorBtn').attr('href', url);
                $('#deleteFloorModal').modal('show');
            });

            // Reset form on page load
            $('#floor-form').submit(function () {
                $(this).find('button[type="submit"]').prop('disabled', true);
            });

        });

        $(document).ready(function () {
            function fetchFloors(page) {
                $.ajax({
                    url: "/restaurant/floors?page=" + page,
                    success: function (data) {
                        $('#floor_management tbody').html($(data).find('#floor_management tbody').html());
                        $('.pagination').html($(data).find('.pagination').html());
                    }
                });
            }

            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetchFloors(page);
            });
        });
    </script>

@endsection
