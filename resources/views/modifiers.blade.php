@extends('layouts.master')
@section('title')
    Modifiers
@endsection
@section('content')
<style>
    table td {
        word-wrap: break-word;
        white-space: normal;
    }

    /* Apply only to the group column */
    .group-column {
        max-width: 200px; /* Adjust column width as needed */
        word-wrap: break-word;
        white-space: normal;
    }

    .select2-dropdown {
        zoom: 70%;
    }

    .select2-dropdown {
        top: -16px;
    }

    .select2-results__options {
        max-height: 400px !important;
    }
</style>

    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>Add Modifier</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('modifier-create') }}" method="POST" enctype='multipart/form-data' id="modifier-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Modifier name </label>
                                <input type="text" placeholder="Modifier Name" id="name" name="name" class="form-control max30Length" required value="{{ old('name', '') }}" autofocus />
                                @error('name')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="price">Price</label>
                                <input type="text" id="price" name="price" class="form-control" required value="{{ old('price', '0.00') }}" >

                                @error('price')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="modifier_groups">Select Modifier Groups</label>
                                <select class="form-select" id="modifier_groups" name="modifier_groups[]" multiple="multiple" required>
                                    @foreach($modifierGroups as $group)
                                        <option value="{{ $group->id }}" 
                                            {{ in_array($group->id, $selectedGroups ?? []) ? 'selected' : '' }}>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="image">Modifier Image </label>
                                <input type="file" id="image" name="image" class="form-control">
                                @error('image')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <img src="" class="list-image" id="edit_modifier_icon">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="color">Color</label>
                                <input type="color" id="color" name="color" class="form-control" value="{{ old('color', '#5C5E66') }}">
                                @include('elements.colors-div')
                                @error('color')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="font_color">Font Color</label>
                                <input type="color" id="font_color" name="font_color" class="form-control" value="{{ old('font_color', '#ffffff') }}">
                                @include('elements.colors-div')
                                @error('font_color')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @can('modifier-create')
                            <button type="submit" class="btn btn-primary">Save</button>
                            @endcan
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-heading">
                        <h4>Modifier Management</h4>
                    </div>
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup"></th>
                                    <th scope="rowgroup">Modifier Name</th>
                                    <th scope="rowgroup">Price</th>
                                    <th scope="rowgroup">Color</th>
                                    <th scope="rowgroup">Font Color</th>
                                    <th scope="rowgroup">Modifier Groups</th>
                                    <th scope="rowgroup">Image</th>
                                    <th scope="rowgroup">Created by</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modifiers as $modifier)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $modifier->name }}</td>
                                        <td>{{ $modifier->price }}</td>
                                        <td><span style="display: inline-block; width: 60px; height: 35px; background-color: {{ $modifier->color }}"></span></td>
                                        <td><span style="display: inline-block; width: 60px; height: 35px; background-color: {{ $modifier->font_color }}"></span></td>
                                        <td style="max-width: 200px; word-wrap: break-word; white-space: normal;">
                                            @if($modifier->modifierGroups)
                                                {{ $modifier->modifierGroups->pluck('name')->join(', ') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <!-- <a href="{{ asset('images/modifiers/' . $modifier->image) }}" target="_blank"> -->
                                                <img src="{{ asset($modifier->image_url) }}" class="list-image">
                                            <!-- </a> -->
                                        </td>
                                        <td>{{ $modifier->createdBy->name ?? "" }}</td>
                                        <td>
                                            <span class="me-2">
                                                @can('modifier-edit')
                                                    <a aria-hidden="true" href="#" id="modifier-edit" data-id="{{ $modifier->id }}">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <span>
                                                @can('modifier-delete')
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteModifier" data-bs-target="#deleteModifierModal" data-url="{{ route('modifier-delete', ['id' => $modifier->id]) }}">
                                                        <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
                                                    </a>
                                                @endcan
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
    <div class="modal fade" id="deleteModifierModal" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Modifier</h5>
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
                    <a href="#" class="btn btn-primary" id="deleteModifierBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal Popup end-->
@endsection
@section('js')
    <script>
        @if(Session::has('errors'))
            var isValidationError = true;
        @else
            var isValidationError = false;
        @endif

        var modifierCreateUrl = '{{ route('modifier-create') }}';
        var modifierUpdateUrl = '{{ route('modifier-edit', ':id') }}';
        var modifierDetailUrl = '{{ route('modifier', ':id') }}';

        $(document).on('click', '#modifier-add', function(e) {
            $('#modifier-add-modal').find('.modal-title').text('Add Modifier');
            var modifierAddForm = $('#modifier-add-modal').find('form');
            modifierAddForm.attr('action', modifierCreateUrl);
            modifierAddForm.find('button[type=submit]').text('Add');
            modifierAddForm.find('#name').val('');
            modifierAddForm.find('#price').val('');
            modifierAddForm.find('#image').val('');
            modifierAddForm.find('#color').val('#ffffff');
            modifierAddForm.find('#status').val('1');
            modifierAddForm.find("#edit_modifier_icon").hide();

            setTempData("modal_title", 'Add Modifier');
            setTempData("add_update", 'Add');
            $('.validation-error').hide();
        });

        $(document).on('click', '#modifier-edit', function(e) {
            var id = $(this).data('id');
            var formUrl = modifierUpdateUrl.replace(':id', id);
            var url = modifierDetailUrl.replace(':id', id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.status == 'success') {
                        var data = response.data;
                        var form = $('#modifier-form');

                        form.attr('action', formUrl);
                        form.find('#name').val(data.name);
                        form.find('#price').val(data.price);
                        form.find('#color').val(data.color);
                        form.find('#font_color').val(data.font_color);

                        // Set selected modifier groups
                        var selectedGroups = data.modifier_groups || [];
                        $('#modifier_groups').val(selectedGroups).trigger('change');

                        if (data.image_url) {
                            $('#edit_modifier_icon').attr('src', data.image_url).show();
                        } else {
                            $('#edit_modifier_icon').hide();
                        }
                    }
                }
            });
        });


        $(document).on('click', '#deleteModifier', function(e) {
            var url = $(this).data('url');
            $('#deleteModifierBtn').attr('href', url);
        });

        // Validate input to allow only decimal values
        $('input[name="price"]').on('input', function() {
            this.value = this.value.replace(/[^0-9.]/g, '');

            if (this.value.split('.').length > 2) {
                this.value = this.value.replace(/\.+$/, "");
            }
        });
    </script>
    <link href="{{ asset('assets/css/plugins/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/plugins/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#modifier_groups').select2({
                placeholder: "Select Modifier Groups",
                dropdownParent: $(document.documentElement),
                allowClear: true,
                width: '100%',
                closeOnSelect: false
            });
        });

        $(document).on('focus', '#price', function(e) {
            $(this).select(); // Auto-select the whole text when focused
        });
    </script>

@endsection
