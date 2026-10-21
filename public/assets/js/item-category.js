$(document).ready(function () {
    if (isValidationError) {
        var modalTitle = getTempData("modal_title");
        var addUpdate = getTempData("add_update");
        $('.main-heading').text(modalTitle);
        var categoryAddForm = $('form');
        categoryAddForm.find('button[type=submit]').text(addUpdate);
        if (addUpdate == 'Add') {
            categoryAddForm.find("#edit_category_icon").hide();
            categoryAddForm.find("#image").prop('required', true);
        } else {
            var image = getTempData("image");
            var formUrl = getTempData("formUrl");
            categoryAddForm.find('#edit_category_icon').attr("src", image);
            categoryAddForm.find("#image").prop('required', false);
            categoryAddForm.attr('action', formUrl);
            categoryAddForm.find("#edit_category_icon").show();
        }
    }

    $(document).on('click', '#category-add', function(e) {
        $('.main-heading').text('Add Category');
        var categoryAddForm = $('form');
        categoryAddForm.attr('action', categoryCreateUrl);
        categoryAddForm.find('button[type=submit]').text('Add');
        categoryAddForm.find('#parent_id').val('');
        categoryAddForm.find('#menu_type_id').val('');
        categoryAddForm.find('#item_tag_id').val('');
        categoryAddForm.find('#category_name').val('');
        categoryAddForm.find('#image').val('');
        categoryAddForm.find('#status').val('1');
        categoryAddForm.find('#description').val('');
        categoryAddForm.find('#color').val('#ffffff');
        categoryAddForm.find('#font_color').val('#000000');
        categoryAddForm.find('#available_online').attr('checked', false);

        categoryAddForm.find("#edit_category_icon").hide();
        categoryAddForm.find("#image").prop('required', false);

        setTempData("modal_title", 'Add Category');
        setTempData("add_update", 'Add');
        $('.validation-error').hide();
    });

    $(document).on('click', '#category-edit', function(e) {
        $('.main-heading').text('Edit Category');
        var categoryAddForm = $('form');
        var id = $(this).data('id');
        var formUrl = categoryUpdateUrl.replace(':id', id);
        categoryAddForm.attr('action', formUrl);
        categoryAddForm.find('button[type=submit]').text('Update');
        var categoryRow = $(this).closest('tr');

        $("#category_management").find('tr').each(function (i, tr) {
            this.style.removeProperty('background-color');
        });

        var url = categoryDetailUrl.replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                if (response.status == 'success') {
                    categoryRow.css('background-color', '#ffe');
                    categoryAddForm.find('#parent_id').val(response.data.parent_id);
                    categoryAddForm.find('#category_name').val(response.data.category_name).focus();
                    categoryAddForm
                        .find("#menu_type_id")
                        .val(response.data.menu_type_id);
                    categoryAddForm
                        .find("#item_tag_id")
                        .val(response.data.item_tag_id);
                    categoryAddForm.find('#status').val(response.data.status);
                    categoryAddForm.find('#description').val(response.data.category_desc);
                    categoryAddForm.find('#color').val(response.data.color);
                    categoryAddForm.find('#font_color').val(response.data.font_color);
                    categoryAddForm.find('#available_online').attr('checked', response.data.available_online ? true:  false);

                    categoryAddForm.find('#edit_category_icon').attr("src", response.data.image_url);
                    categoryAddForm.find("#edit_category_icon").show();

                    if(!response.data.can_delete) {
                        categoryAddForm.find('#category_name').attr('readonly', true);
                    }

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

    $(document).on('click', '#deleteCategory', function(e) {
        var url = $(this).data('url');
        $('#deleteCategoryBtn').attr('href', url);
    });

    // Code for import confirmations and validations
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
        var exportMenuLink = document.querySelector('.export-menu');

        if (exportMenuLink) {
            exportMenuLink.click();

            setTimeout(function () {
                document.getElementById('menuImportForm').submit();
            }, 500);
        } else {
            // If the link is not found, just submit the form
            document.getElementById('menuImportForm').submit();
        }
    });

    document.getElementById('importConfirmationModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('menuImportForm').reset();
    });

    $(document).on('click', '.copy-cat', function () {
        var categoryId = $(this).data('id');
        $('#confirmCopyBtn').data('id', categoryId);
        $('#copyCategoryModal').modal('show');
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $(document).on('click', '#confirmCopyBtn', function () {
        var categoryId = $(this).data('id');
    
        $.ajax({
            url: `/category/copy/${categoryId}`,
            type: 'POST',
            success: function (response) {
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    location.reload();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function () {
                showAlert('error', response.message);
            }
        });
    });

    function saveCategoryIndexes() {
        setTimeout(() => {
            var categoryIndexes = [];

            $("#category_rows tr").map(function(i, trElm) {
                categoryIndexes.push({
                    categoryId: $(trElm).find('.category_id').val(),
                    orderIndex: $(trElm).find('.order_index').val()
                })
            });

            $.ajax({
                url: updateIndexUrl,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(categoryIndexes),
                success: function (response) {
                    if (response.status !== 'success') {
                        showAlert('error', response.message);
                    }
                },
                error: function () {
                    showAlert('error', response.message);
                }
            });

        }, 300);
    }

    new Sortable(document.getElementById("category_rows"), {
        onSort: function (e) {
            $(e.target)
                .find("tr")
                .each(function (i, el) {
                    $(this)
                    .find(".order_index")
                    .val(i + 1);
                });

            saveCategoryIndexes();
        }
    });

    $(document).on('change', '.order_index', function (e) {
        let $input = $(this);
        let inputIndex = parseInt($input.val(), 10);
        if (isNaN(inputIndex)) return;

        let $currentRow = $input.closest('tr');
        let $tbody = $currentRow.closest('tbody');
        let $rows = $tbody.find('tr').not($currentRow);

        let rowArray = $rows.toArray();
        let insertIndex = Math.max(0, Math.min(inputIndex - 1, rowArray.length));

        rowArray.splice(insertIndex, 0, $currentRow[0]);

        $tbody.empty().append(rowArray);

        $tbody.find('tr').each(function (i) {
            $(this).find('.order_index').val(i + 1);
        });

        saveCategoryIndexes();
    });

    function showWarningMessage(message) {
        var warningAlert =
            '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        $(".table-container").prepend(warningAlert);
    }

    function showSuccessMessage(message) {
        var successAlert = '<div class="alert alert-success alert-dismissible fade show" role="alert">'
                            + message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        $(".table-container").prepend(successAlert);
    }
    
    function showModalWarningMessage(message) {
        var modalWarningAlert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">'
                            + message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        $('.modal-body').prepend(modalWarningAlert);
    }

    $("#select-all").on("change", function (e) {
        if (this.checked) {
            $(".item-checkbox").each(function () {
                this.checked = true;
            });
        } else {
            $(".item-checkbox").each(function () {
                this.checked = false;
            });
        }
    });

    $("#bulk-update-btn").on("click", function () {
        if ($(".item-checkbox:checked").length === 0) {
            showWarningMessage("Please select categories to update.");
        } else {
            $("#bulkUpdateModal").modal("show");
        }
    });

    $('#bulk-update-submit').on('click', function() {
        var ubereatsSurcharge = $('input[name="ubereats_surcharge"]').val();

        if (!ubereatsSurcharge) {
            showModalWarningMessage('Please enter Ubereats Surcharge.');
            return;
        }

        var selectedItems = $('.item-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        var formData = $('#bulk-update-form').serializeArray();
        formData.push({name: '_token', value: $('meta[name="csrf-token"]').attr('content')});
        formData.push({name: 'categories', value: selectedItems.join(',')});
        formData.push({name: 'ubereats_surcharge', value: ubereatsSurcharge});

        $.ajax({
            type: 'POST',
            url: categoryBulkUpdateUrl,
            data: $.param(formData),
            success: function(response) {
                if (response?.status == "failed") {
                    showModalWarningMessage(response?.message);    
                    return;
                }
                $("#bulkUpdateModal").modal("hide");
                showSuccessMessage('Bulk update successful.');
                setTimeout(() => {
                    location.reload();
                }, 500);
            },
            error: function(xhr) {
                showWarningMessage('An error occurred: ' + xhr.responseText);
            }
        });
    });

    $(".available_online_list, .available_online_list_all").on("change", function () {
        var categoryId = $(this).data('category-id');
        var availableOnline = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            type: 'POST',
            url: categoryOnlineStatusUpdateUrl.replace(':id', categoryId),
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                available_online: availableOnline
            },
            success: function(response) {
                if (response?.status == "failed") {
                    showWarningMessage(response?.message);    
                    return;
                }
                if (categoryId == "all") {
                    $('.available_online_list').prop('checked', availableOnline);
                }
                showSuccessMessage('Category updated successfully.');
            },
            error: function(xhr) {
                showWarningMessage('An error occurred: ' + xhr.responseText);
            },
            complete: function () {
                console.log($('.available_online_list').length, $('.available_online_list:checked').length);
                $('.available_online_list_all').attr('checked', $('.available_online_list').length == $('.available_online_list:checked').length);
            }
        });
    });

    $('.available_online_list_all').attr('checked', $('.available_online_list').length == $('.available_online_list:checked').length);
});
