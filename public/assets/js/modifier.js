$(document).ready(function () {
    if (isValidationError) {
        var modalTitle = getTempData("modal_title");
        var addUpdate = getTempData("add_update");
        var modifierAddForm = $('#modifier-form');
        $('#modifier-add-modal').find('.modal-title').text(modalTitle);
        modifierAddForm.find('button[type=submit]').text(addUpdate);

        if (addUpdate === 'Add') {
            modifierAddForm.find("#edit_modifier_icon").hide();
            modifierAddForm.find("#image").prop('required', true);
        } else {
            var image = getTempData("image");
            var formUrl = getTempData("formUrl");
            modifierAddForm.find('#edit_modifier_icon').attr("src", image).show();
            modifierAddForm.find("#image").prop('required', false);
            modifierAddForm.attr('action', formUrl);
        }

        $('#modifier-add-modal').modal('show');
    }

    $(document).on('click', '#modifier-add', function (e) {
        var modifierAddForm = $('#modifier-form');
        $('#modifier-add-modal').find('.modal-title').text('Add Modifier');
        modifierAddForm.attr('action', modifierCreateUrl);
        modifierAddForm.find('button[type=submit]').text('Add');
        modifierAddForm.find('#name').val('');
        modifierAddForm.find('#price').val('');
        modifierAddForm.find('#color').val('#ffffff');
        modifierAddForm.find('#status').val('1');
        modifierAddForm.find("#edit_modifier_icon").hide();
        modifierAddForm.find("#image").prop('required', true);

        setTempData("modal_title", 'Add Modifier');
        setTempData("add_update", 'Add');
        $('.validation-error').hide();
    });

    $(document).on('click', '#modifier-edit', function (e) {
        var id = $(this).data('id');
        var formUrl = modifierUpdateUrl.replace(':id', id);
        var url = modifierDetailUrl.replace(':id', id);

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function (response) {
                if (response.status === 'success') {
                    var modifierAddForm = $('#modifier-form');
                    modifierAddForm.attr('action', formUrl);
                    modifierAddForm.find('button[type=submit]').text('Update');
                    modifierAddForm.find('#name').val(response.data.name);
                    modifierAddForm.find('#price').val(response.data.price);
                    modifierAddForm.find('#color').val(response.data.color);
                    modifierAddForm.find('#status').val(response.data.status ? '1' : '0');

                    if (response.data.image_url) {
                        modifierAddForm.find('#edit_modifier_icon').attr("src", response.data.image_url).show();
                    } else {
                        modifierAddForm.find('#edit_modifier_icon').hide();
                    }

                    setTempData("modal_title", 'Edit Modifier');
                    setTempData("add_update", 'Update');
                    setTempData("image", response.data.image_url);
                    setTempData("formUrl", formUrl);

                    $('#modifier-add-modal').modal('show');
                    $('.validation-error').hide();
                }
            }
        });
    });

    $(document).on('click', '#deleteModifier', function (e) {
        var url = $(this).data('url');
        $('#deleteModifierBtn').attr('href', url);
    });
});
