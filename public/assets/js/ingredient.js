$(document).ready(function () {
    if (isValidationError) {
        var modalTitle = getTempData("modal_title");
        var addUpdate = getTempData("add_update");
        var ingredientAddForm = $('#ingredient-form');
        $('#ingredient-add-modal').find('.modal-title').text(modalTitle);
        ingredientAddForm.find('button[type=submit]').text(addUpdate);

        if (addUpdate === 'Add') {
            ingredientAddForm.find("#image").prop('required', true);
        } else {
            var formUrl = getTempData("formUrl");
            ingredientAddForm.attr('action', formUrl);
        }

        $('#ingredient-add-modal').modal('show');
    }

    $(document).on('click', '#ingredient-add', function (e) {
        var ingredientAddForm = $('#ingredient-form');
        $('#ingredient-add-modal').find('.modal-title').text('Add Ingredient');
        ingredientAddForm.attr('action', ingredientCreateUrl);
        ingredientAddForm.find('button[type=submit]').text('Add');
        ingredientAddForm.find('#name').val('');
        ingredientAddForm.find('#price').val('');
        ingredientAddForm.find('#uom').val('');
        ingredientAddForm.find('#expiry_date').val('');
        ingredientAddForm.find('#status').val('1');

        setTempData("modal_title", 'Add Ingredient');
        setTempData("add_update", 'Add');
        $('.validation-error').hide();
    });

    $(document).on('click', '#ingredient-edit', function (e) {
        $('#ingredient-add-modal').find('.modal-title').text('Edit Ingredient');
        var ingredientAddForm = $('#ingredient-form');
        var id = $(this).data('id');
        var formUrl = ingredientUpdateUrl.replace(':id', id);
        ingredientAddForm.attr('action', formUrl);
        ingredientAddForm.find('button[type=submit]').text('Update');

        var url = ingredientDetailUrl.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function (response) {
                if (response.status === 'success') {
                    ingredientAddForm.find('#name').val(response.data.name);
                    ingredientAddForm.find('#price').val(response.data.price);
                    ingredientAddForm.find('#uom').val(response.data.uom);
                    ingredientAddForm
                        .find("#buy_quantity")
                        .val(response.data.buy_quantity);
                    ingredientAddForm.find('#expiry_date').val(dateFormat(response.data.expiry_date));
                    ingredientAddForm.find('#status').val(response.data.status ? '1' : '0');

                    setTempData("modal_title", 'Edit Ingredient');
                    setTempData("add_update", 'Update');
                    setTempData("formUrl", formUrl);
                    $('#ingredient-add-modal').modal('show');
                    $('.validation-error').hide();
                }
            }
        });
    });

    $(document).on('click', '#deleteIngredient', function (e) {
        var url = $(this).data('url');
        $('#deleteIngredientBtn').attr('href', url);
    });

    function dateFormat(dateObject) {
        var d = new Date(dateObject);
        var day = d.getDate();
        var month = d.getMonth() + 1;
        var year = d.getFullYear();
        if (day < 10) {
            day = "0" + day;
        }
        if (month < 10) {
            month = "0" + month;
        }
        var date = year + "-" + month + "-" + day;
        return date;
    };
});
