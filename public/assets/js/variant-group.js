
$(document).ready(function () {

    if(isValidationError){
        $(document).ready(function(){
            var modalTitle = getTempData("modal_title");
            var addUpdate = getTempData("add_update");
            $('#variant-add-modal').find('.modal-title').text(modalTitle);
            var variantAddForm = $('#variant-add-modal').find('form');
            variantAddForm.find('button[type=submit]').text(addUpdate);
            if(addUpdate == 'Update'){
                var formUrl = getTempData("formUrl");
                variantAddForm.attr('action', formUrl);
            }
            $('#variant-add-modal').modal('show');
        });
    }

    $(document).on('click', '#variant-add', function(e) {
        $('#variant-add-modal').find('.modal-title').text('Add Modifier Group');
        var variantAddForm = $('#variant-add-modal').find('form');
        variantAddForm.attr('action', variantCreateUrl);
        variantAddForm.find('button[type=submit]').text('Add');
        variantAddForm.find('#name').val('');
        variantAddForm.find('#status').val('1');

        setTempData("modal_title", 'Add Variant');
        setTempData("add_update", 'Add');
        $('.validation-error').hide();
    });

    $(document).on('click', '#variant-edit', function(e) {
        $('#variant-add-modal').find('.modal-title').text('Edit Modifier Group');
        variantAddForm = $('#variant-add-modal').find('form');
        console.log(variantAddForm.attr('action'));
        var id = $(this).data('id');
        formUrl = variantUpdateUrl.replace(':id', id);
        variantAddForm.attr('action', formUrl);
        variantAddForm.find('button[type=submit]').text('Update');

        url = variantDetailUrl.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            async: false,
            success: function(response) {
                if (response.status == 'success') {
                    variantAddForm.find('#name').val(response.data.name);
                    variantAddForm.find('#status').val(response.data.status);
                    variantAddForm.find('#variant_group_table_body').html(response.variantsGroupDetailHtml);

                    setTempData("modal_title", 'Edit Variant');
                    setTempData("add_update", 'Update');
                    setTempData("formUrl", formUrl);
                    $('.validation-error').hide();
                }
            }
        });
    });

    $(document).on('click', '#deleteVariant', function(e) {
        var url = $(this).data('url');
        $('#deleteVariantBtn').attr('href',url);
    });

    function myFunctionThree() {
        var table = document.getElementById("item_variant_table_body");
        var row = table.insertRow(0);
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

});
