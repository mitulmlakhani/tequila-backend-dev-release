$(document).ready(function () {
    $(document).on("click", "#category-add", function (e) {
        $(".main-heading h4").text("Add Category Schedule");
        var categoryAddForm = $("form");
        categoryAddForm.attr("action", categoryCreateUrl);
        categoryAddForm.find("button[type=submit]").text("Add");
        categoryAddForm.find("#title").val("");
        categoryAddForm.find("#status").val("1");
        categoryAddForm.find("#categories").val("").trigger("change");
        categoryAddForm.find('.days_checkbox').each(function () {
            $(this).prop("checked", false);
        });

        setTempData("modal_title", "Add Category Schedule");
        setTempData("add_update", "Add");
        $(".validation-error").hide();
    });

    $(document).on("click", "#category-edit", function (e) {
        $(".main-heading h4").text("Edit Category Schedule");
        var categoryAddForm = $("form");
        var id = $(this).data("id");
        var formUrl = categoryUpdateUrl.replace(":id", id);
        categoryAddForm.attr("action", formUrl);
        categoryAddForm.find("button[type=submit]").text("Update");

        var url = categoryDetailUrl.replace(":id", id);
        
        $("#category_management").find('tr').each(function (i, tr) {
            this.style.removeProperty('background-color');
        });

        var scheduleRow = $(this).closest('tr');

        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                if (response.success) {
                    scheduleRow.css('background-color', '#ffe');
                    categoryAddForm.find("#title").val(response.data.title).focus();

                    categoryAddForm.find('.days_checkbox').each(function () {
                        $(this).prop("checked", false);
                    });

                    response.data.days.forEach(function (day) {
                        categoryAddForm.find(`#${day}`).prop("checked", true);
                    });

                    if (response.data.days?.length == 7) {
                        $("#all_days").attr('checked', true);
                    }

                    categoryAddForm.find("#status").val(response.data.status);
                    categoryAddForm
                        .find("#categories")
                        .val(response.data.categories)
                        .trigger("change");
                    
                    if (response.data.schedule) {
                        for (const [day, time] of Object.entries(response.data.schedule)) {
                            categoryAddForm.find(`#${day}_from_time`).val(time.from);
                            categoryAddForm.find(`#${day}_to_time`).val(time.to);
                        }
                    }

                    setTempData("modal_title", "Edit Category");
                    setTempData("add_update", "Update");
                    setTempData("formUrl", formUrl);
                    $(".validation-error").hide();
                }
            },
        });
    });

    $(document).on("click", "#deleteCategory", function (e) {
        var url = $(this).data("url");
        $("#deleteCategoryBtn").attr("href", url);
    });

    $("#all_days").on("click", function () {
        var isChecked = $(this).is(":checked");
        $(".days_checkbox").prop("checked", isChecked);
    });

    $("#schedule_from_time").on("change", function () {
        var fromTime = $("#schedule_from_time").val();
        $(".from-date-field").val(fromTime);
    });

    $("#schedule_to_time").on("change", function () {
        var toTime = $("#schedule_to_time").val();
        $(".to-date-field").val(toTime);
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});
