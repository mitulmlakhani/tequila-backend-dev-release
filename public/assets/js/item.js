var newMenuItemLastCategoryId = "";

/**Items List */
$(document).on('click', '#deleteItem', function(e) {
    var url = $(this).data('url');
    $('#deleteItemBtn').attr('href', url);
});

// $(document).ready(function () {
//     $('#kds_device_filter, #printer_device_filter').select2({
//         placeholder: "Select Devices",
//         allowClear: true,
//         width: '100%'
//     });
// });

/**Items Add */
function showFields() {
    if ($("#showfilds").is(":checked")) {
        $('#item_variant_div').show();
        $('#item_variant_add_new_btn').show();

        $("#price").prop('required', false);
        $("#color").prop('required', false);
        $("#font_color").prop('required', false);
        $("#quantity").prop('required', false);

        $('[name="variant_name[]"]').prop('required', true);
        $('input[name="variant_quantity[]"').prop('required', true);
        $('input[name="variant_price[]"').prop('required', true);

        $(".price_quantity").hide();

        if ($('#item_variant_table_body tr').length < 1) {
            myFunctionThree();
        }
    } else {
        $('#item_variant_div').hide();
        $('#item_variant_add_new_btn').hide();

        $("#price").prop('required', true);
        $("#color").prop('required', false);
        $("#font_color").prop('required', false);
        $("#quantity").prop('required', true);

        $('[name="variant_name[]"]').prop('required', false);
        $('input[name="variant_quantity[]"').prop('required', false);
        $('input[name="variant_price[]"').prop('required', false);

        $(".price_quantity").show();
    }
}

function myFunctionThree() {
    var table = document.getElementById("item_variant_table_body");
    var row   = table.insertRow(0);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    var cell4 = row.insertCell(3);
    var cell5 = row.insertCell(4);
    cell1.innerHTML =
        '<img src="/assets/images/dustbin.png" alt="dustbin" onclick="removeFunThree(this)" role="button">';
    cell2.innerHTML = variantDropdown;

    cell3.innerHTML =
        '<input type="number" placeholder="Quantity" name="variant_quantity[]" class="form-control" required min="0">';
    cell4.innerHTML =
        '<input type="text" placeholder="Price" name="variant_price[]" class="form-control numberInput" required min="0">';
    cell5.innerHTML = '<input type="file" name="variant_image[]" class="form-control" step="0.01" >';
}

function removeFunThree(This) {
    This.closest('tr').remove();

    if ($('#item_variant_table_body tr').length < 1) {
        $('#showfilds').prop('checked', false);
        showFields();
    }
}

$(document).on("change", '[name="variant_name[]"]', function (e) {
    var thisObj = $(this);
    var selectedVariant = $(this).val();
    if (selectedVariant == '')
        return false;

    $('[name="variant_name[]"]').not(this).each(function (index, value) {
        let variant = $(this);
        if (variant.val() == selectedVariant) {
            thisObj.val('');
            showAlert('error', 'Variant already Added');
            return false;
        }
    });
});

$(document).on("submit", "#ingredient_add", function (e) {
    e.preventDefault();
    var ingredientId = $("#ingredient_id").val();
    var ingredientUom = $("#ingredient_uom").val();
    var ingredientQuantity = $("#ingredient_quantity").val();
    var url = this.action;
    var data = {
        'ingredient_id': ingredientId,
        'ingredient_uom': ingredientUom,
        'ingredient_quantity': ingredientQuantity
    };
    $.ajax({
        type: 'POST',
        url: url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: data,
        success: function (response) {
            if (response.status == 'success') {
                var html =
                    `<tr>
                        <td><img src="/assets/images/dustbin.png" alt="delete" role="button" onclick="deleteIngredient(` +
                    response.data.item_ingredient.id + `,this)"></td>
                        <td>` + response.data.ingredient_name + `</td>
                        <td>` + response.data.uom_name + `</td>
                        <td>` + response.data.item_ingredient.quantity + `</td>
                    </tr>`;
                $('#ingredient_table').append(html);
                $('#ingredient_add')[0].reset();
                $('#ingredient_id').html(response.data.remaining_ingredients_html);
                $('#ingredient_id').selectpicker('refresh');
                $('#ingredient_uom').selectpicker('refresh');
            }
        }
    });
});

function deleteIngredient(itemingredientId, This) {
    $.ajax({
        type: 'POST',
        url: itemingredientDeleteUrl,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            'item_ingredient_id': itemingredientId
        },
        success: function (response) {
            if (response.status == 'success') {

                $('#ingredient_id').html(response.data.remaining_ingredients_html);
                $('#ingredient_add')[0].reset();
                $('#ingredient_id').selectpicker('refresh');
                $('#ingredient_uom').selectpicker('refresh');
                This.closest('tr').remove();
            }
        }
    });
}

$(document).ready(function(){

    const editor = new DataTable.Editor({
        ajax: itemListUrl,
        fields: [
            {
                label: 'Position',
                name: 'position',
            },
            {
                label: 'Name',
                name: 'name',
                // className: "form-control",
            },
            {
                label: 'category_id',
                name: 'category_id',
                // placeholder: 'Select category',
                type: "select",
                options: []
            },
            {
                label: 'Price',
                name: 'price'
            },
            {
                label: 'item_tag_id',
                name: 'item_tag_id',
                // placeholder: 'Select item type',
                type: "select",
                options: []
            },
            {
                label: 'Floor Item Icon',
                name: 'floor_item_icon_id',
                type: 'select',
                options: []
            },
            {
                label: 'Is Special',
                name: 'is_special',
                type: "select",
                options: [
                    { label: 'No', value: 0 },
                    { label: 'Yes', value: 1 }
                ],
                def: 0
            },
            { 
                label: 'Color', 
                name: 'color', 
                attr: { 
                    type: 'color', 
                    // style: 'width: 100%; height: auto;' 
                }, 
                def: '#2A2D33'

            },
            { 
                label: 'Font Color', 
                name: 'font_color', 
                attr: { 
                    type: 'color', 
                    // style: 'width: 100%; height: auto;' 
                }, 
                def: '#ffffff' 
            },
            {
                label: 'variant_group_id',
                name: 'variant_group_id',
                placeholder: 'Select Modifier',
                type: "select",
                options: []
            },
            {
                label: 'modifier_group_id',
                name: 'modifier_group_id',
                placeholder: 'Select Force Modifier',
                type: "select",
                options: []
            },
            {
                label: 'Time',
                name: 'time',
                def: 10
            },
            {
                label: 'Available In',
                name: 'available_in',
                type: "select",
                multiple: true,
                options: [
                    {'label': 'UberEats', 'value': 'ubereats'},
                    {'label': 'DoorDash', 'value': 'doordash'},
                    {'label': 'GrubHub', 'value': 'grubhub'}
                ]
            },
            {
                label: 'UOM',
                name: 'uom',
                type: "select",
                options: []
            },
            {
                label: 'KDS Devices',
                name: 'kds_devices',
                type: "select",
                multiple: true,
                options: []
            },
            {
                label: 'Printer Devices',
                name: 'printer_devices',
                type: "select",
                multiple: true,
                options: []
            },
            {
                label: 'Status',
                name: 'status',
                type: "select",
                options: {
                    'Active':1,
                    'In-Active':0
                },
                def: 1
            }
        ],
        table: '#item_list_table'
    });

    let currentRowId = null;
    let currentAction = null;
    let suppressIconChange = false;

    // Use the DataTables API to add a specific handler for changes on the color input
    editor.on('open', function (e, mode, action) {
        currentAction = action;
        suppressIconChange = true;

        if (mode === 'inline') {
            currentRowId = editor.modifier();
        }

        // Fetch and display icons matching the item's current name when the editor opens
        let currentName = editor.field('name').val() || '';
        fetchIcons(currentName);

        if(action === "create") {
            editor.field('category_id').val($("#category_filter").val() || newMenuItemLastCategoryId);
            // Hide is_special field on Create form
            editor.field('is_special').hide();
        } else {
            // Show is_special field on Edit form
            editor.field('is_special').show();
        }

        // Ensure color fields have valid hex values so HTML5 color inputs don't reset to default black (#000000)
        if (!editor.field('color').val()) {
            editor.field('color').set('#2A2D33');
        }
        if (!editor.field('font_color').val()) {
            editor.field('font_color').set('#ffffff');
        }

        setTimeout(() => {
            $('.DTE_Field select').on('select2:close', function () {
                editor.submit();
            });
        }, 0);

        window.initCheckboxSelect();
    });

    // Prevent closing inline editing if the user clicks or interacts with the onscreen keyboard
    editor.on('preBlur', function (e) {
        // If clicking inside the onscreen keyboard or preview, don't blur/close
        if (window.event) {
            var target = window.event.target;
            if (target && $(target).closest('#keyboardSection').length > 0) {
                return false;
            }
        }
        if (document.activeElement && $(document.activeElement).closest('#keyboardSection').length > 0) {
            return false;
        }
    });

    // Ensure the editor is refreshed to apply bindings
    editor.on('close', function (e, mode, action) {
        // Reset icons list after inline form closes
        let $input = $(editor.field('floor_item_icon_id').input());
        $input.siblings('.icon-previews').remove();

        suppressIconChange = true;
        editor.field('floor_item_icon_id').update([{ label: 'None', value: '' }]);
        setTimeout(() => { suppressIconChange = false; }, 0);
    });

    editor.on('preSubmit', function (e, data, action) {
        data._token = $('meta[name="csrf-token"]').attr('content');

        if (action === "create") {
            newMenuItemLastCategoryId = data.data[0]?.category_id;
            if (data.data && data.data[0]) {
                if (editor.field('printer_devices').displayed()) {
                    var pVal = editor.field('printer_devices').val();
                    data.data[0]['printer_devices'] = (pVal && pVal.length) ? pVal : [''];
                }
                if (editor.field('kds_devices').displayed()) {
                    var kVal = editor.field('kds_devices').val();
                    data.data[0]['kds_devices'] = (kVal && kVal.length) ? kVal : [''];
                }
            }
        }

        // Validate recipe for special items on edit
        if (action === "edit") {
            var rowIds = Object.keys(data.data || {}).filter(function(key) {
                return key.indexOf('row_') === 0;
            });
            for (var i = 0; i < rowIds.length; i++) {
                var rowData = data.data[rowIds[i]];
                if (!rowData) continue;

                // Explicitly send empty arrays as [''] so jQuery $.param does not strip them
                if (editor.field('printer_devices').displayed()) {
                    var pVal = editor.field('printer_devices').val();
                    rowData['printer_devices'] = (pVal && pVal.length) ? pVal : [''];
                }
                if (editor.field('kds_devices').displayed()) {
                    var kVal = editor.field('kds_devices').val();
                    rowData['kds_devices'] = (kVal && kVal.length) ? kVal : [''];
                }

                // Only validate when is_special field is being changed
                if (rowData.is_special !== undefined && parseInt(rowData.is_special) === 1) {
                    // Look up the recipe from the current row in the DataTable
                    var rowId = '#' + rowIds[i];
                    var rowTableData = datatable.row(rowId).data();
                    var recipe = rowTableData ? rowTableData.recipe : null;

                    if (!recipe || recipe === '' || recipe === '<p><br></p>' || recipe === '<br>') {
                        editor.field('is_special').error('Special items must have a recipe.');
                        showAlert('error', 'Special items must have a recipe.');
                        return false;
                    }
                }
            }
        }
    });

    editor.on('postSubmit', function (e, json, data, action) {
        if (action === 'edit' && json && json.data) {
            // Ensure datatable rows reflect newly updated relationships immediately
            $('#item_list_table').DataTable().ajax.reload(null, false);
        }
    });
    
    var category_options = [];
    var uom_options = [];
    var variant_group_options = [];
    var modifier_group_options = [];
    var item_tag_options = [];
    var kds_device_options = [];
    var printer_device_options = [];

    $.getJSON(itemsDynamicOptionsUrl+'?type=category', function(data) {
            var option = {};
            $.each(data, function(i,e) {
                option.label = e.category_name;
                option.value = e.id;
                category_options.push(option);
                option = {};
            });
        }
    ).done(function() {
        editor.field('category_id').update(category_options, newMenuItemLastCategoryId);
    });

    $.getJSON(itemsDynamicOptionsUrl+'?type=item_tag', function(data) {
            var option = {};
            $.each(data, function(i,e) {
                option.label = e.name;
                option.value = e.id;
                item_tag_options.push(option);
                option = {};
            });
        }
    ).done(function() {
        editor.field('item_tag_id').update(item_tag_options);
        if (item_tag_options.length > 0) {
            if (typeof defaultItemTag !== 'undefined' && defaultItemTag) {
                editor.field("item_tag_id").def(defaultItemTag);
            }
        }
    });

    let floor_item_icon_options = [{ label: 'None', value: '' }];
    $.getJSON('/restaurant/floor-item-icon-search', function(data) {
        $.each(data, function(i,e) {
            floor_item_icon_options.push({ label: e.name, value: e.id });
        });
    }).done(function() {
        editor.field('floor_item_icon_id').update(floor_item_icon_options);
    });

    $.getJSON(itemsDynamicOptionsUrl+'?type=uom', function(data) {
            var option = {};
            $.each(data, function(i,e) {
                option.label = e.name;
                option.value = e.id;
                uom_options.push(option);
                option = {};
            });
        }
    ).done(function() {
        editor.field('uom').update(uom_options);
    });

    $.getJSON(itemsDynamicOptionsUrl + '?type=kds_device', function (data) {
        kds_device_options.push({ label: 'Select All', value: 'select_all' });
        $.each(data, function (i, e) {
            kds_device_options.push({ label: e.name, value: e.id });
        });
        editor.field('kds_devices').update(kds_device_options);
    });
    
    $.getJSON(itemsDynamicOptionsUrl + '?type=printer_device', function (data) {
        printer_device_options.push({ label: 'Select All', value: 'select_all' });
        $.each(data, function (i, e) {
            printer_device_options.push({ label: e.name, value: e.id });
        });
        editor.field('printer_devices').update(printer_device_options);
    });

    $.getJSON(itemsDynamicOptionsUrl+'?type=variant_group', function(data) {
        var option = {};
        option.label = 'Select Modifier';
        option.value = '';
        variant_group_options.push(option);
        option = {};
        $.each(data, function(i,e) {
            option.label = e.name;
            option.value = e.id;
            variant_group_options.push(option);
            option = {};
        });
    }
    ).done(function() {
        editor.field('variant_group_id').update(variant_group_options);
    });

    $.getJSON(itemsDynamicOptionsUrl+'?type=modifier_group', function(data) {
        var option = {};
        option.label = 'Select Force Modifier';
        option.value = '';
        modifier_group_options.push(option);
        option = {};
        $.each(data, function(i,e) {
            option.label = e.name;
            option.value = e.id;
            modifier_group_options.push(option);
            option = {};
        });

    }
    ).done(function() {
        editor.field('modifier_group_id').update(modifier_group_options);
    });

    

    editor.field('name').input().addClass('form-control');

    let nameInputTypingTimer;
    const nameInputDoneTypingInterval = 1000;

    function fetchIcons(name) {
        suppressIconChange = true;

        $.ajax({
            url: '/restaurant/floor-item-icon-search',
            type: 'GET',
            data: { name: name },
            success: function(response) {
                let options = [{ label: 'None', value: '' }];
                let previewHtml = '<div class="icon-previews mt-2 d-flex flex-wrap" style="gap: 5px;">';
                
                if (response && response.length > 0) {
                    $.each(response, function(i, e) {
                        let iconUrl = e.icon_url ? e.icon_url : ('/' + e.icon);
                        options.push({ label: e.name, value: e.id });
                        previewHtml += `<img src="${iconUrl}" data-id="${e.id}" class="icon-preview-item border rounded p-1 shadow-sm" style="width:40px; height:40px; object-fit:contain; cursor:pointer;" title="${e.name}" />`;
                        if (i == 0) {
                            editor.field('floor_item_icon_id').set(e.id);
                        }
                    });
                }
                previewHtml += '</div>';

                editor.field('floor_item_icon_id').update(options);

                // Wait for the editor to finish updating the DOM
                setTimeout(() => {
                    let $input = $(editor.field('floor_item_icon_id').input());
                    // Remove old previews if any exist next to the input
                    $input.siblings('.icon-previews').remove(); 
                    
                    if (response && response.length > 0) {
                        // Append the preview images directly below the select input
                        $input.after(previewHtml);
                        
                        // Allow clicking a preview to select it in the dropdown
                        $input.siblings('.icon-previews').find('.icon-preview-item').on('click', function(e) {
                            e.stopPropagation();
                            let id = $(this).data('id');
                            editor.field('floor_item_icon_id').set(id);
                            $input.trigger('change');
                            
                            // Highlight selected
                            $input.siblings('.icon-previews').find('.icon-preview-item').css('border-color', '').removeClass('bg-light');
                            $(this).css('border-color', '#0d6efd').addClass('bg-light');
                        });
                    }

                    suppressIconChange = false;
                }, 50);
            },
            error: function(xhr) {
                console.error("Error fetching floor item icons:", xhr);
                // Release suppression even on failure so the field still works.
                suppressIconChange = false;
            }
        });
    }

    editor.field('name').input().on('keyup', function () {
        clearTimeout(nameInputTypingTimer);
        let currentVal = $(this).val().trim();
        nameInputTypingTimer = setTimeout(function () {
            if (currentVal !== '') {
                fetchIcons(currentVal);
            }
        }, nameInputDoneTypingInterval);
    }).on('keydown', function () {
        clearTimeout(nameInputTypingTimer);
    });

    editor.field('position').input().addClass('form-control');
    editor.field('category_id').input().addClass('form-select');
    editor.field('item_tag_id').input().addClass('form-select');
    editor.field('floor_item_icon_id').input().addClass('form-select').on('change', function() {
        // Ignore change events fired by programmatic .update()/.set() calls (e.g. options refresh on open/close)
        if (suppressIconChange) return;

        // Auto-save the icon selection while editing (inline row edit or main edit modal).
        // Skipped during 'create' so we don't submit a not-yet-created item.
        if (editor.mode() === 'inline' || currentAction === 'edit') {
            editor.submit();
        }
    });
    editor.field('status').input().addClass('form-select');
    editor.field('variant_group_id').input().addClass('form-select');
    editor.field('modifier_group_id').input().addClass('form-select');
    editor.field('price').input().addClass('form-control numberInput');
    editor.field('color').input().addClass('form-control');
    editor.field('font_color').input().addClass('form-control');
    editor.field('time').input().addClass('form-control numberInput');
    editor.field("available_in").input().addClass("form-select select2-multiple checkbox-select").css("min-width", "125px");
    editor.field('uom').input().addClass('form-select');
    editor.field('kds_devices').input().addClass('form-select select2-multiple checkbox-select');
    editor.field('printer_devices').input().addClass('form-select select2-multiple checkbox-select');
    editor.field('is_special').input().addClass('form-select');

    // Filters for Category
    $('#category_filter').on('change', function () {
        $('#item_tag_filter').val('');
        $('#kds_device_filter, #printer_device_filter').val('');
        let category_id = $('#category_filter').val();

        window.defaultItemTag = categories.find(c => c.id == category_id)?.item_tag_id || itemTags.find(t => t.name == 'Food')?.id || "";
        editor.field("item_tag_id").def(defaultItemTag);

        // Reload DataTable with filters applied
        datatable.ajax.url(itemListUrl + '?category_id=' + category_id).load();
        history.replaceState(null, "", itemListUrl + '/' + category_id);
    });

    const confirmationModalId = "#confirmationModal";
    const warningModalId = "#warningModal";

    $('#item_tag_filter').on('change', function () {
        const selectedCategory = $('#category_filter').val(); // Get the selected category ID
        const selectedItemType = $(this).val(); // Get the selected item type ID
        $('#item_tag_filter').val('');

        if (!selectedCategory) {
            // Show a warning if no category is selected
            $(this).val(''); // Reset the dropdown selection
            $(warningModalId).modal('show');
            return;
        }

        if (!selectedItemType) return;

        // Show confirmation modal for updating item type
        $(confirmationModalId).find('.modal-title').text('Confirm Update');
        $(confirmationModalId).find('.modal-body').text('Are you sure you want to update the item type for all items in the selected category?');
        $(confirmationModalId).modal('show');

        $(confirmationModalId).off('click', '#confirmAction').on('click', '#confirmAction', function () {
            $(confirmationModalId).modal('hide'); // Hide the confirmation modal
            // AJAX request to update the item type
            $.ajax({
                type: 'POST',
                url: bulk_update, // Endpoint for updating items
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                data: {
                    type: 'item_type',
                    value: selectedItemType, // Item type ID to update
                    category_id: selectedCategory // Selected category ID
                },
                success: function (response) {
                    if (response.status === 'success') {
                        showAlert('success', response.message);
                        // Optionally, reload the data table to reflect the changes
                        $('#item_list_table').DataTable().ajax.reload();
                        // editor.create();
                    } else {
                        showAlert('error', response.message);
                    }
                },
                error: function () {
                    showAlert('error', 'An error occurred while updating the item type.');
                }
            });
        });
    });
    

    $('#kds_device_filter, #printer_device_filter').on('select2:closing', function () {
        const selectedCategory = $('#category_filter').val();
        let selectedDevices = $(this).val() || []; 
        const selectElement = $(this);
        const deviceType = selectElement.attr('id') === 'kds_device_filter' ? 'kds_device' : 'printer_device';
        const deviceLabel = deviceType === 'kds_device' ? 'KDS' : 'PRINTER';
        
        if (!selectedCategory) {
            showAlert('error', 'Please select a category before choosing devices.');
            selectElement.val('').trigger('change');
            return;
        }

        // Filter out 'select_all'
        selectedDevices = (Array.isArray(selectedDevices) ? selectedDevices : [selectedDevices]).filter(v => v && v !== 'select_all');
        
        if (selectedDevices.length === 0) { 
            // Allow empty selection: confirm removal of all devices of this type for category items
            $('#confirmationModal .modal-body').text(`Remove ALL ${deviceLabel} devices from all items in this category?`);
            $('#confirmationModal').modal('show');

            $('#confirmAction').off('click').on('click', function () {
                $('#confirmationModal').modal('hide');

                $.ajax({
                    type: 'POST',
                    url: bulk_update,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data: {
                        type: deviceType,
                        value: [],
                        category_id: selectedCategory
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            showAlert('success', response.message);
                            $('#item_list_table').DataTable().ajax.reload();
                        } else {
                            showAlert('error', response.message);
                        }
                    },
                    error: function () {
                        showAlert('error', 'An error occurred while updating devices.');
                    }
                });
            });
            return;
        }
        
        const allDeviceIds = [];
        selectElement.find('option').each(function () {
            const val = $(this).val();
            if (val && val !== 'select_all') {
                allDeviceIds.push(val.toString());
            }
        });

        const isAllSelected = allDeviceIds.length > 0 && allDeviceIds.every(id => selectedDevices.map(String).includes(id));

        if (isAllSelected) {
            // Confirmation for "Apply ALL"
            $('#confirmationModal .modal-body').text(`Apply ALL ${deviceLabel} devices to all items in this category?`);
            $('#confirmationModal').modal('show');

            $('#confirmAction').off('click').on('click', function () {
                $('#confirmationModal').modal('hide');

                // AJAX to apply ALL devices
                $.ajax({
                    type: 'POST',
                    url: bulk_update,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data: {
                        type: deviceType,
                        value: allDeviceIds,
                        category_id: selectedCategory
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            showAlert('success', response.message);
                            $('#item_list_table').DataTable().ajax.reload();
                        } else {
                            showAlert('error', response.message);
                        }
                    },
                    error: function () {
                        showAlert('error', 'An error occurred while updating devices.');
                    }
                });
            });
    
        } else {
            // Normal selection (not select all)
            $('#confirmationModal .modal-body').text(`Apply selected ${deviceLabel} devices to all items in this category?`);
            $('#confirmationModal').modal('show');

            $('#confirmAction').off('click').on('click', function () {
                $('#confirmationModal').modal('hide');

                $.ajax({
                    type: 'POST',
                    url: bulk_update,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data: {
                        type: deviceType,
                        value: selectedDevices,
                        category_id: selectedCategory
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            showAlert('success', response.message);
                            $('#item_list_table').DataTable().ajax.reload();
                        } else {
                            showAlert('error', response.message);
                        }
                    },
                    error: function () {
                        showAlert('error', 'An error occurred while updating devices.');
                    }
                });
            });
        }
    });
    
    const datatable = $('#item_list_table').DataTable({
        ajax: {
            url: itemListUrl + '/' + categoryId,
            dataSrc: function(json) {
                $("#category_modifier_list").html('');

                (json?.categoryModifiers || []).forEach(function (item) {
                    $("#category_modifier_list").append('<div>' + item + '</div>');
                });

                return json.data;
            }
        },

        stateSave: true,
        stateSaveCallback: function (settings, data) {
            localStorage.setItem('items_datatable', JSON.stringify(data));
        },
        stateLoadCallback: function (settings) {
            return JSON.parse(localStorage.getItem('items_datatable'));
        },
        // rowReorder: {
        //     dataSrc: 'position',
        //     selector: '.row-reorder',
        //     editor: editor,
        // },
        processing: true,
        serverSide: true,
        pageLength: 25,
        scrollCollapse: true,
        scrollX: true,
        scrollY: '92vh', // optional, scrollable area
        fixedHeader: {
            header: false,
            headerOffset: 60 // Adjust based on your header height
        },
        fixedColumns: {
            left: 3,
        },
        columns: [
            // { data: 'action' },
            {
                data: null,
                defaultContent: '<i class="fa fa-trash"/>',
                className: 'row-remove dt-center',
                orderable: false
            },
            // {
            //     data: null,
            //     defaultContent: '<i class="fa fa-arrows"/>',
            //     className: 'row-reorder dt-center', 
            //     orderable: false
            // },
            { data: 'position' },
            { data: 'name' },
            { data: 'category_id', name: 'category_id',render: renderCategoryColumn },
            { data: 'price' },
            { data: 'item_tag_id', name: 'item_tag_id',render: renderItemTagColumn },
            { data: 'is_special', render: renderIsSpecialColumn },
            {
                data: 'image_url',
                render: function(data, type, row) {
                    let html = '<div class="item-image-container" style="text-align: center;">';
                    if (data && !data.includes('no-image.png')) {
                        let checked = (row.show_item_image_on_pos == 1 || row.show_item_image_on_pos == true) ? 'checked' : '';
                        html += '<a target="_blank" href="'+data+'"><img src="' + data + '" style="max-width: 75px; max-height: 75px; display: block; margin: 0 auto 5px;" /></a>';
                        html += '<div class="mt-3 d-flex justify-content-center align-items-center" style="gap: 5px;">';
                        html += '<input type="checkbox" class="item-show-image-pos" data-item-id="' + row.id + '" ' + checked + ' title="Show Image On POS" style="width: 32px; height: 28px; margin: 0; cursor: pointer;">';
                        html += '<label class="btn btn-sm btn-outline-primary" style="padding: 2px 6px; font-size: 16px; margin-bottom: 0px; width: 32px; height: 28px;">';
                        html += '<i class="fa fa-upload"></i>';
                        html += '<input type="file" class="item-image-upload" data-item-id="' + row.id + '" accept="image/*" style="display: none;" />';
                        html += '</label>';
                        html += '<badge type="button" class="btn btn-sm btn-outline-danger item-image-delete" data-item-id="' + row.id + '" style="padding: 2px 6px; font-size: 16px; width: 32px; height: 28px;"><i class="fa fa-trash"></i></badge>';
                        html += '</div>';
                    } else {
                        html += '<label class="btn btn-sm btn-outline-secondary" style="padding: 4px 8px;">';
                        html += '<i class="fa fa-upload"></i> Upload';
                        html += '<input type="file" class="item-image-upload" data-item-id="' + row.id + '" accept="image/*" style="display: none;" />';
                        html += '</label>';
                    }
                    html += '</div>';
                    return html;
                },
                orderable: false
            },
            {
                data: 'floor_item_icon_url',
                editField: 'floor_item_icon_id',
                render: function(data, type, row) {
                    if (data && data !== '-') {
                        return '<img src="' + data + '" height="75" style="object-fit: contain;" />';
                    }
                    return '<button class="badge bg-dark mt-1 associate_floor_item"><i class="fa fa-plus"></i></button>';
                },
                orderable: false
            },
            { 
                data: 'color',
                render: function(data, type, row) {
                    var color = data || '#2A2D33';
                    if (type === 'display') {
                        return '<input type="color" class="form-control form-control-color item-color-picker" data-item-id="' + row.id + '" data-field="color" value="' + color + '" style="width: 38px; height: 28px; padding: 2px; cursor: pointer;" title="Choose Color">';
                    }
                    return color;
                }
            },
            { 
                data: 'font_color',
                render: function(data, type, row) {
                    var fontColor = data || '#ffffff';
                    if (type === 'display') {
                        return '<input type="color" class="form-control form-control-color item-color-picker" data-item-id="' + row.id + '" data-field="font_color" value="' + fontColor + '" style="width: 38px; height: 28px; padding: 2px; cursor: pointer;" title="Choose Font Color">';
                    }
                    return fontColor;
                }
            },
            {
                data: 'modifier_group_names',
                render: function (data, type, row) {
                    let listHtml = '';
                    if (Array.isArray(data) && data.length) {
                        listHtml = data.map(name => `<div>${name}</div>`).join('');
                    } else {
                        listHtml = '';
                    }
        
                    let copyBtnHtml = '';
                    if (window.copiedModifierItemId == row.id) {
                        copyBtnHtml = `<button class="badge bg-danger mt-1 cancel_copy_modifier ms-1" data-id="${row.id}" style="cursor:pointer; font-size: 20px;" title="Cancel Copy"><i class="fa fa-times"></i></button>`;
                    } else if (window.copiedModifierItemId) {
                        copyBtnHtml = `<button class="badge bg-info mt-1 paste_modifier ms-1" data-id="${row.id}" style="cursor:pointer; font-size: 20px;" title="Paste Modifiers"><i class="fa fa-paste"></i></button>`;
                    } else {
                        copyBtnHtml = `<button class="badge bg-secondary mt-1 copy_modifier ms-1" data-id="${row.id}" style="cursor:pointer; font-size: 20px;" title="Copy Modifiers"><i class="fa fa-copy"></i></button>`;
                    }

                    listHtml += `<div>
                    <button class="badge bg-dark mt-1 associate_modifier" data-id="${row.id}" style="cursor:pointer; font-size: 20px; data-category_id=""><i class="fa fa-plus"></i></button>
                    ${copyBtnHtml}
                    </div>`;
                    return listHtml;
                }
            },
            { data: 'associate_ingredient' },
            { data: 'variants' },
            // { data: 'variant_group_id',render: renderVariantGroupColumn },
            // { data: 'modifier_group_id',render: renderModifierGroupColumn },
            { data: 'time' },
            { 
                data: 'recipe',
                className: 'recipe-cell',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var content = '';
                        if (data) {
                            var strippedText = $('<div>').html(data).text();
                            var truncated = strippedText.length > 3 ? strippedText.substring(0, 3) + '...' : strippedText;
                            content = '<span class="recipe-preview">' + truncated + '</span>';
                        } else {
                            content = '<span class="recipe-preview text-muted">No recipe</span>';
                        }
                        return '<div class="edit-recipe-btn text-center" data-item-id="' + row.id + '" data-recipe="' + (data ? encodeURIComponent(data) : '') + '" style="cursor:pointer; font-size:20px;">' + 
                               content + ' <br /><button class="badge bg-dark mt-1" style="cursor:pointer; font-size: 20px;"><i class="fa fa-plus"></i></button></div>';
                    }
                    return data;
                }
            },
            { data: 'available_in',render: renderAvailableInColumn },
            { data: 'uom',render: renderUomColumn },
            { data: 'kds_devices', name: 'kds_devices',render: renderkdsDeviceColumn },
            { data: 'printer_devices', name: 'printer_devices',render: renderprinterDeviceColumn },
            { data: 'status' ,render: renderStatusColumn},
            { data: 'id', visible: false, searchable: false }
        ],
        columnDefs: [
            {
                "targets": 3, // your case first column
                "className": "text-center"
           }
        ],
        buttons: [
            {
                extend: 'createInline',
                className: 'btn btn-primary addNewItemInRow',
                editor: editor,
                formOptions: {
                    focus: 'name'
                }
            }
        ],
        dom: 'Bfrtip',
        language: {
            paginate: {
                next: '<i class="fas fa-angle-right"></i>', // or '→'
                previous: '<i class="fas fa-angle-left"></i>' // or '←'
            }
        },
        scrollCollapse: true,
        scrollX: true,
        
        "paging": true,
        "ordering": false,
        "searching": true,
        "lengthChange": true,
        "sScrollX": "100%",
        "sScrollXInner": "110%",
        "bScrollCollapse": true,
    });

    let firstDraw = true;
    datatable.on('draw', function () {
        if(firstDraw) {
            $(".addNewItemInRow").click();
        }

        firstDraw = false;
    });

    datatable.on('error', function (e, settings, techNote, message) {
        console.log('An error occurred:', message);
    });

    // Handle interactive color picker changes directly
    $(document).on('change', '.item-color-picker', function (e) {
        e.stopPropagation();
        var itemId = $(this).data('item-id');
        var field = $(this).data('field');
        var val = $(this).val();

        if (!itemId || !field) return;

        var postData = {
            action: 'edit',
            _token: $('meta[name="csrf-token"]').attr('content'),
            data: {}
        };
        postData.data['row_' + itemId] = {};
        postData.data['row_' + itemId][field] = val;

        $.ajax({
            url: itemListUrl,
            type: 'POST',
            data: postData,
            success: function (response) {
                var row = datatable.row('#row_' + itemId);
                if (row && row.length) {
                    var rowData = row.data();
                    if (rowData) {
                        rowData[field] = val;
                    }
                }
            },
            error: function (xhr) {
                showAlert('error', 'Failed to update ' + (field === 'color' ? 'color' : 'font color'));
            }
        });
    });

    datatable.on('click', 'tbody td:not(:first-child):not(:nth-child(8))', function (e) {
        // Skip inline edit for image column (8th column)
        if ($(e.target).closest('.item-image-container').length > 0) {
            return;
        }
        
        // Skip inline edit for color pickers (handled directly)
        if ($(e.target).closest('.item-color-picker').length > 0) {
            return;
        }

        // Skip inline edit for recipe column - handled by modal
        if ($(e.target).closest('.edit-recipe-btn').length > 0 || $(this).hasClass('recipe-cell')) {
            return;
        }
        
        var cell = datatable.cell(this);
        if (!cell.length) return; // Not a valid Datatable cell
        
        var colIdx = cell.index().column;
        var dataSrc = datatable.settings()[0].aoColumns[colIdx].data;

        // Skip inline edit on color columns since they have interactive inputs
        if (dataSrc === 'color' || dataSrc === 'font_color') {
            return;
        }

        try {
            if (dataSrc === 'floor_item_icon_url') {
                editor.inline(this, 'floor_item_icon_id', {submitOnBlur: true});
            } else {
                editor.inline(this, {submitOnBlur: true});
            }
        } catch (error) {
            // Ignore columns that are not mapped to an editor field
            console.log("Column not inline editable");
        }
    });

    function decodeRecipeHtml(value) {
        if (!value) {
            return '';
        }

        try {
            value = decodeURIComponent(value);
        } catch (e) {}

        var textarea = document.createElement('textarea');

        textarea.innerHTML = value;

        return textarea.value;
    }

    // Prevent Bootstrap 5 modal focus trap from blocking Summernote dialogs/popovers
    // Must use native capture-phase listener since Bootstrap 5 uses native addEventListener
    document.addEventListener('focusin', function (e) {
        if (e.target.closest('.note-modal, .note-popover, .note-dialog, .note-modal-backdrop')) {
            e.stopImmediatePropagation();
        }
    }, true);

    // Handle recipe edit via modal
    $('#item_list_table').on('click', '.edit-recipe-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var itemId = $(this).data('item-id');
        var recipeData = $(this).data('recipe');

        // Properly decode recipe
        var recipe = decodeRecipeHtml(recipeData);

        $('#recipe_item_id').val(itemId);

        // Initialize Summernote if not already done
        if (!$('#recipe_editor').hasClass('summernote-initialized')) {
            $('#recipe_editor').summernote({
                placeholder: 'Enter recipe instructions...',
                tabsize: 2,
                height: 300,
                dialogsInBody: true,
                callbacks: {
                    onImageUpload: function (files) {
                        var maxSizeBytes = 200 * 1024; // 200 KB
                        var $editor = $(this);
                        for (var i = 0; i < files.length; i++) {
                            var file = files[i];
                            if (file.size > maxSizeBytes) {
                                var sizeKB = (file.size / 1024).toFixed(1);
                                showAlert('error', 'Image "' + file.name + '" (' + sizeKB + ' KB) exceeds the 200 KB size limit.');
                                continue;
                            }

                            // Read and insert image if within limit
                            (function (imgFile) {
                                var reader = new FileReader();
                                reader.onload = function (e) {
                                    $editor.summernote('insertImage', e.target.result, imgFile.name);
                                };
                                reader.readAsDataURL(imgFile);
                            })(file);
                        }
                    },
                    onPaste: function (e) {
                        var clipboardData = (e.originalEvent || e).clipboardData;
                        if (clipboardData && clipboardData.items) {
                            for (var i = 0; i < clipboardData.items.length; i++) {
                                var item = clipboardData.items[i];
                                if (item.type && item.type.indexOf('image') !== -1) {
                                    var file = item.getAsFile();
                                    if (file && file.size > 200 * 1024) {
                                        e.preventDefault();
                                        var sizeKB = (file.size / 1024).toFixed(1);
                                        showAlert('error', 'Pasted image (' + sizeKB + ' KB) exceeds the 200 KB size limit.');
                                        return;
                                    }
                                }
                            }
                        }
                    }
                }
            });

            $('#recipe_editor').addClass('summernote-initialized');
        }

        // Set HTML content
        $('#recipe_editor').summernote('code', recipe);

        // Correct way to focus Summernote
        $('#recipe_editor').summernote('focus');

        $('#recipeModal').modal('show');

        // Watch for fullscreen toggle on the Summernote editor (once initialized)
        var $noteEditor = $('#recipe_editor').next('.note-editor');
        if ($noteEditor.length && !$noteEditor.data('fs-observer')) {
            var fsObserver = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    if (mutation.attributeName === 'class') {
                        var isFullscreen = $noteEditor.hasClass('fullscreen');
                        if (isFullscreen) {
                            // Save and override body zoom so position:fixed covers full viewport
                            document.body.dataset.savedZoom = document.body.style.zoom || '';
                            document.body.style.zoom = '100%';
                            // Neutralize modal stacking context so position:fixed works from viewport
                            $('#recipeModal').addClass('summernote-fs-active');
                            $('#recipeModal .modal-dialog').css('transform', 'none');
                            $('.modal-backdrop').hide();
                        } else {
                            // Restore body zoom
                            document.body.style.zoom = document.body.dataset.savedZoom || '';
                            $('#recipeModal').removeClass('summernote-fs-active');
                            $('#recipeModal .modal-dialog').css('transform', '');
                            $('.modal-backdrop').show();
                        }
                    }
                });
            });
            fsObserver.observe($noteEditor[0], { attributes: true, attributeFilter: ['class'] });
            $noteEditor.data('fs-observer', true);
        }
    });

    // Exit fullscreen if recipe modal is closed
    $('#recipeModal').on('hidden.bs.modal', function () {
        var $noteEditor = $('#recipe_editor').next('.note-editor');
        if ($noteEditor.hasClass('fullscreen')) {
            $('#recipe_editor').summernote('fullscreen.toggle');
        }
        // Restore body zoom and modal styles
        if (document.body.dataset.savedZoom !== undefined) {
            document.body.style.zoom = document.body.dataset.savedZoom || '';
        }
        $('#recipeModal').removeClass('summernote-fs-active');
        $('#recipeModal .modal-dialog').css('transform', '');
        $('.modal-backdrop').show();
    });

    // Save recipe
    $('#saveRecipeBtn').on('click', function () {

        var itemId = $('#recipe_item_id').val();
        var recipe = $('#recipe_editor').summernote('code');

        // Clean empty content
        if (
            recipe === '<p><br></p>' ||
            recipe === '<br>' ||
            recipe.trim() === ''
        ) {
            recipe = '';
        }

        $.ajax({
            type: 'POST',
            url: itemListUrl,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: {
                action: 'edit',
                data: {
                    ['row_' + itemId]: {
                        recipe: recipe
                    }
                }
            },
            success: function (response) {

                if (response.error) {
                    showAlert('error', response.error);
                } else {

                    showAlert('success', 'Recipe updated successfully');

                    $('#recipeModal').modal('hide');

                    datatable.ajax.reload(null, false);
                }
            },
            error: function (xhr) {
                showAlert('error', 'Failed to update recipe');
            }
        });
    });

    // Handle image upload
    $('#item_list_table').on('change', '.item-image-upload', function (e) {
        var itemId = $(this).data('item-id');
        var file = this.files[0];
        
        if (!file) return;
        
        // Validate file type
        var allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!allowedTypes.includes(file.type)) {
            showAlert('error', 'Please select a valid image file (JPEG, PNG, or JPG).');
            return;
        }
        
        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            showAlert('error', 'Image size must be less than 5MB.');
            return;
        }
        
        var formData = new FormData();
        formData.append('image', file);
        
        var url = itemUploadImageUrl.replace(':itemId', itemId);
        
        $.ajax({
            type: 'POST',
            url: url,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    datatable.ajax.reload(null, false); // Reload without resetting pagination
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function (xhr) {
                var message = 'Failed to upload image.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert('error', message);
            }
        });
    });

    // Handle image delete
    $('#item_list_table').on('click', '.item-image-delete', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        var itemId = $(this).data('item-id');
        
        if (!confirm('Are you sure you want to delete this image?')) {
            return;
        }
        
        var url = itemDeleteImageUrl.replace(':itemId', itemId);
        
        $.ajax({
            type: 'DELETE',
            url: url,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    datatable.ajax.reload(null, false); // Reload without resetting pagination
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function (xhr) {
                var message = 'Failed to delete image.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert('error', message);
            }
        });
    });

    // Handle show on POS checkbox change
    $('#item_list_table').on('change', '.item-show-image-pos', function (e) {
        var itemId = $(this).data('item-id');
        var isChecked = $(this).is(':checked') ? 1 : 0;
        var url = itemUpdateShowImageOnPosUrl.replace(':itemId', itemId);
        
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                show_item_image_on_pos: isChecked
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.status === 'success') {
                    showAlert('success', response.message);
                } else {
                    showAlert('error', response.message);
                    // Revert checkbox state
                    $(this).prop('checked', !isChecked);
                }
            }.bind(this),
            error: function (xhr) {
                var message = 'Failed to update setting.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showAlert('error', message);
                // Revert checkbox state
                $(this).prop('checked', !isChecked);
            }.bind(this)
        });
    });

    
    editor.on ( 'submitSuccess', function ()
    {
        showAlert('success', 'Successfully updated');    
        $(".addNewItemInRow").click();
    });

    // Delete row
    $('#item_list_table').on('click', 'tbody td.row-remove', function (e) {
        var row = $(this).closest('tr');  // Get the row
        var itemName = row.find('td:eq(2)').text();  // Assuming the item name is in the second column (index 1)
    
        editor.remove(this.parentNode, {
            title: 'Delete record',
            message: 'Are you sure you wish to delete the item '+ itemName + '?',
            buttons: 'Delete'
        });
    });

    function renderCategoryColumn(data, type, row) {
        // 'display' type is used for rendering in the table
        if (type === 'display') {
            // Assuming row.category is an object with 'id' and 'name'
            return row.category_name;
        }
        // 'filter', 'sort', and 'type' types use the 'id' for operations
        return row.category_id;
    }

    function renderItemTagColumn(data, type, row) {
        // 'display' type is used for rendering in the table
        if (type === 'display') {
            // Assuming row.category is an object with 'id' and 'name'
            return row.item_tag;
        }
        // 'filter', 'sort', and 'type' types use the 'id' for operations
        return row.item_tag_id;
    }

    function renderIsSpecialColumn(data, type, row) {
        if (type === 'display') {
            return parseInt(data) === 1 ? 'Yes' : 'No';
        }
        return data;
    }

    function renderStatusColumn(data, type, row) {
        // 'display' type is used for rendering in the table
        if (type === 'display') {
            // Assuming row.category is an object with 'id' and 'name'
            return row.status_value;
        }
        // 'filter', 'sort', and 'type' types use the 'id' for operations
        return row.status;
    }

    function renderAvailableInColumn(data, type, row) {
        if (type === 'display') {
            return row.available_in_values ? row.available_in_values.split(', ').join('<br>') : 'N/A';
        }

        return data;
    }

    function renderUomColumn(data, type, row) {
        if (type === 'display') {
            return row.uom_value;
        }
        return row.uom;
    }

    function renderkdsDeviceColumn(data, type, row) {
        if (type === 'display') {
            return row.kds_device_names ? row.kds_device_names.split(', ').join('<br>') : 'N/A';
        }
        return data;
    }

    function renderprinterDeviceColumn(data, type, row) {
        if (type === 'display') {
            return row.printer_device_names ? row.printer_device_names.split(', ').join('<br>') : 'N/A';
        }
        return data;
    }

    function renderVariantGroupColumn(data, type, row) {
        if (type === 'display') {
            return row.variant_group_value;
        }
        return row.uom;
    }

    function renderModifierGroupColumn(data, type, row) {
        if (type === 'display') {
            return row.modifier_group_value;
        }
        return row.uom;
    }
});

var closeModifierModal = true;

function saveModifierFormAfterSort() {
  closeModifierModal = false;

  setTimeout(function () {
    $("#save_associate_modifiers_form").submit();
  }, 500);
}

function makeGroupModifierFormSortable() {
  $(".sortable").each(function (e, el) {
    new Sortable(el, {
      onSort: function (e) {
        $(e.target)
          .find("tr")
          .each(function (i, el) {
            $(this)
              .find(".item_index")
              .val(i + 1);
          });

          saveModifierFormAfterSort();
      },
    });
  });

  new Sortable(document.getElementById("accordionExample"), {
    onSort: function (e) {
        sortModifierGroup();
        saveModifierFormAfterSort();
      },
  });
}

function sortModifierGroup() {
    $('.accordion').find(".accordion-item")
        .each(function (i, el) {
          $(this)
            .find(".order_index")
            .val(i + 1);
        });
}

/**Items Add */
$(document).ready(function(){
    window.copiedModifierItemId = null;

    $(document).on("click", ".copy_modifier", function (e) {
        window.copiedModifierItemId = $(this).data('id');
        $('#item_list_table').DataTable().ajax.reload(null, false);
        $("#header_cancel_copy_modifier_btn").show();
    });

    $(document).on("click", ".cancel_copy_modifier", function (e) {
        window.copiedModifierItemId = null;
        $('#item_list_table').DataTable().ajax.reload(null, false);
        $("#header_cancel_copy_modifier_btn").hide();
    });

    $(document).on("click", ".paste_modifier", function (e) {
        let targetItemId = $(this).data('id');
        let itemId = window.copiedModifierItemId;
        
        let url = cloneModifierGroupUrl.replace(':itemId', itemId).replace(':targetItemId', targetItemId);

        $.ajax({
            type: 'POST',
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
                if (response.success) {
                    showAlert('success', response.message);
                    window.copiedModifierItemId = null;
                    $("#header_cancel_copy_modifier_btn").hide();
                    $('#item_list_table').DataTable().ajax.reload(null, false);
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function (xhr) {
                showAlert('error', 'An error occurred while pasting modifiers.');
            }
        });
    });

    $(document).on("click", '.associate_modifier', function (e) {
        var itemId = $(this).data('id');
        $("#bulk-update-submit").attr('data-item-id', itemId);
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

                    // #Sortable - Modifier and Modifier Items drag and drop
                    makeGroupModifierFormSortable();
                    // validateModifierItemsSelection();
                }
            }
        });
    });

    $(document).on("click", '.category_associate_modifier', function(e) {
        const selectedCategory = $('#category_filter').val(); // Get the selected category ID

        if (!selectedCategory) {
            // Show a warning if no category is selected
            $(this).val(''); // Reset the dropdown selection
            $("#dangerModalBody").html("Please select a category.");
            $("#dangerModal").modal('show');
            return;
        } 

        url = categoryAssociateModifiersUrl.replace(':categoryId', selectedCategory);
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

                    // #Sortable - Modifier and Modifier Items drag and drop
                    makeGroupModifierFormSortable();
                    // validateModifierItemsSelection();
                }
            }
        });
    });

    $(document).on("submit", "#modifier_group_add", function (e) {
        e.preventDefault();
        var itemId = $('#item_id').val();
        var modifierGroupId = $('#modifier_group_id').val();

        if($('.accordion-'+modifierGroupId).length > 0){
            showAlert('error', 'Modifier group already Added');
            return false;
        }
        
        url = itemAddModifierUrl.replace(':itemId', itemId);
        url = url.replace(':modifierGroupId', modifierGroupId);
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
                    $('.accordion').append(response.html);
                    sortModifierGroup();
                    makeGroupModifierFormSortable();
                }
            }
        });
    });
    
    $(document).on("click","#save_associate_modifiers_form_submit",function(){
        // if (!validateModifierItemsSelection()) {
        //     showAlert('error', 'Please clear form errors.');
        //     return false;
        // }
        closeModifierModal = true;
        $('#save_associate_modifiers_form').submit();
    });

    $(document).on("submit", "#save_associate_modifiers_form", function (e) {
        e.preventDefault();
        var data = $("#save_associate_modifiers_form").serialize();
        // return false;
        var itemId = $('#item_id').val();

        url = $(this).attr('action');
        $.ajax({
            type: 'POST',
            data:data,
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
                    showAlert('success', response.message);
                    $('#item_list_table').DataTable().ajax.reload(null, false);
                    if (closeModifierModal) {
                      $('#addModifier').modal('hide');
                    }
                }else{
                    showAlert('error', response.message);
                }
            }
        });
    });

    $(document).on("click",".delete-modifier-group",function(){
        $(this).parents('.accordion-item').remove();
        sortModifierGroup();
    });
});

function showModalMessage(title, message) {
    // Set the modal title and message
    $('#defaultModalLabel').text(title);
    $('#defaultModal .modal-body').text(message);

    // Show the modal
    $('#defaultModal').modal('show');
}

//modifier modal js
$(document).on('change', '.status-select-all', function () {
    const isChecked = $(this).is(':checked');
    const accordionItem = $(this).closest('.accordion-item');
    accordionItem.find('input[name*="[status]"]').prop('checked', isChecked);
});

$(document).on('focus', '.numberInput', function(e) {
    $(this).select(); // Auto-select the whole text when focused
});

$(document).on('change', '.order_index', function (e) {
    let inputIndex = parseInt($(this).val(), 10);
    if (isNaN(inputIndex)) return;

    let $currentRow = $(this).closest('.accordion-item');
    let $rows = $('.accordion .accordion-item').not($currentRow);

    let rowArray = $rows.toArray();

    // Clamp index between 0 and max
    let insertIndex = Math.max(0, Math.min(inputIndex - 1, rowArray.length));

    rowArray.splice(insertIndex, 0, $currentRow[0]);

    $('.accordion').empty().append(rowArray);

    // Re-index all visible rows
    $('.accordion .accordion-item').each(function (i) {
        $(this).find('.order_index').val(i + 1);
    });

    saveModifierFormAfterSort();
    
});

$(document).on('change', '.item_index', function (e) {
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
        $(this).find('.item_index').val(i + 1);
    });

    saveModifierFormAfterSort();
});

function validateModifierItemsSelection() {
    var isValid = true;
    $("#save_associate_modifiers_form").find('.accordion-item').each(function (i, accordion_item) {
        accordion_item = $(accordion_item);
        var minModifierCount = accordion_item.find('.min_modifier_count').val();
        var maxModifierCount = accordion_item.find('.max_modifier_count').val();
        
        if (minModifierCount <= 0 &&  maxModifierCount <= 0) {
            return true;
        }

        if (maxModifierCount && minModifierCount > maxModifierCount) {
            isValid = false;
            // accordion_item.addClass('border border-danger');
            accordion_item.find('.min_modifier_count').addClass('border border-danger');
            accordion_item.find('.max_modifier_count').addClass('border border-danger');
            return true;
        } else {
            // accordion_item.removeClass('border border-danger');
            accordion_item.find('.min_modifier_count').removeClass('border border-danger');
            accordion_item.find('.max_modifier_count').removeClass('border border-danger');
        }

        var checkedModItems = accordion_item.find('.mod_status:checked').length;

        if (minModifierCount != '' && checkedModItems < minModifierCount) {
            isValid = false;

            accordion_item.find(".mod_err").text('Please select at least ' + minModifierCount + ' items.');
            accordion_item.addClass('border border-danger');
        } else if (maxModifierCount != '' && checkedModItems > maxModifierCount) {
            isValid = false;

            accordion_item.find(".mod_err").text('You can select a maximum of ' + maxModifierCount + ' items.');
            accordion_item.addClass('border border-danger');
        } else {
            accordion_item.find(".mod_err").text('');
            accordion_item.removeClass('border border-danger');
        }
    });

    return isValid;
}

// $(document).on('input change', '.mod_status, .min_modifier_count, .max_modifier_count, .status-select-all', function (e) {
    // validateModifierItemsSelection();
// });

$(document).ready(function () {
    window.initCheckboxSelect();

    // Update Price on change of price input (delegated for dynamic rows)
    $("#addModifier").on("change input", ".modifier-price-input", function () {
        var newPrice = parseFloat($(this).val()) || 0;
        var _ptr = $(this).closest("tr");

        var _webInput = $(_ptr).find(".web_price_input");
        var _uberEatsInput = $(_ptr).find(".ubereats_price_input");
        var _grubhubInput = $(_ptr).find(".grubhub_price_input");
        var _doorDashInput = $(_ptr).find(".door_dash_price_input");

        if ($(_webInput).data('modified') != 1) {
            $(_webInput).val(newPrice.toFixed(2));
        }

        if ($(_uberEatsInput).data('modified') != 1) {
            $(_uberEatsInput).val(newPrice.toFixed(2));
        }

        if ($(_grubhubInput).data('modified') != 1) {
            $(_grubhubInput).val(newPrice.toFixed(2));
        }

        if ($(_doorDashInput).data('modified') != 1) {
            $(_doorDashInput).val(newPrice.toFixed(2));
        }
    });
});

$(document).ready(function () {
    $(document).on('click', '.sort-header', function () {
        const sortBy = $(this).data('sort');

        // Scope to THIS table only
        const table = $(this).closest('.item_modifier_table');
        const tbody = table.find('tbody.sortable');

        // Per-table sort state stored on the element
        let sortOrder = table.data('sortOrder') || { idx: 'asc', name: 'asc' };
        const isAsc = sortOrder[sortBy] === 'asc';

        const rows = tbody.find('tr').toArray();

        rows.sort(function (a, b) {
            let aVal, bVal;

            if (sortBy === 'idx') {
                aVal = parseInt($(a).find('.index_input').val()) || 0;
                bVal = parseInt($(b).find('.index_input').val()) || 0;
                return isAsc ? aVal - bVal : bVal - aVal;
            } else {
                aVal = $(a).find('td:nth-child(3)').text().trim().toLowerCase();
                bVal = $(b).find('td:nth-child(3)').text().trim().toLowerCase();
                return isAsc
                    ? aVal.localeCompare(bVal)
                    : bVal.localeCompare(aVal);
            }
        });

        // Toggle and save state back to this table
        sortOrder[sortBy] = isAsc ? 'desc' : 'asc';
        table.data('sortOrder', sortOrder);

        // Update icons only within this table's header
        table.find('.sort-header i').attr('class', 'fas fa-sort ms-1');
        $(this).find('i').attr(
            'class',
            `fas fa-sort-${isAsc ? 'down' : 'up'} ms-1`
        );

        // Re-append sorted rows
        $.each(rows, function (i, row) {
            tbody.append(row);
        });
    });

});