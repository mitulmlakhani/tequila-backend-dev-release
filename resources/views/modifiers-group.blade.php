@extends('layouts.master')
@section('title')
    Force Modifier
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4 id="form-title">Add Modifier Group</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('modifier-group-create') }}" method="POST" enctype='multipart/form-data' id="modifier-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" placeholder="Name" id="name" name="name" class="form-control" required value="{{ old('name','') }}" autofocus />
                                @error('name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
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

                            <!-- Modifier selection -->
                            <!-- <div class="mb-3">
                                <label class="form-label" for="modifiers">Select Modifiers</label>
                                <select class="form-select" id="modifiers" name="modifiers[]" multiple="multiple" required>
                                    @foreach($modifiers as $modifier)
                                        <option value="{{ $modifier->id }}" data-price="{{ $modifier->price }}">{{ $modifier->name }}</option>
                                    @endforeach
                                </select>
                            </div> -->

                            <!-- Collapsible section for prices -->
                            <!-- <div class="accordion mb-3" id="modifierPricesAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingPrices">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrices" aria-expanded="false" aria-controls="collapsePrices">
                                            Show Modifier Prices
                                        </button>
                                    </h2>
                                    <div id="collapsePrices" class="accordion-collapse collapse" aria-labelledby="headingPrices" data-bs-parent="#modifierPricesAccordion">
                                        <div class="accordion-body" id="modifier-prices">
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <button type="submit" class="btn btn-primary" id="save-btn">Save</button>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-heading">
                        <h4>Modifier Group Management</h4>
                    </div>
                    <div class="main-content p-3">
                        <table data-page-length="-1" id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup"><i class="fas fa-arrows-alt"></i></th>
                                    <th scope="rowgroup">#</th>
                                    <th scope="rowgroup">Name</th>
                                    <th scope="rowgroup">Color</th>
                                    <th scope="rowgroup">Font Color</th>
                                    <th scope="rowgroup">Created by</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody id="modifier_group_rows">
                                @foreach ($modifiersGroup as $modifierGroup)
                                    <tr>
                                        <td><i style="color: #0980B2; cursor:grab; font-size: 24px;" class="fas fa-arrows-alt"></i></td>
                                        <td>
                                            <input type="hidden" class="modifier_group_id" value={{ $modifierGroup->id }} id="modifier_group_id_{{ $modifierGroup->id }}">
                                            <input type="number" class="order_index index_input" value="{{ $loop->iteration }}" id="modifier_group_index_{{ $modifierGroup->id }}">
                                        </td>
                                        <td>{{ $modifierGroup->name }}</td>
                                        <td><span style="display: inline-block; width: 60px; height: 30px; background-color: {{ $modifierGroup->color }}"></span></td>
                                        <td><span style="display: inline-block; width: 60px; height: 30px; background-color: {{ $modifierGroup->font_color }}"></span></td>
                                        <td>{{ $modifierGroup->createdBy->name ?? "" }}</td>

                                        <td>
                                            <span class="me-2">
                                                @can('modifier-edit')
                                                    <a aria-hidden="true" href="#" id="modifier-edit"
                                                        data-bs-toggle="modal" data-id="{{ $modifierGroup->id }}"
                                                        data-bs-target="#modifier-add-modal" data-bs-whatever="@mdo">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <span>
                                                @can('modifier-delete')
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteModifier"
                                                        data-bs-target="#deleteModifierModal"
                                                        data-url="{{ route('modifier-group-delete', ['id' => $modifierGroup->id]) }}">
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
    <div class="modal fade" id="deleteModifierModal" tabindex="-1" aria-labelledby="deleteRoleLabel"
        aria-hidden="true">
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
    <!-- Add Select2 CDN -->
    <link href="{{ asset('assets/css/plugins/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/plugins/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/Sortable.min.js') }}"></script>

    <script>
        @if(Session::has('errors'))
            var isValidationError = true;
        @else
            var isValidationError = false;
        @endif

        var modifierCreateUrl = '{{ route('modifier-group-create') }}';
        var modifierUpdateUrl = '{{ route('modifier-group-edit', ':id') }}';
        var modifierDetailUrl = '{{ route('modifier-group', ':id') }}';
        var updateIndexUrl = '{{ route('modifier-group-update-index') }}'

        $(document).ready(function() {
    // Initialize Select2 for multiple selection of modifiers
    $('#modifiers').select2();

    // Function to populate price inputs on modifiers change
    function populatePriceFields(selectedModifiers, modifierPrices = {}) {
        // Get the existing prices that are already set in the form
        const existingPrices = {};
        $('#modifier-prices input').each(function() {
            const modifierId = $(this).attr('name').match(/\d+/)[0]; // Extract modifier ID from input name
            existingPrices[modifierId] = $(this).val(); // Store existing price for each modifier
        });

        // Clear the price input fields
        $('#modifier-prices').empty();

        // Loop through selected options and create a price input for each selected modifier
        selectedModifiers.forEach(modifierId => {
            const modifierName = $('#modifiers option[value="'+modifierId+'"]').text();
            const modifierDefaultPrice = $('#modifiers option[value="'+modifierId+'"]').data('price');
            const modifierPrice = modifierPrices[modifierId] || existingPrices[modifierId] || modifierDefaultPrice || '';  // Prioritize saved, existing, or default price

            // Create a price input and label for each selected modifier in the same row
            const priceInputHtml = `
                <div class="d-flex align-items-center mb-3">
                    <label class="me-2 form-label">Price for ${modifierName}:</label>
                    <input type="text" name="modifier_prices[${modifierId}]" class="form-control" placeholder="Price" value="${modifierPrice}" required style="max-width: 150px;">
                </div>
            `;
            $('#modifier-prices').append(priceInputHtml);
        });
    }

    // Handle selection changes and dynamically add price inputs
    $('#modifiers').on('change', function() {
        const selectedModifiers = $(this).val();
        populatePriceFields(selectedModifiers);  // Maintain the existing prices, and only add new ones
    });

    // Use event delegation for dynamically loaded elements
    $(document).on('click', '#modifier-edit', function(e) {
        e.preventDefault(); // Prevent default action
        
        var id = $(this).data('id');
        console.log('Edit icon clicked, modifier group ID:', id); // Debugging

        var formUrl = modifierUpdateUrl.replace(':id', id);
        $('#modifier-form').attr('action', formUrl);
        $('#save-btn').text('Update');
        $('#form-title').text('Edit Modifier Group');

        var url = modifierDetailUrl.replace(':id', id);
        console.log('Fetching details from URL:', url); // Debugging

        var modifierRow = $(this).closest('tr');
        $("#modifier_group_rows").find('tr').each(function (i, tr) {
            this.style.removeProperty('background-color');
        });

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                console.log('AJAX Response:', response); // Debugging
                if (response.status === 'success') {
                    const data = response.data;

                    modifierRow.css('background-color', '#ffe');

                    // Populate form fields with data
                    $('#name').val(data.name).focus();
                    $('#color').val(data.color);
                    $('#font_color').val(data.font_color);
                    $('#status').val(data.status ? 1 : 0);

                    // Select modifiers in the dropdown
                    const selectedModifiers = data.modifiers_group_details.map(modifierDetail => modifierDetail.modifier_id);
                    $('#modifiers').val(selectedModifiers).trigger('change'); // Ensure dropdown reflects the selected modifiers

                    // Populate price fields from the response instead of default
                    const modifierPrices = {};
                    data.modifiers_group_details.forEach(modifierDetail => {
                        modifierPrices[modifierDetail.modifier_id] = modifierDetail.price;
                    });

                    populatePriceFields(selectedModifiers, modifierPrices);  // Inject prices from backend response
                } else {
                    console.error('Error fetching modifier group details:', response.message);
                }
            },
            error: function(err) {
                console.error('AJAX error:', err);
            }
        });
    });

    // Handle delete functionality in the modal
    $(document).on('click', '#deleteModifier', function(e) {
        var url = $(this).data('url');
        $('#deleteModifierBtn').attr('href', url);
    });

    function saveModifierGroupsIndexes() {
        setTimeout(() => {
            var categoryIndexes = [];

            $("#modifier_group_rows tr").map(function(i, trElm) {
                categoryIndexes.push({
                    modifierGroupId: $(trElm).find('.modifier_group_id').val(),
                    orderIndex: $(trElm).find('.order_index').val()
                })
            });

            $.ajax({
                url: updateIndexUrl,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    "_token": "{{ csrf_token() }}",
                    "mg": categoryIndexes
                }),
                success: function (response) {
                    if (response.status !== 'success') {
                        showAlert('error', response.message);
                    }
                },
                error: function () {
                    showAlert('error', response.message);
                }
            });

        }, 300);
    }

    // Sortable
    new Sortable(document.getElementById("modifier_group_rows"), {
        onSort: function (e) {
            $(e.target)
                .find("tr")
                .each(function (i, el) {
                    $(this)
                    .find(".order_index")
                    .val(i + 1);
                });

            saveModifierGroupsIndexes();
        }
    });

    $(document).on('change', '.order_index', function (e) {
        let $input = $(this);
        let inputIndex = parseInt($input.val(), 10);
        if (isNaN(inputIndex)) return;

        let $currentRow = $input.closest('tr');
        let $tbody = $currentRow.closest('tbody');
        let $rows = $tbody.find('tr').not($currentRow);

        let rowArray = $rows.toArray();
        let insertIndex = Math.max(0, Math.min(inputIndex - 1, rowArray.length));

        rowArray.splice(insertIndex, 0, $currentRow[0]);

        $tbody.empty().append(rowArray);

        $tbody.find('tr').each(function (i) {
            $(this).find('.order_index').val(i + 1);
        });

        saveModifierGroupsIndexes();
    });
});

    </script>
@endsection
