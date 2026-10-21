// Set form to Edit mode with AJAX fetch
$(document).on('click', '#gift-voucher-edit', function (e) {
    e.preventDefault();

    // Get the voucher ID from the clicked button/link
    var voucherId = $(this).data('id');
    // Clear previous errors and reset the form before populating with new data
    $('#voucher-form')[0].reset();
    $('#voucher_id').val('');
    $('.validation-error').hide();

    var fetchUrl = typeof showRecordUrl !== 'undefined'
        ? showRecordUrl.replace(':id', voucherId)
        : '/restaurant/gift-vouchers/' + voucherId;

    // Make an AJAX request to fetch the data
    $.ajax({
        url: fetchUrl,
        method: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                var data = response.data;

                // Populate the form with fetched data
                $('#voucher_id').val(data.id);
                $('#voucher_code').val(data.code);
                $('#amount').val(data.amount);
                if (data.expiry_date) {
                    var expDate = data.expiry_date.split('T')[0].split(' ')[0];
                    $('#expiry_date').val(expDate);
                } else {
                    $('#expiry_date').val('');
                }

                // Update status dropdown based on fetched data
                $('#status').val(data.status);

                // Change form action to update URL
                var updateUrl = typeof updateRecordUrl !== 'undefined'
                    ? updateRecordUrl.replace(':id', data.id)
                    : '/restaurant/gift-vouchers/' + data.id;
                $('#voucher-form').attr('action', updateUrl);

                // Change button text and heading
                $('#form-submit').text('Update');
                $('#form-heading').text('Edit Gift Voucher');

                // Show Cancel button
                $('#cancel-edit').removeClass('d-none');
            } else {
                alert(response.message);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('An error occurred while fetching the data.');
        }
    });
});

// Cancel Edit Mode
$('#cancel-edit').click(function () {
    // Clear the form
    $('#voucher-form')[0].reset();
    $('#voucher_id').val('');
    $('.validation-error').hide();

    // Reset the form action and button text
    $('#voucher-form').attr('action', storeRecordUrl);
    $('#form-submit').text('Save');
    $('#form-heading').text('Add Gift Voucher');

    // Hide Cancel button
    $(this).addClass('d-none');

    // Remove the POST method override for update if present
    $('input[name="_method"]').remove();
});

// Handle Delete Confirmation
$('#deleteRecord').on('show.bs.modal', function (e) {
    var url = $(e.relatedTarget).data('url');
    $('#deleteVoucherForm').attr('action', url);  // Update form action dynamically with the voucher URL
});

//voucer code prevent space code
document.addEventListener('DOMContentLoaded', function () {
    var voucherCodeInput = document.getElementById('voucher_code');

    voucherCodeInput.addEventListener('input', function (event) {
        // Remove spaces from input
        this.value = this.value.replace(/\s/g, '');
    });

    voucherCodeInput.addEventListener('keydown', function (event) {
        if (event.key === ' ') {
            event.preventDefault(); // Prevent space key
        }
    });
});