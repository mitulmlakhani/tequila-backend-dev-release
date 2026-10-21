$(document).on('click', '#modifier-edit', function(e) {
    $('#modifier-add-modal').find('.modal-title').text('Edit Modifier Group');
    var modifierAddForm = $('#modifier-form');
    var id = $(this).data('id');
    var formUrl = modifierUpdateUrl.replace(':id', id);
    modifierAddForm.attr('action', formUrl);
    modifierAddForm.find('button[type=submit]').text('Update');

    var url = modifierDetailUrl.replace(':id', id);
    $.ajax({
        url: url,
        type: 'GET',
        data: {
            "_token": "{{ csrf_token() }}",
        },
        async: false,
        success: function(response) {
            if (response.status == 'success') {
                modifierAddForm.find('#name').val(response.data.name);
                modifierAddForm.find('#color').val(response.data.color);
                modifierAddForm.find('#status').val(response.data.status ? 1 : 0);
                
                // Populate the modifier group details (modifiers and prices)
                $('#modifier-prices').html(response.modifiersGroupDetailHtml);

                setTempData("modal_title", 'Edit Modifier');
                setTempData("add_update", 'Update');
                setTempData("formUrl", formUrl);
                $('.validation-error').hide();
                $('#modifier-add-modal').modal('show');
            }
        }
    });
});
