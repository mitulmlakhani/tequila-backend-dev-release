
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
                // categoryAddForm.find("#image").prop('required', true);
            }else{
                var image = getTempData("image");
                var formUrl = getTempData("formUrl");
                categoryAddForm.find('#edit_category_icon').attr("src", image);
                // categoryAddForm.find("#image").prop('required', false);
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
        categoryAddForm.find('#name').val('');
        categoryAddForm.find('#image').val('');
        categoryAddForm.find('#status').val('1');
        categoryAddForm.find('#description').val('');

        categoryAddForm.find("#edit_category_icon").hide();

        // categoryAddForm.find("#image").prop('required', true);

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
                    categoryAddForm.find('#name').val(response.data.name);
                    categoryAddForm.find('#status').val(response.data.status);
                    categoryAddForm.find('#description').val(response.data.description);
                    categoryAddForm.find('#color').val(response.data.color);

                    categoryAddForm.find('#edit_category_icon').attr("src", response.data.image_url);
                    categoryAddForm.find("#edit_category_icon").show();

                    // categoryAddForm.find("#image").prop('required', false);

                    setTempData("modal_title", 'Edit Category');
                    setTempData("add_update", 'Update');
                    setTempData("image", response.data.image_url);
                    setTempData("formUrl", formUrl);
                    $('.validation-error').hide();
                }
            }
        });
    });

    $(document).on('click', '#deleteCategory', function(e) {
        var url = $(this).data('url');
        $('#deleteCategoryBtn').attr('href',url);
    });

});
