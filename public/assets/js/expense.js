$(document).ready(function () {
    if (isValidationError) {
        var modalTitle = getTempData("modal_title");
        var addUpdate = getTempData("add_update");
        if (modalTitle) {
            $('.main-heading h4').text(modalTitle);
        }
        var expenseAddForm = $('#expense-form');
        if (addUpdate) {
            expenseAddForm.find('button[type=submit]').text(addUpdate);
        }
        if (addUpdate == 'Update') {
            var formUrl = getTempData("formUrl");
            if (formUrl) {
                expenseAddForm.attr('action', formUrl);
            }
        }
    }

    $(document).on('click', '#expense-add', function(e) {
        $('.main-heading h4').text('Add Expense');
        var expenseAddForm = $('#expense-form');
        expenseAddForm.attr('action', expenseCreateUrl);
        expenseAddForm.find('button[type=submit]').text('Save');
        expenseAddForm.find('#expenses_type_id').val('');
        expenseAddForm.find('#expense_category').val('');
        expenseAddForm.find('#expense_amount').val('');
        expenseAddForm.find('#expense_description').val('');
        expenseAddForm.find('#status').val('1');

        setTempData("modal_title", 'Add Expense');
        setTempData("add_update", 'Save');
        $('.validation-error').hide();
    });

    $(document).on('click', '.expense-edit', function(e) {
        e.preventDefault();
        $('.main-heading h4').text('Edit Expense');
        var expenseAddForm = $('#expense-form');
        var id = $(this).data('id');
        var formUrl = expenseUpdateUrl.replace(':id', id);
        expenseAddForm.attr('action', formUrl);
        expenseAddForm.find('button[type=submit]').text('Update');

        var url = expenseDetailUrl.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                if (response.status == 'success') {
                    expenseAddForm.find('#expenses_type_id').val(response.data.expenses_type_id);
                    expenseAddForm.find('#expense_category').val(response.data.expense_category);
                    expenseAddForm.find('#expense_amount').val(response.data.expense_amount);
                    expenseAddForm.find('#expense_description').val(response.data.expense_description || '');
                    expenseAddForm.find('#status').val(response.data.status);

                    setTempData("modal_title", 'Edit Expense');
                    setTempData("add_update", 'Update');
                    setTempData("formUrl", formUrl);
                    $('.validation-error').hide();
                }
            }
        });
    });

    $(document).on('click', '.deleteExpense', function(e) {
        var url = $(this).data('url');
        $('#deleteExpenseBtn').attr('href', url);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
