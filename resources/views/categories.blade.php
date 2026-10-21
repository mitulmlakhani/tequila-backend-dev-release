@extends('layouts.master')
@section('title')
    Item Categories
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>Add Item Category</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('category-create') }}" method="POST" enctype='multipart/form-data'>
                            @csrf
                            <!-- <div class="mb-3">
                                <label class="form-label" for="edit_parent_category">Parent Category </label>
                                <select class="form-select" id="parent_id" name="parent_id">
                                    <option value="">Select Parent Category</option>
                                    @foreach ($parentCategories as $parentCategory)
                                        <option value="{{ $parentCategory->id }}" @if(old('parent_id') == $parentCategory->id) selected @endif>{{ $parentCategory->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div> -->

                            <div class="mb-3">
                                <label class="form-label" for="edit_parent_category">Menu Type</label>
                                <select class="form-select" id="menu_type_id" name="menu_type_id">
                                    <option value="">Select Menu Type</option>
                                    @foreach ($MenuTypes as $menuType)
                                        <option value="{{ $menuType->id }}" @if(old('menu_type_id') == $menuType->id) selected @endif>{{ ucwords($menuType->name) }}</option>
                                    @endforeach
                                </select>
                                @error('menu_type_id')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" for="edit_parent_category">Default Item Tag</label>
                                <select class="form-select" id="item_tag_id" name="item_tag_id">
                                    <option value="">Select Item Tag</option>
                                    @foreach ($itemTags as $itemTag)
                                        <option value="{{ $itemTag->id }}" @if(old('item_tag_id') == $itemTag->id) selected @endif>{{ ucwords($itemTag->name) }}</option>
                                    @endforeach
                                </select>
                                @error('item_tag_id')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="edit_category_name">Category Name</label>
                                <input type="text" placeholder="Category Name" id="category_name" name="category_name"
                                        class="form-control max30Length" required value="{{ old('category_name', '') }}">
                                @error('category_name')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="image">Category Image </label>
                                <input type="file" id="image" name="image" class="form-control">
                                @error('image')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="color">Color</label>
                                <input type="color" id="color" name="color" class="form-control" value="{{ old('color', '#3B3F45') }}">
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

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="available_online" name="available_online">
                                    <label class="form-check-label" for="available_online">
                                        Available Online
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Description </label>
                                <textarea class="form-control uploadimage" name="description" id="description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-9 col-lg-9">
                    <!-- <div class="main-heading">
                        <h4>Item Category Management</h4>
                        @can('category-create')
                            @canany(['menu-import'])
                            <a href="{{ route('menu-import') }}" data-bs-toggle="modal" id="menu-import" data-bs-target="#menu-import-modal"
                                data-bs-whatever="@mdo">Import Menu</a>
                            @endcanany
                            @canany(['menu-export'])
                            <a class="export-menu" href="{{ route('menu-export') }}"  data-bs-whatever="@mdo">Export Menu</a>
                            @endcanany
                        @endcan
                    </div> -->
                    <div class="main-content table-container p-3">
                        <button class="btn btn-primary mt-2 mb-4" id="bulk-update-btn">Bulk Update</button>
                        <table data-page-length="-1" id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="sorting_disabled sorting_asc" rowspan="1" colspan="1" style="width: 23.1696px;" aria-label=""><input style="height: 18px; width: 18px;" type="checkbox" id="select-all"></th>
                                    <th scope="rowgroup">#</th>
                                    <th scope="rowgroup">Category</th>
                                    <th scope="rowgroup">Menu Type</th>
                                    <th scope="rowgroup">Color</th>
                                    <th scope="rowgroup">Font Color</th>
                                    <th scope="rowgroup">Image/Icon</th>
                                    <th scope="rowgroup">Created by</th>
                                    <th scope="rowgroup">Status</th>
                                    <th scope="rowgroup" class="text-center">Available Online
                                        <br />
                                        <input class="form-check-input available_online_list_all" type="checkbox" data-category-id="all" name="available_online">
                                    </th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody id="category_rows">
                                @foreach ($categories as $category)
                                    <tr>
                                        <td class="sorting_1"><input style="width: 18px; height: 18px; margin-top: 5px;" type="checkbox" class="item-checkbox" value="{{ $category->id }}"></td>
                                        <td>
                                            <input type="hidden" class="category_id" value={{ $category->id }} id="cate_id_{{ $category->id }}">
                                            <input type="number" class="order_index index_input" value="{{ $loop->iteration }}" id="cate_index_{{ $category->id }}">
                                        </td>
                                        <td>{{ $category->category_name }}</td> 
                                        <td>
                                            {{ $category->menuType->name ?? '--' }}
                                        </td>
                                        <td><span style="display: inline-block; width: 60px; height: 35px; background-color: {{ $category->color }}"></span></td>
                                        <td><span style="display: inline-block; width: 60px; height: 35px; background-color: {{ $category->font_color }}"></span></td>
                                        <td>
                                            <!-- <a href="{{ asset('images/menucat/' . $category->category_icon) }}" target="_blank"> -->
                                                <img src="{{ $category->image_url }}" class="list-image">
                                            <!-- </a> -->
                                        </td>
                                        <td>{{ $category->createdBy->name ?? '--' }}</td>
                                        <td>
                                            <div class="{{ $category->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $category->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                    class="me-2" alt="{{ $category->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $category->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input available_online_list" type="checkbox" data-category-id="{{ $category->id }}" name="available_online" {{ $category->available_online ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <span class="me-2">
                                                @can('category-edit')
                                                    <a aria-hidden="true" href="#" id="category-edit"
                                                        data-bs-toggle="modal" data-id="{{ $category->id }}"
                                                        data-bs-target="#category-add-modal" data-bs-whatever="@mdo">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            @if($category->can_delete)
                                            <span>
                                                @can('category-delete')
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteCategory"
                                                        data-bs-target="#deleteCategoryModal"
                                                        data-url="{{ route('category-delete', ['id' => $category->id]) }}">
                                                        <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
                                                    </a>
                                                @endcan
                                            </span>
                                            @endif
                                            <span>
                                                <i class="fa fa-copy copy-cat" style="font-size:20px;color:#ff4e2afc" data-id="{{ $category->id }}"></i>
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

     <!--Modal Popup import starts-->
     <div class="modal fade" id="menu-import-modal" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryLabel">Import Category Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('menu-import') }}" method="POST" enctype='multipart/form-data' id="menuImportForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="image">Upload file </label>
                            <input type="file" id="menu-items" name="menufile" class="form-control" required> 
                            <a href="{{ route('menu-download-sample') }}" >Download Template</a>
                            <div class="validation-error text-danger" id="fileError" style="display:none;">Please select a file to upload.</div>
                        </div>
                        <div class="mb-3 col-12 col-md-2 col-lg-2">
                            <img src="" class="list-file" id="edit_menu_icon">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-12 col-md-12 col-lg-12">
                            <div class="alert alert-warning" role="alert">
                                Warning: Export menu items first to avoid losing the current catalog.
                                <a href="{{ route('menu-export') }}" class="btn btn-warning btn-sm" data-bs-whatever="@mdo">Export Menu</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="openConfirmationModalBtn">Submit</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <!--Modal Popup import end-->

    <!-- Confirmation Modal Popup Start -->
    <div class="modal fade" id="importConfirmationModal" tabindex="-1" aria-labelledby="importConfirmationLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importConfirmationLabel">Import Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you Sure?</h4>
                        <p class="text-center mt-3">
                            Do you really want to import a new menu? All the previous menu items, modifiers, categories, and groups will be lost.
                            It's recommended to export the previous menu first.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelImportBtn">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmImportBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirmation Modal Popup End -->

    <!-- Copy Confirmation Modal -->
    <div class="modal fade" id="copyCategoryModal" tabindex="-1" aria-labelledby="copyCategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="copyCategoryLabel">Copy Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">Are you sure you want to copy this category and all its contents?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmCopyBtn" data-id="">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Update Modal -->
    <div class="modal fade" id="bulkUpdateModal" tabindex="-1" aria-labelledby="bulkUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkUpdateModalLabel">Set Bulk Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bulk-update-form">
                        <div class="form-group">
                            <label>UberEats Surcharge (%)</label>
                            <input type="text" name="ubereats_surcharge" placeholder="UberEats Surcharge (%)" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="bulk-update-submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->

@endsection
@section('js')
    <script>
        @if(Session::has('errors'))
            var isValidationError = true;
        @else
            var isValidationError = false;
        @endif

        var categoryCreateUrl = '{{ route('category-create') }}';
        var categoryUpdateUrl = '{{ route('category-edit', ':id') }}';
        var categoryDetailUrl = '{{ route('category', ':id') }}';
        var categoryOnlineStatusUpdateUrl = '{{ route('category-online-status-update', ':id') }}';
        var updateIndexUrl = '{{ route('category-update-index') }}';
        var categoryBulkUpdateUrl =  '{{ route('category-bulk-update') }}';
    </script>

    <script src="{{ asset('assets/js/plugins/Sortable.min.js') }}"></script>
    <script src="{{ asset('assets/js/item-category.js') }}"></script>    
@endsection

