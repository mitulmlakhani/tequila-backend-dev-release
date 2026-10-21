@extends('layouts.master')
@section('title')
    Modifier Categories
@endsection

@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Modifier Category Management</h4>
                        @can('modifier-category-create')
                            <a href="{{ route('category-create') }}" data-bs-toggle="modal" data-bs-target="#category-add-modal"
                                id="category-add" data-bs-whatever="@mdo">Add Modifier Category</a>
                        @endcan
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup"></th>
                                    <th scope="rowgroup">Category</th>
                                    <th scope="rowgroup">color</th>
                                    <th scope="rowgroup">Image/Icon</th>
                                    <th scope="rowgroup">Created by</th>
                                    <th scope="rowgroup">Status</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $key => $category)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td><span style="display: inline-block; width: 60px; height: 35px; background-color: {{ $category->color }}"></span></td>
                                        <td>
                                            <a href="{{ asset('images/modifier-categories/' . $category->image) }}"
                                                target="_blank">
                                                <img src="{{ asset($category->image_url) }}"
                                                    class="list-image">
                                            </a>
                                        </td>
                                        <td>{{ $category->createdBy->name }}</td>
                                        <td>
                                            <div class="{{ $category->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $category->status ? 'assets/images/reserved.png' : 'assets/images/pending.png' }}"
                                                    class="me-2" alt="{{ $category->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $category->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="me-2">
                                                @can('modifier-category-edit')
                                                    <a aria-hidden="true" href="#" id="category-edit"
                                                        data-bs-toggle="modal" data-id="{{ $category->id }}"
                                                        data-bs-target="#category-add-modal" data-bs-whatever="@mdo">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <span>
                                                @can('modifier-category-delete')
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal"
                                                        id="deleteCategory" data-bs-target="#deleteCategoryModal"
                                                        data-url="{{ route('modifier-category-delete', ['id' => $category->id]) }}">
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
    <!--Modal Popup Add start-->
    <div class="modal fade" id="category-add-modal" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('modifier-category-create') }}" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" placeholder="Name" id="name" name="name" class="form-control"
                                    required value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="image">Image </label>
                                <input type="file" id="image" name="image" class="form-control">
                                @error('image')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <img src="" class="list-image" id="edit_category_icon">
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="color">Color</label>
                                <input type="color" id="color" name="color" class="form-control" value="{{ old('color', '#ffffff') }}">
                                <!-- Predefined Colors -->
                                @include('elements.colors-div')
                                @error('color')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="status">Status </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>In-active</option>
                                </select>
                                @error('status')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-12 col-lg-12">
                                <label class="form-label" for="description">Description </label>
                                <textarea class="form-control uploadimage" name="description" id="description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="Submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Modal Popup Add end-->


    <!--Delete Modal Popup Start-->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteRoleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Category</h5>
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
                    <a href="#" class="btn btn-primary" id="deleteCategoryBtn">Delete</a>
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

        var categoryCreateUrl = '{{ route('modifier-category-create') }}';
        var categoryUpdateUrl = '{{ route('modifier-category-edit', ':id') }}';
        var categoryDetailUrl = '{{ route('modifier-category', ':id') }}';
    </script>

    <script src="{{ asset('assets/js/modifier-category.js') }}"></script>    
@endsection
