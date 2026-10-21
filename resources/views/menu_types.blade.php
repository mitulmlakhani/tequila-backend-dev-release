@extends('layouts.master')
@section('title')
    Meny Types
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>Add Menu Type</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('menu-type-create') }}" method="POST" enctype='multipart/form-data'>
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" placeholder="Menu Type Name" id="name" name="name"
                                    class="form-control max30Length" required value="{{ old('name', '') }}">
                                @error('name')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="color">Color</label>
                                <input type="color" id="color" name="color" class="form-control"
                                    value="{{ old('color', '#C7C7C7') }}">
                                @include('elements.colors-div')
                                @error('color')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="font_color">Font Color</label>
                                <input type="color" id="font_color" name="font_color" class="form-control"
                                    value="{{ old('font_color', '#ffffff') }}">
                                @include('elements.colors-div')
                                @error('font_color')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="index">Index</label>
                                <input type="text" placeholder="Index" id="index" name="index"
                                    class="form-control max30Length" required value="{{ old('index', '') }}">
                                @error('index')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-content table-container p-3">
                        <button class="btn btn-primary mt-2 mb-4" id="menu-type-add">Add</button>
                        <table data-page-length="-1" id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">Name</th>
                                    <th scope="rowgroup">Color</th>
                                    <th scope="rowgroup">Font Color</th>
                                    <th scope="rowgroup">Index</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody id="category_rows">
                                @foreach ($menuTypes as $menuType)
                                    <tr>
                                        <td>{{ $menuType->name }}
                                        </td>
                                        <td><span
                                                style="display: inline-block; width: 60px; height: 35px; background-color: {{ $menuType->color }}"></span>
                                        </td>
                                        <td><span
                                                style="display: inline-block; width: 60px; height: 35px; background-color: {{ $menuType->font_color }}"></span>
                                        </td>
                                        <td>{{ $menuType->index ?: 0 }}</td>
                                        <td>
                                            <span class="me-2">
                                                @can('category-edit')
                                                    <a aria-hidden="true" href="#" id="menu-type-edit"
                                                        data-id="{{ $menuType->id }}"
                                                        >
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            @can('category-delete')
                                                <span>
                                                    @can('category-delete')
                                                        <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteMenuType"
                                                            data-bs-target="#deleteMenuTypeModal"
                                                            data-url="{{ route('menu-type-delete', ['id' => $menuType->id]) }}">
                                                            <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
                                                        </a>
                                                    @endcan
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

    <!--Delete Modal Popup Start-->
    <div class="modal fade" id="deleteMenuTypeModal" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Menu Type</h5>
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
                    <a href="#" class="btn btn-primary" id="deleteMenuTypeBtn">Delete</a>
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

        var menuTypeCreateUrl = '{{ route('menu-type-create') }}';
        var menuTypeUpdateUrl = '{{ route('menu-type-edit', ':id') }}';
        var menuTypeDetailUrl = '{{ route('menu-type', ':id') }}';
    </script>

    <script src="{{ asset('assets/js/menu-type.js') }}"></script>
@endsection