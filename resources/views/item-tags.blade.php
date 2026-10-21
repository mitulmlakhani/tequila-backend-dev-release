@extends('layouts.master')
@section('title')
    Item Types
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>Add Item Type</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('item_tags-create') }}" method="POST" enctype='multipart/form-data' id="item-tag-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" placeholder="Name" id="name" name="name" class="form-control" required value="{{ old('name','') }}">
                                @error('name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="status">Status </label>
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
                        <h4>Item Types Management</h4>
                        @can('item_tags-create')
                            <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#item-tag-add-modal" id="item-tag-add"
                               data-bs-whatever="@mdo">Add Item Type</a> -->
                        @endcan
                    </div>
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">#</th>
                                    <th scope="rowgroup">Name</th>
                                    <th scope="rowgroup">Created by</th>
                                    <th scope="rowgroup">Status</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($itemTags as $itemTag)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $itemTag->name }}</td>
                                        <td>{{ $itemTag->createdBy->name }}</td>
                                        <td>
                                            <div class="{{ $itemTag->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $itemTag->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                     class="me-2" alt="{{ $itemTag->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $itemTag->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="me-2">
                                                @can('item_tag-edit')
                                                    <a aria-hidden="true" href="#" id="item-tag-edit"
                                                       data-bs-toggle="modal" data-id="{{ $itemTag->id }}"
                                                       data-bs-target="#item-tag-add-modal" data-bs-whatever="@mdo">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <span>
                                                @can('item_tags.destroy')
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteItemTag"
                                                       data-bs-target="#deleteItemTagModal"
                                                       data-url="{{ route('item_tags.destroy', ['id' => $itemTag->id]) }}">
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
        @if(Session::has('errors'))
            var isValidationError = true;
        @else
            var isValidationError = false;
        @endif

        var itemTagCreateUrl = '{{ route('item_tags-create') }}';
        var itemTagUpdateUrl = '{{ route('item_tag-edit', ':id') }}';
        var itemTagDetailUrl = '{{ route('item_tag', ':id') }}';

        $(document).on('click', '#item-tag-add', function () {
            $('#item-tag-form').attr('action', itemTagCreateUrl);
            $('#item-tag-form').find('input[name="_method"]').remove();
            $('#item-tag-form').trigger('reset');
            $('.modal-title').text('Add Item Type');
        });

        $(document).on('click', '#item-tag-edit', function () {
            var id = $(this).data('id');
            var url = itemTagDetailUrl.replace(':id', id);
            var tagRow = $(this).closest('tr');
            
            $("#category_management").find('tr').each(function (i, tr) {
                this.style.removeProperty('background-color');
            });

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status == 'success') {
                        tagRow.css('background-color', '#ffe')
                        $('#name').val(response.data.name).focus();
                        $('#status').val(response.data.status ? 1 : 0);
                        $('.modal-title').text('Edit Item Type');
                        $('#item-tag-add-modal').modal('show');

                        $('#item-tag-form').attr('action', itemTagUpdateUrl.replace(':id', id));
                        if (!$('#item-tag-form').find('input[name="_method"]').length) {
                            $('#item-tag-form').append('<input type="hidden" name="_method" value="POST">');
                        }
                    }
                }
            });
        });

        $(document).on('click', '#deleteItemTag', function () {
            var url = $(this).data('url');
            $('#delete-item-tag-form').attr('action', url);
        });

        $('#deleteItemTagModal').on('hidden.bs.modal', function () {
            $('#delete-item-tag-form').attr('action', '');
        });
    </script>
@endsection
