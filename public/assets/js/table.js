
$(document).ready(function () {

    if(isValidationError){
        $(document).ready(function(){
            var modalTitle = getTempData("modal_title");
            var addUpdate = getTempData("add_update");
            $('#table-add-modal').find('.modal-title').text(modalTitle);
            var tableAddForm = $('#table-add-modal').find('form');
            tableAddForm.find('button[type=submit]').text(addUpdate);
            if(addUpdate == 'Add'){
                tableAddForm.find("#edit_image").hide();
            }else{
                var image = getTempData("image");
                var formUrl = getTempData("formUrl");
                tableAddForm.find('#edit_image').attr("src", image);
                tableAddForm.attr('action', formUrl);
                tableAddForm.find("#edit_image").show();
            }
            $('#table-add-modal').modal('show');
        });
    }

    $(document).on('click', '#table-add', function(e) {
        $('#table-add-modal').find('.modal-title').text('Add Element');
        var tableAddForm = $('#table-add-modal').find('form');
        tableAddForm.attr('action', tableCreateUrl);
        tableAddForm.find('button[type=submit]').text('Add');
        tableAddForm.find('#floor_id').val('');
        tableAddForm.find('#table_no').val('');
        tableAddForm.find('#seating_capacity').val('');
        tableAddForm.find('#bg_color').val('');
        tableAddForm.find('#shape').val('');

        tableAddForm[0].reset();
        tableAddForm.find("#edit_image").hide();
        setTempData("modal_title", 'Add Table');
        setTempData("add_update", 'Add');
        $('.validation-error').hide();
    });

    $(document).on('click', '#table-edit', function(e) {
        $('#table-add-modal').find('.modal-title').text('Edit Element');
        tableAddForm = $('#table-add-modal').find('form');
        console.log(tableAddForm.attr('action'));
        var id = $(this).data('id');
        formUrl = tableUpdateUrl.replace(':id', id);
        tableAddForm.attr('action', formUrl);
        tableAddForm.find('button[type=submit]').text('Update');

        url = tableDetailUrl.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            async: false,
            success: function(response) {
                if (response.status == 'success') {
                    tableAddForm.find('#floor_id').val(response.data.floor_id);
                    tableAddForm.find('#type').val(response.data.type);
                    tableAddForm.find('#table_no').val(response.data.table_no);
                    tableAddForm.find('#seating_capacity').val(response.data.seating_capacity);
                    tableAddForm.find('#status').val(response.data.status);
                    tableAddForm.find('#bg_color').val(response.data.bg_color);
                    tableAddForm.find('#shape').val(response.data.shape);
                    if (response.data.image) {
                        tableAddForm.find('#edit_image').attr("src", response.data.image_url);
                        tableAddForm.find("#edit_image").show();
                    }else{
                        tableAddForm.find("#edit_image").hide();
                        
                    }

                    if (response.data.type == 1) {
                        $("#tableTypeContainer").show();
                        $("#table_type").val(response.data.table_type);
                    }else{
                        $("#tableTypeContainer").hide();
                        $("#table_type").val('regular');
                    }

                    setTempData("modal_title", 'Edit Table');
                    setTempData("add_update", 'Update');
                    setTempData("image", response.data.image_url);
                    setTempData("formUrl", formUrl);
                    $('.validation-error').hide();
                }
            }
        });
    });

    $(document).on('click', '#deleteTable', function(e) {
        var url = $(this).data('url');
        $('#deleteTableBtn').attr('href',url);
    });

    $(document).on('change','#type',function(){
        if($(this).val() == 1){
            $("#tableTypeContainer").show();
        }else{
            $("#tableTypeContainer").hide();
            $("#table_type").val('regular');
        }
    })

});
