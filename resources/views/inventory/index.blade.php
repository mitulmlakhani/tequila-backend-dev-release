@extends('layouts.master')
@section('title')
    Item Inventory Management
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Item Inventory Management</h4>
                        @can('inventory-create')
                            <a href="{{ route('inventory-create') }}" data-bs-toggle="modal" id="inventory-add" data-bs-target="#inventory-add-modal"
                                data-bs-whatever="@mdo">Add Inventory</a>
                        @endcanany
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">Item</th>
                                    <th scope="rowgroup">Category</th>
                                    <th scope="rowgroup">COG</th>
                                    <!-- <th scope="rowgroup">ALU</th> -->
                                    <th scope="rowgroup">QTY In Hand</th>
                                    <th scope="rowgroup">Reorder Point</th>
                                    <th scope="rowgroup">Stock Status</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventories as $inventory)
                                    <tr>
                                        @can('inventory-transactions')
                                            @if(!$inventory || !$inventory->item)
                                                <td>No records found</td>
                                            @else
                                                <td>
                                                    <a href="{{ route('inventory-transactions', $inventory->item->id) }}">
                                                        {{ $inventory->item->name }}
                                                    </a>
                                                </td>
                                            @endif
                                        @else
                                            <td>{{ $inventory->item->name ?? 'No item available' }}</td>
                                        @endcan

                                        <td>{{ $inventory->item->category->category_name ?? 'No category' }}</td>
                                        <td>{{ $inventory->cog }}</td>
                                        <!-- <td>{{ $inventory->alu }}</td> -->
                                        <td>{{ $inventory->quantity }}</td>
                                        <td>{{ $inventory->reorder_point }}</td>
                                        <td>{{ $inventory->quantity > $inventory->reorder_point ? 'In Stock' : 'Out of Stock' }}</td>
                                        <td>
                                            @can('inventory-delete')
                                                <span>
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteInventory"
                                                    data-bs-target="#deleteInventoryModal"
                                                    data-url="{{ route('inventory-delete', ['id' => $inventory->id]) }}">
                                                        <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
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

    <!--Modal Popup Add start-->
    <div class="modal fade" id="inventory-add-modal" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryLabel">Add New Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('inventory-create') }}" method="POST" enctype='multipart/form-data'>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="edit_parent_category"> Item </label>
                            <select class="form-select" id="item_id" name="item_id">
                                <option value="">Select Item</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" @if(old('item_id') == $item->id) selected @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('item_id')
                                <div class="validation-error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="quantity">QTY (In Hand)</label>
                            <input type="number" step="1" class="form-control" id="quantity" name="quantity" required>
                            @error('quantity')
                                <div class="validation-error text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-4 col-lg-6">
                            <label class="form-label" for="cog">COG</label>
                            <input type="number" step="0.01" class="form-control" id="cog" name="cog">
                            @error('cog')
                                <div class="validation-error text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="alu">ALU</label>
                            <input type="text" class="form-control" id="alu" name="alu">
                            @error('alu')
                                <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div> -->
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="reorder_point">Reorder Point</label>
                            <input type="number" step="1" class="form-control" id="reorder_point" name="reorder_point">
                            @error('reorder_point')
                                <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="type">Type</label>
                            {{ Form::select('type',[1=>'Add',0=>'Subtract'], null, array('class' =>'form-select','id'=>'type')) }}
                            @error('type')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--Modal Popup Edit end-->
    

    <!--Delete Modal Popup Start-->
    <div class="modal fade" id="deleteInventoryModal" tabindex="-1" aria-labelledby="deleteRoleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Category</h5>
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
                    <a href="#" class="btn btn-primary" id="deleteInventoryBtn">Delete</a>
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

        var categoryCreateUrl = '{{ route('category-create') }}';
        var categoryUpdateUrl = '{{ route('category-edit', ':id') }}';
        var categoryDetailUrl = '{{ route('category', ':id') }}';
    </script>

    <script src="{{ asset('assets/js/inventory.js') }}"></script>    
@endsection
