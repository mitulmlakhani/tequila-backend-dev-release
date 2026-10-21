
$(document).ready(function () {

    if(isValidationError){
        $(document).ready(function(){
            var modalTitle = getTempData("modal_title");
            var addUpdate = getTempData("add_update");
            $('#category-add-modal').find('.modal-title').text(modalTitle);
            var categoryAddForm = $('#category-add-modal').find('form');
            categoryAddForm.find('button[type=submit]').text(addUpdate);
            if(addUpdate == 'Add'){
                categoryAddForm.find("#edit_category_icon").hide();
                categoryAddForm.find("#image").prop('required', true);
            }else{
                var image = getTempData("image");
                var formUrl = getTempData("formUrl");
                categoryAddForm.find('#edit_category_icon').attr("src", image);
                categoryAddForm.find("#image").prop('required', false);
                categoryAddForm.attr('action', formUrl);
                categoryAddForm.find("#edit_category_icon").show();
            }
            $('#category-add-modal').modal('show');
        });
    }

    $(document).on('click', '#category-add', function(e) {
        $('#category-add-modal').find('.modal-title').text('Add Category');
        var categoryAddForm = $('#category-add-modal').find('form');
        categoryAddForm.attr('action', categoryCreateUrl);
        categoryAddForm.find('button[type=submit]').text('Add');
        categoryAddForm.find('#parent_id').val('');
        categoryAddForm.find('#category_name').val('');
        categoryAddForm.find('#image').val('');
        categoryAddForm.find('#status').val('1');
        categoryAddForm.find('#description').val('');

        categoryAddForm.find("#edit_category_icon").hide();

        categoryAddForm.find("#image").prop('required', false);

        setTempData("modal_title", 'Add Category');
        setTempData("add_update", 'Add');
        $('.validation-error').hide();
    });

    $(document).on('click', '#category-edit', function(e) {
        $('#category-add-modal').find('.modal-title').text('Edit Category');
        categoryAddForm = $('#category-add-modal').find('form');
        console.log(categoryAddForm.attr('action'));
        var id = $(this).data('id');
        formUrl = categoryUpdateUrl.replace(':id', id);
        categoryAddForm.attr('action', formUrl);
        categoryAddForm.find('button[type=submit]').text('Update');

        url = categoryDetailUrl.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            async: false,
            success: function(response) {
                if (response.status == 'success') {
                    categoryAddForm.find('#parent_id').val(response.data.parent_id);
                    categoryAddForm.find('#category_name').val(response.data.category_name);
                    categoryAddForm.find('#status').val(response.data.status);
                    categoryAddForm.find('#description').val(response.data.category_desc);

                    categoryAddForm.find('#edit_category_icon').attr("src", response.data.image_url);
                    categoryAddForm.find("#edit_category_icon").show();

                    categoryAddForm.find("#image").prop('required', false);

                    setTempData("modal_title", 'Edit Category');
                    setTempData("add_update", 'Update');
                    setTempData("image", response.data.image_url);
                    setTempData("formUrl", formUrl);
                    $('.validation-error').hide();
                }
            }
        });
    });

    $(document).on('click', '#deleteInventory', function(e) {
        var url = $(this).data('url');
        $('#deleteInventoryBtn').attr('href',url);
    });

});

//code for import confirmations and validations
document.getElementById('openConfirmationModalBtn').addEventListener('click', function () {
    var fileInput = document.getElementById('menu-items');
    var fileError = document.getElementById('fileError');
    if (fileInput.files.length === 0) {
        fileError.style.display = 'block';
    } else {
        fileError.style.display = 'none';
        var importModal = new bootstrap.Modal(document.getElementById('importConfirmationModal'), {
            keyboard: false
        });
        importModal.show();
    }
});

document.getElementById('confirmImportBtn').addEventListener('click', function () {
    document.getElementById('menuImportForm').submit();
});

document.getElementById('importConfirmationModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('menuImportForm').reset();
});