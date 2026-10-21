$(document).ready(function () {
    // Load permissions when a role is selected
    $('#default-role-select').on('change', function () {
        let roleName = $(this).val();
        $('#role-warning').addClass('d-none'); // hide warning if shown

        if (!roleName) return;

        $.post(defaultPermissionUrl, {
            role: roleName,
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function (res) {
            if (!res.error && res.resp) {
                // Uncheck all
                $('.default-permission-check').prop('checked', false);
                $('.default-group-check').prop('checked', false);

                // Check only returned permissions
                res.resp.forEach(id => {
                    $('#perm_' + id).prop('checked', true);
                });

                // Update group checkboxes
                $('.default-group-check').each(function () {
                    const group = $(this).data('group');
                    const allInGroup = $('.' + group).length;
                    const checkedInGroup = $('.' + group + ':checked').length;
                    $(this).prop('checked', allInGroup === checkedInGroup);
                });
            }
        });
    });

    // Select all permissions button
    $('#select-all-default').click(function () {
        if (!checkRoleSelected()) {
            $(this).prop('checked', false);
            return;
        }
        $('.default-permission-check').prop('checked', true);
        $('.default-group-check').prop('checked', true);
    });

    // Handle group checkbox (select all in group)
    $('.default-group-check').on('change', function () {
        if (!checkRoleSelected()) {
            $(this).prop('checked', false);
            return;
        }
        let groupClass = $(this).data('group');
        $('.' + groupClass).prop('checked', $(this).is(':checked'));
    });

    // Sync group checkbox if all children are checked manually
    $('.default-permission-check').on('change', function () {
        if (!checkRoleSelected()) {
            $(this).prop('checked', false);
            return;
        }
        let checkbox = $(this);
        let classes = checkbox.attr('class').split(/\s+/);

        // Find group_XXX
        let groupClass = classes.find(cls => cls.startsWith('group_'));
        if (!groupClass) return;

        let all = $('.' + groupClass).length;
        let checked = $('.' + groupClass + ':checked').length;

        $('.default-group-check[data-group="' + groupClass + '"]').prop('checked', all === checked);
    });
});


function checkRoleSelected() {
    const roleSelected = $('#default-role-select').val();
    if (!roleSelected) {
        $('#role-warning').removeClass('d-none');
        setTimeout(() => {
            $('#role-warning').addClass('d-none');
        }, 2000); // Hide after 2.5 seconds
        return false;
    }
    return true;
}
