@extends('layouts.master')
@section('title')
    Variants
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Variant Management</h4>
                        @can('variant-create')
                            <a href="{{ route('category-create') }}" data-bs-toggle="modal" data-bs-target="#variant-add-modal" id="variant-add"
                                data-bs-whatever="@mdo">Add Variant</a>
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
                                    <th scope="rowgroup">Name</th>
                                    <th scope="rowgroup">Created by</th>
                                    <th scope="rowgroup">Status</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($variants as $variant)
                                    <tr>
                                        <td></td>
                                        <td>{{ $variant->name }}</td>
                                        <td>{{ $variant->createdBy->name }}</td>
                                        <td>
                                            <div class="{{ $variant->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $variant->status ? 'assets/images/reserved.png' : 'assets/images/pending.png' }}"
                                                    class="me-2" alt="{{ $variant->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $variant->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="me-2">
                                                @can('variant-edit')
                                                    <a aria-hidden="true" href="#" id="variant-edit"
                                                        data-bs-toggle="modal" data-id="{{ $variant->id }}"
                                                        data-bs-target="#variant-add-modal" data-bs-whatever="@mdo">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <span>
                                                @can('variant-delete')
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteVariant"
                                                        data-bs-target="#deleteVariantModal"
                                                        data-url="{{ route('variant-delete', ['id' => $variant->id]) }}">
                                                        <img src="assets/images/dustbin.png" alt="dustbin">
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
    <div class="modal fade" id="variant-add-modal" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryLabel">Add Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('variant-create') }}" method="POST" enctype='multipart/form-data'>
                    @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" placeholder="Name" id="name" name="name" class="form-control" required value="{{ old('name','') }}">
                            @error('name')
                                <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="status">Status </label>
                            <select class="form-select" id="status" name="status">
                                <option value="1" {{ old("status") == 1 ? "selected":"" }}>Active</option>
                                <option value="0" {{ old("status") == 0 ? "selected":"" }}>In-active</option>
                            </select>
                            @error('status')
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
    <div class="modal fade" id="deleteVariantModal" tabindex="-1" aria-labelledby="deleteRoleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-img">
                        <img src="assets/images/delete.png" alt="delete" class="img-fluid">
                    </div>
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you Sure?</h4>
                        <p class="text-center mt-3">Do you really want to delete ? <br>This process cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-primary" id="deleteVariantBtn">Delete</a>
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

        var variantCreateUrl = '{{ route('variant-create') }}';
        var variantUpdateUrl = '{{ route('variant-edit', ':id') }}';
        var variantDetailUrl = '{{ route('variant', ':id') }}';
    </script>

    <script src="{{ asset('assets/js/variant.js') }}"></script>    
@endsection
