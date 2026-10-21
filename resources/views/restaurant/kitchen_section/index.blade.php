@extends('layouts.master')
@section('title')
    Locations
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>Add Location</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('kitchen_section.store') }}" method="POST" enctype='multipart/form-data' id="kitchen_section-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" placeholder="Name" id="name" name="name" class="form-control" required value="{{ old('name','') }}">
                                @error('name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea placeholder="Description" id="description" name="description" class="form-control">{{ old('description','') }}</textarea>
                                @error('description')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>In-active</option>
                                </select>
                                @error('status')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-heading">
                        <h4>Locations Management</h4>
                    </div>
                    <div class="main-content p-3">
                        <table id="kitchen_section_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $record->name }}</td>
                                        <td>{{ $record->description }}</td>
                                        <td>
                                            <div class="{{ $record->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $record->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                     class="me-2" alt="{{ $record->status ? 'active' : 'inactive' }}">
                                                <span>{{ $record->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="me-2">
                                                <a href="#" id="edit-kitchen_section" data-id="{{ $record->id }}" data-bs-toggle="modal"
                                                   data-bs-target="#edit-kitchen_section-modal" class="text-primary">
                                                    <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                </a>
                                            </span>
                                            <span>
                                                <a href="#" id="delete-kitchen_section" data-id="{{ $record->id }}" data-bs-toggle="modal"
                                                   data-bs-target="#delete-kitchen_section-modal" class="text-danger">
                                                    <img src="{{ asset('assets/images/dustbin.png') }}" alt="delete">
                                                </a>
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

    <!-- Delete Modal Popup -->
    <div class="modal fade" id="delete-kitchen_section-modal" tabindex="-1" aria-labelledby="deleteKitchenSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteKitchenSectionModalLabel">Delete Kitchen Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                        <h4 class="mt-3">Are you sure?</h4>
                        <p>All associations with devices will be deleted for this section.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="delete-kitchen_section-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal Popup End -->
@endsection

@section('js')
<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#kitchen_section_management').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search Locations",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: "&lt;",
                    next: "&gt;",
                },
            },
        });

        // Clear the form and reset the modal for adding a new record
        $(document).on('click', '#kitchen_section-add', function () {
            $('#kitchen_section-form').trigger('reset');
            $('#kitchen_section-form').attr('action', '{{ route("kitchen_section.store") }}');
            $('#kitchen_section-form').find('input[name="_method"]').remove(); // Remove any existing PUT method
            $('.modal-title').text('Add Kitcher section');
        });

        // Edit Kitchen section
        $(document).on('click', '#edit-kitchen_section', function () { // Changed to match the HTML ID
            var id = $(this).data('id');
            var url = '{{ route("kitchen_section.show", ":id") }}'.replace(':id', id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    if (response.status === 'success') {
                        $('#name').val(response.data.name);
                        $('#description').val(response.data.description);
                        $('#status').val(response.data.status);
                        $('#kitchen_section-form').attr('action', '{{ route("kitchen_section.update", ":id") }}'.replace(':id', id));
                        if (!$('#kitchen_section-form').find('input[name="_method"]').length) {
                            $('#kitchen_section-form').append('<input type="hidden" name="_method" value="PUT">');
                        }
                        $('.modal-title').text('Edit Kitchen section');
                        $('#kitchen_section-modal').modal('show');
                    } else {
                        alert('Failed to fetch kitchen section data.');
                    }
                },
                error: function () {
                    alert('Error occurred while fetching kitchen section data.');
                },
            });
        });

        // Delete kitchen_section
        $(document).on('click', '#delete-kitchen_section', function () { // Changed to match the HTML ID
            var id = $(this).data('id');
            var url = '{{ route("kitchen_section.destroy", ":id") }}'.replace(':id', id);
            $('#delete-kitchen_section-form').attr('action', url);
        });

        // Reset delete modal action on close
        $('#delete-kitchen_section-modal').on('hidden.bs.modal', function () {
            $('#delete-kitchen_section-form').attr('action', '');
        });
    });
</script>
@endsection
