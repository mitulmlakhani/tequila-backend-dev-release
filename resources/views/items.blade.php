@extends('layouts.master')
@section('title')
    Items
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('editor/summernote-lite.min.css') }}">
    <style>
        th {
            border-top: 1px solid #dddddd;
            border-bottom: 1px solid #dddddd;
            border-right: 1px solid #dddddd;
        }

        th:first-child {
            border-left: 1px solid #dddddd;
        }

        .modal.fade:not(.in).right .modal-dialog {
            -webkit-transform: translate3d(0%, 0, 0);
            transform: translate3d(0%, 0, 0);
        }
        
        .accordion-button {
            display: block;
        }

        .lg-checkbox{
            width: 30px;
            height: 30px;
        }

        .text-align-center{
            text-align: center !important;
        }

        .accordion-header{
            border-bottom: 1px solid #d1d1d1;
        }

        .fs-20{
            font-size: 20px;
        }
        .form-select{
            padding: 0rem 2.25rem .05rem .75rem !important;
        }
        .dt-button:hover{
            background-color:#0980B2 !important;
            background: linear-gradient(to bottom, #0980B2 0%, #0980B2 100%) !important;
        }
        /* sticky header */
        .dataTables_scrollHead {
            position: sticky;
            z-index: 1020;
            background: white;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css">

    <style>
        .select2-container {
            width: 100% !important;
            min-width: 120px !important;
            max-width: 220px !important;
        }

        .select2-container .select2-selection--multiple {
            min-height: 36px;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 2px 4px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .select2-dropdown {
            z-index: 1060 !important;
            min-width: 140px !important;
            max-width: 200px !important;
            border: 1px solid #ced4da;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 4px;
            background: #ffffff !important;
        }

        .select2-results__options {
            max-height: 250px;
            overflow-y: auto;
            padding: 4px 0;
        }

        .select2-results__option {
            padding: 6px 12px !important;
            white-space: nowrap !important;
        }

        .select2-check {
            width: 16px !important;
            min-width: 16px !important;
            height: 16px !important;
            flex-shrink: 0 !important;
            margin: 0 8px 0 0 !important;
            cursor: pointer !important;
            display: inline-block !important;
            vertical-align: middle !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #e9ecef !important;
            color: #212529 !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: transparent !important;
            color: #212529 !important;
        }

        /* Hide select_all choice pill if any appears in the rendered selected items list */
        .select2-container--default .select2-selection--multiple .select2-selection__choice[title="Select All"],
        .select2-container--default .select2-selection--multiple .select2-selection__choice[title=""],
        .select2-container--default .select2-selection--multiple .select2-selection__choice:empty {
            display: none !important;
        }

        /* Disable unselecting items directly from select2 choice box (remove 'x' icon) - modify only via dropdown checkboxes */
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            display: none !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            padding-left: 8px !important;
            padding-right: 8px !important;
            cursor: default !important;
            pointer-events: none !important;
        }


        /* Item image upload styles */
        .item-image-container {
            min-width: 80px;
        }
        .item-image-container img {
            border-radius: 4px;
            object-fit: cover;
        }
        .item-image-container .btn {
            cursor: pointer;
        }
        .item-image-container label.btn {
            margin-bottom: 0;
        }

        /* Summernote recipe editor styles */
        .recipe-preview {
            cursor: pointer;
            max-width: 200px;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Summernote Fullscreen & Dialog fix inside Bootstrap Modal */
        .note-editor.note-frame.fullscreen,
        .note-editor.note-airframe.fullscreen {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 10500 !important;
            background-color: #ffffff !important;
        }

        .note-editor.note-frame.fullscreen .note-editing-area,
        .note-editor.note-frame.fullscreen .note-editable {
            background-color: #ffffff !important;
        }

        .note-editor.note-frame.fullscreen .note-toolbar {
            background-color: #f8f9fa !important;
        }

        .note-dropdown-menu {
            z-index: 10550 !important;
        }

        .note-popover {
            z-index: 10650 !important;
        }

        .note-modal {
            z-index: 10600 !important;
        }

        .note-modal-backdrop {
            z-index: 10590 !important;
        }

        /* When Summernote fullscreen is active inside the modal */
        #recipeModal.summernote-fs-active {
            overflow: visible !important;
        }

        #recipeModal.summernote-fs-active .modal-dialog {
            transform: none !important;
            margin: 0 !important;
            max-width: none !important;
            width: 100% !important;
            overflow: visible !important;
        }

        #recipeModal.summernote-fs-active .modal-content {
            border: none !important;
            box-shadow: none !important;
            overflow: visible !important;
            background: transparent !important;
        }

        #recipeModal.summernote-fs-active .modal-header,
        #recipeModal.summernote-fs-active .modal-footer {
            display: none !important;
        }

        #recipeModal.summernote-fs-active .modal-body {
            padding: 0 !important;
            overflow: visible !important;
        }

        #bulkUpdateModal {
            background-color: #9999;
        }

        .form-select:focus {
            border: 1px solid #0d6efd !important;
        }
    </style>
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Items Management</h4>
                    </div>
                </div>
                @include('layouts.flash-msg')
                @include('layouts.validation-error-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <div style="overflow-x: auto;">
                            <table id="item_list_table" class="display nowrap w-100 cell-border">
                                <thead>
                                    <tr>
                                        {{-- <th></th> --}}
                                        <th scope="rowgroup"><svg style="visibility: hidden;" class=" svg-inline--fa fa-trash fa-w-14" aria-hidden="true" data-fa-processed="" data-prefix="fa" data-icon="trash" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M0 84V56c0-13.3 10.7-24 24-24h112l9.4-18.7c4-8.2 12.3-13.3 21.4-13.3h114.3c9.1 0 17.4 5.1 21.5 13.3L312 32h112c13.3 0 24 10.7 24 24v28c0 6.6-5.4 12-12 12H12C5.4 96 0 90.6 0 84zm415.2 56.7L394.8 467c-1.6 25.3-22.6 45-47.9 45H101.1c-25.3 0-46.3-19.7-47.9-45L32.8 140.7c-.4-6.9 5.1-12.7 12-12.7h358.5c6.8 0 12.3 5.8 11.9 12.7z"></path></svg></th>
                                        <th scope="rowgroup">#</th>
                                        <th scope="rowgroup">Item Name</th>
                                        <th scope="rowgroup">
                                            Category Name
                                            <select id="category_filter" class="form-select mt-2">
                                                <option disabled value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option {{ $category->id == $categoryId ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th scope="rowgroup">Price</th>
                                        <th scope="rowgroup">
                                            Item Type
                                            <select id="item_tag_filter" class="form-select mt-2">
                                                <option value="">Select Item Tag</option>
                                                @foreach($itemTags as $tag)
                                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th scope="rowgroup">Special <br /> Item</th>
                                        <th scope="rowgroup">Item <br /> Image</th>
                                        <th scope="rowgroup">Floor Item <br /> Icon</th>
                                        <th scope="rowgroup">Color</th>
                                        <th scope="rowgroup">Font <br /> Color</th>
                                        <th scope="rowgroup">
                                            Modifier Groups
                                            <br />
                                            <div class="fw-normal" id="category_modifier_list"></div>
                                            <button class="badge bg-dark mt-1 category_associate_modifier" style="cursor:pointer; font-size: 20px;" data-category_id=""><i class="fa fa-plus"></i></button>
                                            <button id="header_cancel_copy_modifier_btn" class="badge bg-danger mt-1 cancel_copy_modifier ms-1" style="cursor:pointer; font-size: 20px; display: none;" title="Cancel Copy"><i class="fa fa-times"></i></button>
                                        </th>
                                        <th scope="rowgroup"> Ingredient</th>
                                        <th scope="rowgroup"> Variants</th>
                                        {{-- <th scope="rowgroup">Modifiers Group</th> --}}
                                        {{-- <th scope="rowgroup">Force Modifiers Group</th> --}}
                                        <th scope="rowgroup">Preparation <br /> Time</th>
                                        <th scope="rowgroup">Recipe</th>
                                        <th scope="rowgroup">Available <br /> In</th>
                                        <th scope="rowgroup">UOM</th>
                                        <th scope="rowgroup">
                                            KDS <br /> Devices <br/>
                                            <select id="kds_device_filter" class="form-control checkbox-select mt-2" multiple="multiple">
                                                <option value="select_all">Select All</option>    
                                                @foreach($posDevices->where('type', 'kds') as $device)
                                                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th scope="rowgroup">
                                            Printer <br /> Devices <br/>
                                            <select id="printer_device_filter" class="form-control checkbox-select mt-2" multiple="multiple">
                                                <option value="select_all">Select All</option>
                                                @foreach($posDevices->where('type', 'printer') as $device)
                                                    <option value="{{ $device->id }}">{{ $device->name }}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th scope="rowgroup">Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->
    <!--Modal Popup Edit start-->
    <div class="modal fade" id="deleteItemModal" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Item</h5>
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
                    <a href="#" class="btn btn-primary" id="deleteItemBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!--Modal Popup Edit start-->
    <div class="modal fade right" id="addModifier" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                
            </div>
        </div>
    </div>


    <!-- Default Modal Structure -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to apply this action to all items?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmAction">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content set dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmAction">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="warningModalLabel">Warning</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Please select a category before updating the item type.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dangerModal" tabindex="-1" aria-labelledby="dangerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dangerModalLabel">Warning</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="dangerModalBody">
                    <!-- Content set dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recipe Editor Modal -->
    <div class="modal fade" id="recipeModal" aria-labelledby="recipeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="recipeModalLabel">Edit Recipe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="recipe_item_id" value="">
                    <textarea id="recipe_editor"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveRecipeBtn">Save Recipe</button>
                </div>
            </div>
        </div>
    </div>

    @include('modals.price-bulk-update')
@endsection
@section('js')
    <script src="{{ asset('editor/summernote-lite.min.js') }}"></script>
    <script>
        // var itemListUrl = '{{ route('item-list', ['categoryId' => ':categoryId']) }}';
        var itemListUrl = '{{ route('item-list') }}';
        var categoryId = "{{ empty($categoryId) ? '' : $categoryId }}";

        var categories = {!! $categories !!};
        var itemTags = {!! $itemTags !!};
        window.defaultItemTag = categories.find(c => c.id == categoryId)?.item_tag_id || itemTags.find(t => t.name == 'Food')?.id || "";
        // itemListUrl = itemListUrl.replace(':categoryId', categoryId);
        var itemsDynamicOptionsUrl = '{{ route('item-dynamic-options') }}';

        var itemAssociateModifiersUrl = '{{ route('associate-modifiers', ['itemId' => ':itemId']) }}';
        var categoryAssociateModifiersUrl = '{{ route('category-associate-modifiers', ['categoryId' => ':categoryId']) }}';
        var itemAddModifierUrl = '{{ route('add-modifier-group', ['itemId' => ':itemId', 'modifierGroupId' => ':modifierGroupId']) }}';
        var cloneModifierGroupUrl = '{{ route('clone-modifier-group', ['itemId' => ':itemId', 'targetItemId' => ':targetItemId']) }}';
        var bulk_update = '{{ route('items.update') }}';
        var itemUploadImageUrl = '{{ route('item-upload-image', ['itemId' => ':itemId']) }}';
        var itemDeleteImageUrl = '{{ route('item-delete-image', ['itemId' => ':itemId']) }}';
        var itemUpdateShowImageOnPosUrl = '{{ route('item-update-show-image-on-pos', ['itemId' => ':itemId']) }}';
    </script>
    <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('assets/js/item.js') }}?v={{ filemtime(public_path('assets/js/item.js')) }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>

    <script>
        $(document).ready(function () {
            // Collapse Menu Management menu on Items page
            $('#menu-management-menu').removeClass('showMenu');

            // Show the modal on bulk update button click
            $(document).on('click', '#bulk-update-btn', function () {
                if ($('.item-modifier-checkbox:checked').length === 0) {
                    alert('Please select modifiers to update.');
                } else {
                    $('#bulkUpdateModal').modal('show');
                    $('input[name="update_type"][value="set_to"]').prop('checked', true); // Set default to 'Set to'
                }
            });

            $('#bulk-update-submit').on('click', function(e) {
                e.preventDefault();
                var updateType = $('input[name="update_type"]:checked').val();
                var setToValue = $('input[name="set_to"]').val();
                var byPriceValue = $('input[name="by_price"]').val();
                var byPercentValue = $('input[name="by_percent"]').val();
                var percentType = $('input[name="percent_type"]:checked').val();

                if (updateType === 'set_to' && !setToValue) {
                    alert('Please enter a value for "Set to".');
                    return;
                }

                if (updateType === 'by_price' && !byPriceValue) {
                    alert('Please enter a value for "By Price".');
                    return;
                }

                if (updateType === 'by_percent' && !byPercentValue) {
                    alert('Please enter a value for "By Percent".');
                    return;
                }

                var selectedVariants = $('.item-modifier-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedVariants.length === 0) {
                    alert('No modifier selected.');
                    return;
                }

                var formData = $('#bulk-update-form').serializeArray();
                formData.push({name: 'modifiers', value: selectedVariants.join(',')});
                formData.push({name: '_token', value: $('meta[name="csrf-token"]').attr('content')});
                formData.push({name: 'percent_type', value: percentType});

                $.ajax({
                    type: 'POST',
                    url: '{{ route('save-bulk-item-modifiers') }}',
                    data: $.param(formData),
                    success: function(response) {
                            var itemId = $("#bulk-update-submit").data('item-id');
                            url = itemAssociateModifiersUrl.replace(':itemId', itemId);
                            $.ajax({
                                type: 'GET',
                                url: url,
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                                },
                                beforeSend: function(){
                                    showLoader();
                                },
                                complete: function(){
                                    hideLoader();
                                },
                                success: function (response) {
                                    if (response.status == 'success') {
                                        $('#addModifier').find('.modal-content').html(response.html);
                                        $('#addModifier').modal('show');
                                        $('#bulkUpdateModal').modal('hide');

                                        // #Sortable - Modifier and Modifier Items drag and drop
                                        makeGroupModifierFormSortable();

                                        // Fix this
                                        $("#bulk-update-form").find("input:text").each(function (i, elm) {
                                            $(elm).val('');
                                        });
                                        $("#bulk-update-form").find("input[name='price_type[]']").each(function (i, elm) {
                                            if ($(elm).val() == 'price') {
                                                $(elm).prop('checked', true);
                                            } else {
                                                $(elm).prop('checked', false);
                                            }
                                        });
                                        // validateModifierItemsSelection();
                                    }
                                }
                            });
                    },
                    error: function(xhr) {
                        console.log({xhr});
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            });

        });
    </script>
@endsection
