@extends('layouts.master')
@section('title')
    Ingredient Inventory Management
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Ingredient Inventory Management</h4>
                        <div>
                        @can('inventory-create')
                            <button id="orderButton" class="btn btn-secondary">Start Order</button>

                            <a href="{{ route('inventory-create') }}" data-bs-toggle="modal" id="inventory-add"
                                data-bs-target="#inventory-add-modal" data-bs-whatever="@mdo">Add Ingredient Inventory</a>
                        @endcanany
                        </div>
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">Ingredient</th>
                                    <th scope="rowgroup">COG</th>
                                    <!-- <th scope="rowgroup">ALU</th> -->
                                    <th scope="rowgroup">Reorder Point</th>
                                    <th scope="rowgroup">QTY In Hand</th>
                                    <th scope="rowgroup">Order Qty</th>
                                    <th scope="rowgroup">Unit Price</th>
                                    @foreach ($vendors as $vendor)
                                        <th class="text-center" scope="rowgroup">{{ ucwords($vendor->name) }}</th>
                                    @endforeach
                                    <th scope="rowgroup">Stock Status</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventories as $inventory)
                                    @php $ingredient = $inventory->ingredient()->withTrashed()->first(); @endphp
                                    <tr>
                                        @can('ingredient-inventory-transactions')
                                            <td><a href="{{ route('ingredient-inventory-transactions', $ingredient->id) }}">{{ $ingredient->name }}
                                                    @if($ingredient->deleted_at)
                                                        <span class="badge bg-danger">Deleted</span>
                                                    @endif
                                                </a>
                                            </td>
                                        @else
                                            <td>{{ $ingredient->name }}
                                                @if($ingredient->deleted_at)
                                                    <span class="badge bg-danger">Deleted</span>
                                                @endif
                                            </td>
                                        @endcan

                                        <td>{{ $inventory->cog }}</td>
                                        <!-- <td>{{ $inventory->alu }}</td> -->
                                        <td>{{ $inventory->reorder_point }}</td>
                                        <td>{{ $inventory->quantity }}
                                            {{ $ingredient->unitOfMeasurement->name }}
                                        </td>
                                        <td>
                                            <input type="text" class="form-control order_qty_box"
                                                name="qty_{{ $inventory->ingredient_id }}"
                                                id="qty_{{ $inventory->ingredient_id }}"
                                                data-ingredient_id="{{ $inventory->ingredient_id }}" data-vendor_id=""
                                                style="width: 100px;" />

                                            @error('orders.' . $inventory->ingredient_id . '.quantity')
                                                <div class="validation-error text-danger">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" class="form-control order_price_box" disabled
                                                name="price_{{ $inventory->ingredient_id }}"
                                                id="price_{{ $inventory->ingredient_id }}" value=""
                                                style="width: 100px;" />

                                            @error('orders.' . $inventory->ingredient_id . '.unit_price')
                                                <div class="validation-error text-danger">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        @foreach ($vendors as $vendor)
                                            <td class="text-center">
                                                <div class="d-flex flex-column align-items-center justify-content-center">
                                                    <input disabled
                                                        class="form-check-input lg-checkbox vendor_checkbox mb-2"
                                                        type="checkbox"
                                                        data-last_price="{{ $lastIngredientPricesByVendors[$inventory->ingredient_id][$vendor->id] ?? '' }}"
                                                        data-vendor_id="{{ $vendor->id }}"
                                                        data-ingredient_id="{{ $inventory->ingredient_id }}" />
                                                    <a href="javascript:void(0)" class="vendor_price"
                                                        data-item_name="{{ $ingredient->name }}"
                                                        data-vendor_id="{{ $vendor->id }}"
                                                        data-ingredient_id="{{ $inventory->ingredient_id }}">{{ $lastIngredientPricesByVendors[$inventory->ingredient_id][$vendor->id] ?? '' }}</a>
                                                </div>
                                            </td>
                                        @endforeach

                                        <td>{{ $inventory->quantity > $inventory->reorder_point ? 'In Stock' : 'Out of Stock' }}
                                        </td>
                                        <td>
                                            @can('inventory-delete')
                                                <span>
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal"
                                                        id="deleteInventory" data-bs-target="#deleteInventoryModal"
                                                        data-url="{{ route('ingredient-inventory-delete', ['id' => $inventory->id]) }}">
                                                        <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
                                                    </a>
                                                </span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total Items: <span id="total_items"></span></th>
                                    <th>Order Total: <span id="order_amount"></span></th>
                                    @foreach ($vendors as $vendor)
                                        <th class="text-center vendor_total_item" data-vendorId="{{ $vendor->id }}"
                                            id="vendor_total_{{ $vendor->id }}"></th>
                                    @endforeach
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
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
                <form action="{{ route('ingredient-inventory-create') }}" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="edit_parent_category"> Item </label>
                                <select class="form-select" id="ingredient_id" name="ingredient_id">
                                    <option value="">Select ingredient</option>
                                    @foreach ($ingredients as $ingredient)
                                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                    @endforeach
                                </select>
                                @error('ingredient_id')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="quantity">QTY (In Hand)</label>
                                <input type="number" step="1" class="form-control" id="quantity" name="quantity"
                                    required>
                                @error('quantity')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-4 col-lg-6">
                                <label class="form-label" for="cog">COG</label>
                                <input type="number" step="0.01" class="form-control" id="cog"
                                    name="cog">
                                @error('cog')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="mb-3 col-12 col-md-6 col-lg-6">
                                    <label class="form-label" for="alu">ALU</label>
                                    <input type="text" class="form-control" id="alu" name="alu">
                                    @error('alu')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="reorder_point">Reorder Point</label>
                                <input type="number" step="1" class="form-control" id="reorder_point"
                                    name="reorder_point">
                                @error('reorder_point')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="type">Type</label>
                                {{ Form::select('type', [1 => 'Add', 0 => 'Subtract'], null, ['class' => 'form-select', 'id' => 'type']) }}
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

    <!--Price History Modal Popup Start-->
    <div class="modal fade" id="priceHistoryModal" tabindex="-1" aria-labelledby="priceHistoryLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceHistoryLabel">Price History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Vendor</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody id="priceHistoryBody">
                            <!-- Price history data will be populated here via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Price History Modal Popup END-->
@endsection
@section('js')
    <script>
        @if (Session::has('errors'))
            var isValidationError = true;
        @else
            var isValidationError = false;
        @endif

        var categoryCreateUrl = '{{ route('category-create') }}';
        var categoryUpdateUrl = '{{ route('category-edit', ':id') }}';
        var categoryDetailUrl = '{{ route('category', ':id') }}';
    </script>

    <script src="{{ asset('assets/js/inventory.js') }}"></script>

    <script>
        $(function() {

            /* -------------------------
             * Helpers
             * ------------------------- */
            const toFloat = val => {
                const num = parseFloat(val);
                return Number.isFinite(num) ? num : 0;
            };

            const calculateTotals = () => {
                let totalItems = 0;
                let orderTotal = 0;
                const vendorTotals = {};

                $('.order_qty_box').each(function() {
                    const $row = $(this).closest('tr');

                    const qty = toFloat($(this).val());
                    const price = toFloat($row.find('.order_price_box').val());
                    const vendorId = $(this).data('vendor_id');

                    if (!qty || !price || !vendorId) return;

                    const lineTotal = qty * price;

                    totalItems += qty;
                    orderTotal += lineTotal;
                    vendorTotals[vendorId] = (vendorTotals[vendorId] || 0) + lineTotal;
                });

                $('#total_items').text(totalItems);
                $('#order_amount').text(orderTotal.toFixed(2));

                $('.vendor_total_item').each(function() {
                    const vId = $(this).data('vendorid');
                    $(this).text((vendorTotals[vId] || 0).toFixed(2));
                });
            };

            /* -------------------------
             * Quantity change
             * ------------------------- */
            $(document).on('input', '.order_qty_box', function() {
                const $row = $(this).closest('tr');
                const qty = toFloat($(this).val());
                const enabled = qty > 0;

                $row.find('.vendor_checkbox').prop({
                    disabled: !enabled,
                    checked: enabled ? undefined : false
                });

                $row.find('.order_price_box').prop('disabled', false);

                calculateTotals();
            });

            /* -------------------------
             * Vendor checkbox change
             * ------------------------- */
            $(document).on('change', '.vendor_checkbox', function() {
                const $row = $(this).closest('tr');
                const vendorId = $(this).data('vendor_id');

                if (!this.checked) {
                    $row.find('.order_qty_box').removeData('vendor_id');
                    $row.find('.order_price_box').val('');
                } else {
                    // single vendor per row
                    $row.find('.vendor_checkbox').not(this).prop('checked', false);
                    $row.find('.order_qty_box').data('vendor_id', vendorId);
                    $row.find('.order_price_box').val($(this).data('last_price') || '');
                }

                calculateTotals();
            });

            /* -------------------------
             * Price change
             * ------------------------- */
            $(document).on('input', '.order_price_box', calculateTotals);

            /* -------------------------
             * Price history modal
             * ------------------------- */
            $(document).on('click', '.vendor_price', function() {
                const vendorId = $(this).data('vendor_id');
                const ingredientId = $(this).data('ingredient_id');
                const ingredientName = $(this).data('item_name');

                let url = "{{ route('ingredient-price-history', ':id') }}"
                    .replace(':id', ingredientId);

                $.get(url, {
                        vendorId
                    })
                    .done(response => {
                        const $tbody = $('#priceHistoryBody').empty();

                        if (!response.length) {
                            $tbody.append(
                                '<tr><td colspan="3" class="text-center">No price history available.</td></tr>'
                            );
                        } else {
                            response.forEach(r => {
                                $tbody.append(`
                            <tr>
                                <td>${r.invoice_date}</td>
                                <td>${r.vendor_name}</td>
                                <td>${r.unit_price}</td>
                            </tr>
                        `);
                            });
                        }

                        $('#priceHistoryLabel').text(`${ingredientName} Price History`);
                        $('#priceHistoryModal').modal('show');
                    })
                    .fail(() => alert('Failed to fetch price history.'));
            });

            /* -------------------------
             * Submit order
             * ------------------------- */
            $('#orderButton').on('click', function() {
                const $form = $('<form>', {
                    method: 'POST',
                    action: '{{ route('vendor-invoice-multi-store') }}'
                }).append(
                    $('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: '{{ csrf_token() }}'
                    })
                );

                $('.order_qty_box').each(function() {
                    const qty = toFloat($(this).val());
                    const itemId = $(this).data('ingredient_id');
                    const vendorId = $(this).data('vendor_id');
                    const unit_price = $(this).closest('tr').find('.order_price_box').val();

                    if (!qty || !vendorId) return;

                    [
                        ['quantity', qty],
                        ['vendor_id', vendorId],
                        ['item_id', itemId],
                        ['unit_price', unit_price]
                    ].forEach(([key, value]) => {
                        $form.append(
                            $('<input>', {
                                type: 'hidden',
                                name: `orders[${itemId}][${key}]`,
                                value
                            })
                        );
                    });
                });

                $('body').append($form);
                $form.submit();
            });

        });
    </script>
@endsection
