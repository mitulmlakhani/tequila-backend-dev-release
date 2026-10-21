$(document).ready(function () {
    if (isValidationError) {
        var modalTitle = getTempData("modal_title");
        var addUpdate = getTempData("add_update");
        $('.main-heading').text(modalTitle);
        var menuTypeAddForm = $('form');
        menuTypeAddForm.find('button[type=submit]').text(addUpdate);
        if (addUpdate == 'Add') {
            menuTypeAddForm.find("#edit_category_icon").hide();
            menuTypeAddForm.find("#image").prop('required', true);
        } else {
            var formUrl = getTempData("formUrl");
            menuTypeAddForm.attr('action', formUrl);
            menuTypeAddForm.find("#edit_category_icon").show();
        }
    }

    $(document).on('click', '#menu-type-add', function(e) {
        $('.main-heading h4').text('Add Menu Type');
        var menuTypeAddForm = $('form');
        menuTypeAddForm.attr('action', menuTypeCreateUrl);
        menuTypeAddForm.find('button[type=submit]').text('Save');
        menuTypeAddForm.find('#name').val('');
        menuTypeAddForm.find('#color').val('#ffffff');
        menuTypeAddForm.find('#font_color').val('#000000');
        menuTypeAddForm.find('#index').val('');

        setTempData("modal_title", 'Add Menu Type');
        menuTypeAddForm.find("button[type=submit]").text("Add");
        $('.validation-error').hide();
    });

    $(document).on('click', '#menu-type-edit', function(e) {
        $('.main-heading h4').text('Edit Menu Type');
        var menuTypeAddForm = $('form');
        var id = $(this).data('id');
        var formUrl = menuTypeUpdateUrl.replace(':id', id);
        menuTypeAddForm.attr('action', formUrl);
        menuTypeAddForm.find('button[type=submit]').text('Update');

        var url = menuTypeDetailUrl.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                if (response.status == 'success') {
                    menuTypeAddForm.find('#name').val(response.data.name);
                    menuTypeAddForm.find('#color').val(response.data.color);
                    menuTypeAddForm.find('#font_color').val(response.data.font_color);
                    menuTypeAddForm.find('#index').val(response.data.index);

                    setTempData("modal_title", 'Edit Menu Type');
                    setTempData("add_update", 'Update');
                    setTempData("formUrl", formUrl);
                    $('.validation-error').hide();
                }
            }
        });
    });

    $(document).on('click', '#deleteMenuType', function(e) {
        var url = $(this).data('url');
        $('#deleteMenuTypeBtn').attr('href', url);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
