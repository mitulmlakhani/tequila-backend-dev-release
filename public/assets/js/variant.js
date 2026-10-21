
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
        $('#variant-add-modal').find('.modal-title').text('Add Variant');
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
        $('#variant-add-modal').find('.modal-title').text('Edit Variant');
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

});
