window.initCheckboxSelect = function () {
    function formatOptionWithCheckbox(data, selectedValues, areAllSelected) {
        if (!data.id) return data.text;

        const isSelectAll = data.id === "select_all";
        const isChecked = isSelectAll
            ? areAllSelected
            : selectedValues.includes(data.id.toString());

        const checkbox = `<input class="select2-check form-check-input select2-checkbox" style="width: 16px; min-width: 16px; height: 16px; flex-shrink: 0; margin: 0 8px 0 0; pointer-events: none;" data-id="${
            data.id
        }" type="checkbox" ${isChecked ? "checked" : ""} />`;
        return $(`<div class="d-flex align-items-center flex-nowrap select2-option-row" data-id="${data.id}" style="white-space: nowrap; cursor: pointer;">${checkbox} <span class="select2-option-text" style="white-space: nowrap;">${data.text}</span></div>`);
    }

    $(".checkbox-select").each(function () {
        const $select = $(this);
        const containerAttr = $(this).attr('data-containerId');

        if ($select.hasClass("select2-hidden-accessible")) {
            return;
        }

        // Get all option values except 'select_all' and empty values
        function getAllRealOptionValues() {
            return $select
                .find("option")
                .map(function () {
                    const val = $(this).val();
                    return (val && val !== "select_all") ? val.toString() : null;
                })
                .get();
        }

        function areAllOptionsSelected() {
            const allOptionValues = getAllRealOptionValues();
            const selected = ($select.val() || [])
                .map((v) => v.toString())
                .filter((v) => v !== "select_all");
            return (
                allOptionValues.length > 0 &&
                allOptionValues.every((val) => selected.includes(val))
            );
        }

        // Clean any initial 'select_all' from val
        const initialVal = $select.val();
        if (Array.isArray(initialVal) && initialVal.includes("select_all")) {
            $select.val(initialVal.filter((v) => v !== "select_all"));
        }

        var select2Opts = {
            closeOnSelect: false,
            width: '100%',
            templateResult: function (data) {
                const selected = ($select.val() || [])
                    .map((v) => v.toString())
                    .filter((v) => v !== "select_all");
                return formatOptionWithCheckbox(
                    data,
                    selected,
                    areAllOptionsSelected()
                );
            },
            templateSelection: function (data) {
                if (data.id === "select_all") {
                    return null;
                }
                return data.text;
            },
        };

        if (containerAttr) {
            select2Opts.dropdownParent = $("#" + containerAttr);
        }

        $select.select2(select2Opts);

        // Ensure 'select_all' is never stored in val
        $select.on("change", function () {
            const val = $select.val();
            if (Array.isArray(val) && val.includes("select_all")) {
                const cleaned = val.filter((v) => v !== "select_all");
                $select.val(cleaned).trigger("change.select2");
            }
            updateCheckboxStates();
        });

        function updateCheckboxStates() {
            setTimeout(function () {
                const selected = ($select.val() || [])
                    .map((s) => s.toString())
                    .filter((v) => v !== "select_all");
                const allSelected = areAllOptionsSelected();

                $(".select2-results__option").each(function () {
                    const $opt = $(this);
                    const checkbox = $opt.find('input[type="checkbox"]');
                    if (checkbox.length) {
                        const optId = checkbox.data("id")?.toString();
                        if (optId === "select_all") {
                            checkbox.prop("checked", allSelected);
                        } else if (optId) {
                            checkbox.prop("checked", selected.includes(optId));
                        }
                    }
                });
            }, 0);
        }

        $select.on(
            "select2:open select2:select select2:unselect",
            updateCheckboxStates
        );
    });

    // Global delegation on Select All option click
    $(document).off("mouseup.select2all").on("mouseup.select2all", ".select2-results__option", function (e) {
        const $opt = $(this);
        const $checkbox = $opt.find('input[data-id="select_all"]');
        if ($checkbox.length) {
            e.preventDefault();
            e.stopPropagation();

            // Find the active select element
            const $results = $opt.closest(".select2-results__options");
            const resultsId = $results.attr("id") || "";
            let $select = null;

            if (resultsId) {
                const selectId = resultsId.replace(/^select2-/, '').replace(/-results$/, '');
                $select = $('#' + selectId);
                if (!$select.length) {
                    $select = $('[name="' + selectId + '"]');
                }
            }

            if (!$select || !$select.length) {
                $select = $(".checkbox-select.select2-hidden-accessible").filter(function() {
                    return $(this).data('select2') && $(this).data('select2').isOpen();
                });
            }

            if ($select && $select.length) {
                const allOptionValues = $select
                    .find("option")
                    .map(function () {
                        const val = $(this).val();
                        return (val && val !== "select_all") ? val.toString() : null;
                    })
                    .get();

                const currentVals = ($select.val() || [])
                    .map((v) => v.toString())
                    .filter((v) => v !== "select_all");

                const areAllSelected = allOptionValues.length > 0 && allOptionValues.every((val) => currentVals.includes(val));

                if (areAllSelected) {
                    $select.val([]).trigger("change");
                } else {
                    $select.val(allOptionValues).trigger("change");
                }

                setTimeout(function () {
                    const newVals = ($select.val() || []).map((v) => v.toString());
                    const newAllSelected = allOptionValues.length > 0 && allOptionValues.every((v) => newVals.includes(v));

                    $results.find(".select2-results__option").each(function () {
                        const $o = $(this);
                        const cb = $o.find('input[type="checkbox"]');
                        if (cb.length) {
                            const optId = cb.data("id")?.toString();
                            if (optId === "select_all") {
                                cb.prop("checked", newAllSelected);
                            } else if (optId) {
                                cb.prop("checked", newVals.includes(optId));
                            }
                        }
                    });
                }, 0);
            }
        }
    });

    // Prevent backspace key in search field from deleting choices in checkbox-select
    $(document).off("keydown.checkboxSelectNoDelete").on("keydown.checkboxSelectNoDelete", ".select2-search__field", function (e) {
        if (e.which === 8 && $(this).val() === "") {
            const $container = $(this).closest(".select2-container");
            const $select = $container.prev("select.checkbox-select");
            if ($select.length) {
                e.preventDefault();
            }
        }
    });
};



