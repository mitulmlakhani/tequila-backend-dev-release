$(document).ready(function () {
    if (isValidationError) {
        var modalTitle = getTempData("modal_title");
        var addUpdate = getTempData("add_update");
        if (modalTitle) {
            $('.main-heading h4').text(modalTitle);
        }
        var expenseTypeAddForm = $('#expense-type-form');
        if (addUpdate) {
            expenseTypeAddForm.find('button[type=submit]').text(addUpdate);
        }
        if (addUpdate == 'Update') {
            var formUrl = getTempData("formUrl");
            if (formUrl) {
                expenseTypeAddForm.attr('action', formUrl);
            }
        }
    }

    $(document).on('click', '#expense-type-add', function(e) {
        $('.main-heading h4').text('Add Expense Type');
        var expenseTypeAddForm = $('#expense-type-form');
        expenseTypeAddForm.attr('action', expenseTypeCreateUrl);
        expenseTypeAddForm.find('button[type=submit]').text('Save');
        expenseTypeAddForm.find('#expense_type_name').val('');
        expenseTypeAddForm.find('#status').val('1');

        setTempData("modal_title", 'Add Expense Type');
        setTempData("add_update", 'Save');
        $('.validation-error').hide();
    });

    $(document).on('click', '.expense-type-edit', function(e) {
        e.preventDefault();
        $('.main-heading h4').text('Edit Expense Type');
        var expenseTypeAddForm = $('#expense-type-form');
        var id = $(this).data('id');
        var formUrl = expenseTypeUpdateUrl.replace(':id', id);
        expenseTypeAddForm.attr('action', formUrl);
        expenseTypeAddForm.find('button[type=submit]').text('Update');

        var url = expenseTypeDetailUrl.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                if (response.status == 'success') {
                    expenseTypeAddForm.find('#expense_type_name').val(response.data.expense_type);
                    expenseTypeAddForm.find('#status').val(response.data.status);

                    setTempData("modal_title", 'Edit Expense Type');
                    setTempData("add_update", 'Update');
                    setTempData("formUrl", formUrl);
                    $('.validation-error').hide();
                }
            }
        });
    });

    $(document).on('click', '.deleteExpenseType', function(e) {
        var url = $(this).data('url');
        $('#deleteExpenseTypeBtn').attr('href', url);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
