
$(document).ready(function () {

    if(isValidationError){
        $(document).ready(function(){
            var modalTitle = getTempData("modal_title");
            var addUpdate = getTempData("add_update");
            $('#role-add-modal').find('.modal-title').text(modalTitle);
            var roleAddForm = $('#role-add-modal').find('form');
            roleAddForm.find('button[type=submit]').text(addUpdate);

            $('#role-add-modal').modal('show');
        });
    }

    $(document).on('click', '#role-add', function(e) {
        $('#role-add-modal').find('.modal-title').text('Add Role');
        var roleAddForm = $('#role-add-modal').find('form');
        roleAddForm.attr('action', roleCreateUrl);
        roleAddForm.find('button[type=submit]').text('Add');
        roleAddForm.find('#name').val('');
        roleAddForm.find('#guard_name').val('web');
        roleAddForm.find('#status').val('1');

        setTempData("modal_title", 'Add Role');
        setTempData("add_update", 'Add');
        $('.validation-error').hide();
    });

    $(document).on('click', '#role-edit', function(e) {
        $('#role-add-modal').find('.modal-title').text('Edit Role');
        roleAddForm = $('#role-add-modal').find('form');
        console.log(roleAddForm.attr('action'));
        var id = $(this).data('id');
        formUrl = roleUpdateUrl.replace(':id', id);
        roleAddForm.attr('action', formUrl);
        roleAddForm.find('button[type=submit]').text('Update');

        url = roleDetailUrl.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            async: false,
            success: function(response) {
                if (response.status == 'success') {
                    roleAddForm.find('#name').val(response.data.name);
                    roleAddForm.find('#guard_name').val(response.data.role_category);
                    roleAddForm.find('#status').val(response.data.status);

                    setTempData("modal_title", 'Edit Role');
                    setTempData("add_update", 'Update');
                    $('.validation-error').hide();
                }
            }
        });
    });

    $(document).on('click', '#deleteRole', function(e) {
        var url = $(this).data('url');
        $('#deleteRoleBtn').attr('href',url);
    });

});
