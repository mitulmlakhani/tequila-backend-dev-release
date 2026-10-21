/**
 * Role & Employee Permissions Management Script
 */

$(document).ready(function () {
    // CSRF Token Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let currentRoleFilter = 'all';
    let currentCategoryFilter = 'all';
    let currentGuardFilter = 'all';
    let searchQuery = '';
    let showModifiedOnly = false;
    let unsavedChangesCount = 0;

    // Cache initial checkbox states per employee: { userId: Set(permIds) }
    const initialPermissions = {};
    const currentPermissions = {};
    const roleDefaultPermissions = window.roleDefaultPermissionsMap || {};

    // Cache all category options for dynamic guard-based filtering
    const allCategoryOptions = [];
    $('#filter-category option').each(function () {
        allCategoryOptions.push({
            value: $(this).val(),
            category: $(this).data('category') || $(this).val(),
            guard: ($(this).data('guard') || 'all').toLowerCase(),
            label: $(this).text().trim()
        });
    });

    // Initialize state from DOM
    $('.emp-row').each(function () {
        const userId = $(this).data('user-id');
        const checkedPerms = new Set();
        $(this).find('.chk-perm:checked').each(function () {
            checkedPerms.add(parseInt($(this).val()));
        });
        initialPermissions[userId] = new Set(checkedPerms);
        currentPermissions[userId] = new Set(checkedPerms);
    });

    // ----------------------------------------------------
    // Role Cards Selection / Filtering
    // ----------------------------------------------------
    $('.rolecard').on('click', function () {
        $('.rolecard').removeClass('active');
        $(this).addClass('active');

        const roleSlug = $(this).data('role-slug') || 'all';
        currentRoleFilter = roleSlug;

        // Sync dropdown
        $('#filter-role').val(roleSlug);

        applyFilters();
    });

    $('#filter-role').on('change', function () {
        const roleSlug = $(this).val();
        currentRoleFilter = roleSlug;

        $('.rolecard').removeClass('active');
        $(`.rolecard[data-role-slug="${roleSlug}"]`).addClass('active');

        applyFilters();
    });

    // ----------------------------------------------------
    // Search Filtering
    // ----------------------------------------------------
    $('#search-employee').on('input', function () {
        searchQuery = $(this).val().toLowerCase().trim();
        applyFilters();
    });

    // ----------------------------------------------------
    // Dynamic Category Filtering & Guard Dropdown Sync
    // ----------------------------------------------------
    function updateCategoryDropdown(guardFilter) {
        const categorySelect = $('#filter-category');
        const previousVal = categorySelect.val();
        categorySelect.empty();

        const filtered = allCategoryOptions.filter(opt => {
            if (opt.value === 'all') return false;
            return (guardFilter === 'all') || (opt.guard === guardFilter);
        });

        let allLabel = 'All Categories (' + filtered.length + ')';
        if (guardFilter === 'api') {
            allLabel = 'All POS Categories (' + filtered.length + ')';
        } else if (guardFilter === 'web') {
            allLabel = 'All Back-office Categories (' + filtered.length + ')';
        }

        categorySelect.append(
            $('<option></option>')
                .val('all')
                .attr('data-category', 'all')
                .attr('data-guard', 'all')
                .text(allLabel)
        );

        let optionStillExists = false;
        filtered.forEach(opt => {
            const optEl = $('<option></option>')
                .val(opt.value)
                .attr('data-category', opt.category)
                .attr('data-guard', opt.guard)
                .text(opt.label);

            if (opt.value === previousVal) {
                optEl.prop('selected', true);
                optionStillExists = true;
            }

            categorySelect.append(optEl);
        });

        if (!optionStillExists) {
            categorySelect.val('all');
        }
    }

    $('#filter-category').on('change', function () {
        applyColumnFilters();
    });

    $('#filter-guard').on('change', function () {
        currentGuardFilter = $(this).val();
        updateCategoryDropdown(currentGuardFilter);
        applyColumnFilters();
    });

    // ----------------------------------------------------
    // Show Modified Only Toggle
    // ----------------------------------------------------
    $('#toggle-modified-only').on('change', function () {
        showModifiedOnly = $(this).is(':checked');
        applyFilters();
    });

    // ----------------------------------------------------
    // Apply Row Filtering
    // ----------------------------------------------------
    function applyFilters() {
        let visibleRows = 0;

        $('.emp-row').each(function () {
            const row = $(this);
            const empName = row.data('user-name') ? row.data('user-name').toLowerCase() : '';
            const empRoles = (row.data('user-roles') || '').toLowerCase();
            const isModified = row.hasClass('is-modified');

            let matchesRole = (currentRoleFilter === 'all') || row.hasClass('r-' + currentRoleFilter);
            let matchesSearch = (searchQuery === '') || empName.includes(searchQuery) || empRoles.includes(searchQuery);
            let matchesModified = !showModifiedOnly || isModified;

            if (matchesRole && matchesSearch && matchesModified) {
                row.show();
                visibleRows++;
            } else {
                row.hide();
            }
        });

        $('#visible-emp-count').text(visibleRows);

        if (visibleRows === 0) {
            $('#empty-state-row').show();
        } else {
            $('#empty-state-row').hide();
        }
    }

    // ----------------------------------------------------
    // Apply Column Filtering (by category/guard)
    // ----------------------------------------------------
    function applyColumnFilters() {
        let visibleCols = 0;
        const selectedCatOption = $('#filter-category option:selected');
        const selectedCat = selectedCatOption.data('category') || 'all';
        const selectedCatGuard = (selectedCatOption.data('guard') || 'all').toLowerCase();

        $('.perm-th, .perm-td').each(function () {
            const el = $(this);
            const colCat = el.data('category');
            const colGuard = (el.data('guard') || '').toLowerCase();

            // Guard match from the guard dropdown
            let matchesGuard = (currentGuardFilter === 'all') || (colGuard === currentGuardFilter);

            // Category match from the category dropdown
            let matchesCat = true;
            if (selectedCat !== 'all') {
                matchesCat = (colCat === selectedCat);
                if (matchesCat && selectedCatGuard !== 'all') {
                    matchesCat = (colGuard === selectedCatGuard);
                }
            }

            if (matchesCat && matchesGuard) {
                el.show();
                if (el.hasClass('perm-th')) visibleCols++;
            } else {
                el.hide();
            }
        });

        // Update group header cells (colspan and visibility)
        $('.perm-group-th').each(function () {
            const groupEl = $(this);
            const gCat = groupEl.data('category');
            const gGuard = (groupEl.data('guard') || '').toLowerCase();

            // Count how many .perm-th in this group are visible
            const visibleChildren = $(`.perm-th[data-category="${gCat}"][data-guard="${gGuard}"]:visible`).length;
            if (visibleChildren > 0) {
                groupEl.attr('colspan', visibleChildren).show();
                if (visibleChildren === 1) {
                    groupEl.addClass('single-col-group');
                } else {
                    groupEl.removeClass('single-col-group');
                }
            } else {
                groupEl.hide();
            }
        });

        $('#visible-perm-count').text(visibleCols);
        syncHeaderStickyTops();
    }

    // Keep Row 2 sticky top exactly flush under Row 1
    function syncHeaderStickyTops() {
        const row1H = $('.header-group-row').outerHeight();
        if (row1H && row1H > 0) {
            $('.header-perm-row th, .sticky-col-row2, .sticky-actions-row2').css('top', row1H + 'px');
        }
    }
    $(window).on('resize', syncHeaderStickyTops);
    setTimeout(syncHeaderStickyTops, 100);

    // ----------------------------------------------------
    // Checkbox Change Handling & Change Tracking
    // ----------------------------------------------------
    $(document).on('change', '.chk-perm', function () {
        const chk = $(this);
        const row = chk.closest('.emp-row');
        const userId = row.data('user-id');
        const permId = parseInt(chk.val());
        const isChecked = chk.is(':checked');

        if (!currentPermissions[userId]) {
            currentPermissions[userId] = new Set();
        }

        if (isChecked) {
            currentPermissions[userId].add(permId);
        } else {
            currentPermissions[userId].delete(permId);
        }

        // Highlight modified cell
        const defaults = getEmployeeRoleDefaults(row);
        const originallyHad = defaults.has(permId);
        const isDifferentFromDefault = isChecked !== originallyHad;

        if (isDifferentFromDefault) {
            chk.closest('td').addClass('customized-cell');
        } else {
            chk.closest('td').removeClass('customized-cell');
        }

        updateRowModificationStatus(row, userId);
        updateSaveBar();
    });

    function getEmployeeRoleDefaults(row) {
        const roleIdsStr = row.data('role-ids') || '';
        const roleIds = roleIdsStr.toString().split(',').filter(Boolean);
        const defaultPerms = new Set();

        roleIds.forEach(function (rId) {
            const perms = roleDefaultPermissions[rId] || [];
            perms.forEach(p => defaultPerms.add(parseInt(p)));
        });

        return defaultPerms;
    }

    function updateRowModificationStatus(row, userId) {
        const defaults = getEmployeeRoleDefaults(row);
        const current = currentPermissions[userId] || new Set();

        let isDiff = false;
        if (current.size !== defaults.size) {
            isDiff = true;
        } else {
            for (let p of current) {
                if (!defaults.has(p)) {
                    isDiff = true;
                    break;
                }
            }
        }

        if (isDiff) {
            row.addClass('is-modified');
            row.find('.modified-pill').removeClass('d-none');
        } else {
            row.removeClass('is-modified');
            row.find('.modified-pill').addClass('d-none');
        }
    }

    function updateSaveBar() {
        let totalModified = 0;
        $('.emp-row').each(function () {
            const userId = $(this).data('user-id');
            const init = initialPermissions[userId] || new Set();
            const curr = currentPermissions[userId] || new Set();

            let rowDiff = false;
            if (init.size !== curr.size) {
                rowDiff = true;
            } else {
                for (let p of curr) {
                    if (!init.has(p)) {
                        rowDiff = true;
                        break;
                    }
                }
            }
            if (rowDiff) totalModified++;
        });

        unsavedChangesCount = totalModified;

        if (totalModified > 0) {
            $('#unsaved-badge').text(totalModified + ' employee' + (totalModified > 1 ? 's' : '') + ' modified').removeClass('d-none');
            $('#btn-save-changes').removeClass('disabled').prop('disabled', false);
        } else {
            $('#unsaved-badge').addClass('d-none');
            $('#btn-save-changes').addClass('disabled').prop('disabled', true);
        }
    }

    // ----------------------------------------------------
    // Individual Employee Reset to Role Defaults
    // ----------------------------------------------------
    $(document).on('click', '.btn-reset-row', function () {
        const btn = $(this);
        const row = btn.closest('.emp-row');
        const userId = row.data('user-id');
        const userName = row.data('user-name');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: window.resetUserPermissionUrl,
            type: 'POST',
            data: JSON.stringify({ user_id: userId }),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json'
        })
            .done(function (response) {
                if (response.success) {
                    const defaultPermIds = new Set((response.permissions || []).map(p => parseInt(p)));

                    // Update row checkboxes
                    row.find('.chk-perm').each(function () {
                        const permId = parseInt($(this).val());
                        const shouldCheck = defaultPermIds.has(permId);
                        $(this).prop('checked', shouldCheck);
                        $(this).closest('td').removeClass('customized-cell');
                    });

                    // Update sets
                    currentPermissions[userId] = new Set(defaultPermIds);
                    initialPermissions[userId] = new Set(defaultPermIds);

                    row.removeClass('is-modified');
                    row.find('.modified-pill').addClass('d-none');

                    updateSaveBar();
                    notify('success', 'Reset permissions to role defaults for ' + userName);
                } else {
                    notify('error', response.msg || 'Could not reset permissions.');
                }
            })
            .fail(function (xhr) {
                notify('error', 'Failed to reset employee permissions.');
            })
            .always(function () {
                btn.prop('disabled', false).text('Reset');
            });
    });

    // ----------------------------------------------------
    // Reset to Role Defaults (Bulk for all visible)
    // ----------------------------------------------------
    $('#btn-reset-defaults').on('click', function () {
        const visibleUserIds = [];
        $('.emp-row:visible').each(function () {
            visibleUserIds.push($(this).data('user-id'));
        });

        if (visibleUserIds.length === 0) {
            notify('info', 'No employees currently visible to reset.');
            return;
        }

        if (!confirm('Are you sure you want to reset all ' + visibleUserIds.length + ' visible employees to their role default permissions?')) {
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Resetting...');

        $.ajax({
            url: window.resetAllUsersPermissionUrl,
            type: 'POST',
            data: JSON.stringify({ user_ids: visibleUserIds }),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json'
        })
            .done(function (response) {
                if (response.success) {
                    const results = response.results || {};

                    visibleUserIds.forEach(function (uId) {
                        const row = $(`.emp-row[data-user-id="${uId}"]`);
                        const permList = results[uId] || [];
                        const permSet = new Set(permList.map(p => parseInt(p)));

                        row.find('.chk-perm').each(function () {
                            const pId = parseInt($(this).val());
                            $(this).prop('checked', permSet.has(pId));
                            $(this).closest('td').removeClass('customized-cell');
                        });

                        currentPermissions[uId] = new Set(permSet);
                        initialPermissions[uId] = new Set(permSet);

                        row.removeClass('is-modified');
                        row.find('.modified-pill').addClass('d-none');
                    });

                    updateSaveBar();
                    notify('success', response.msg || 'All visible employees reset to role defaults.');
                } else {
                    notify('error', response.msg || 'Reset failed.');
                }
            })
            .fail(function () {
                notify('error', 'Failed to reset employees.');
            })
            .always(function () {
                btn.prop('disabled', false).html('<i class="bx bx-reset me-1"></i> Reset to Role Defaults');
            });
    });

    // ----------------------------------------------------
    // Save Changes (Bulk Direct User Permissions)
    // ----------------------------------------------------
    $('#btn-save-changes').on('click', function () {
        const userPermissionsPayload = {};

        // Gather checked permissions for all employees
        $('.emp-row').each(function () {
            const userId = $(this).data('user-id');
            const perms = [];
            $(this).find('.chk-perm:checked').each(function () {
                perms.push(parseInt($(this).val()));
            });
            userPermissionsPayload[userId] = perms;
        });

        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

        $.ajax({
            url: window.saveUserPermissionsUrl,
            type: 'POST',
            data: JSON.stringify({ user_permissions: userPermissionsPayload }),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json'
        })
            .done(function (response) {
                if (response.success) {
                    // Update initial snapshot
                    for (let uId in userPermissionsPayload) {
                        initialPermissions[uId] = new Set(userPermissionsPayload[uId]);
                    }

                    updateSaveBar();
                    notify('success', response.msg || 'Permissions updated successfully.');
                } else {
                    notify('error', response.msg || 'Could not save permissions.');
                }
            })
            .fail(function (xhr) {
                let msg = 'Failed to save permissions.';
                if (xhr.responseJSON && xhr.responseJSON.msg) {
                    msg = xhr.responseJSON.msg;
                }
                notify('error', msg);
            })
            .always(function () {
                btn.prop('disabled', unsavedChangesCount === 0).html('<i class="bx bx-check-circle me-1"></i> Save Changes');
            });
    });

    // ----------------------------------------------------
    // Column Header Click to Select/Deselect All Visible
    // ----------------------------------------------------
    $(document).on('click', '.btn-toggle-col', function (e) {
        e.stopPropagation();
        const permId = $(this).data('perm-id');
        const visibleCheckboxes = $(`.emp-row:visible .chk-perm[value="${permId}"]`);
        const allChecked = visibleCheckboxes.length === visibleCheckboxes.filter(':checked').length;

        visibleCheckboxes.prop('checked', !allChecked).trigger('change');
    });

    // Group Header Click to Select/Deselect All Visible in Group
    $(document).on('click', '.perm-group-th', function (e) {
        e.stopPropagation();
        const gCat = $(this).data('category');
        const gGuard = ($(this).data('guard') || '').toLowerCase();
        const childPerms = $(`.perm-th[data-category="${gCat}"][data-guard="${gGuard}"]:visible`);
        const permIds = [];
        childPerms.each(function () {
            permIds.push(parseInt($(this).data('perm-id')));
        });

        if (permIds.length === 0) return;

        let allChecked = true;
        permIds.forEach(function (pId) {
            const boxes = $(`.emp-row:visible .chk-perm[value="${pId}"]`);
            if (boxes.length !== boxes.filter(':checked').length) {
                allChecked = false;
            }
        });

        permIds.forEach(function (pId) {
            $(`.emp-row:visible .chk-perm[value="${pId}"]`).prop('checked', !allChecked).trigger('change');
        });
    });

    // ----------------------------------------------------
    // Role Template Default Editor (Modal)
    // ----------------------------------------------------
    $('#modal-select-role').on('change', function () {
        const role = $(this).val();
        $('#role-modal-checkboxes .form-check-input').prop('checked', false);
        $('#modal-apply-defaults-btn').addClass('d-none');

        if (!role) return;

        $('#modal-loading').removeClass('d-none');

        $.get(window.loadRolePermissionUrl, { role: role })
            .done(function (response) {
                if (!response.error && response.resp) {
                    response.resp.forEach(function (id) {
                        $('#modal_perm_' + id).prop('checked', true);
                    });

                    if (response.has_default) {
                        $('#modal-apply-defaults-btn').removeClass('d-none');
                    }
                }
            })
            .always(function () {
                $('#modal-loading').addClass('d-none');
            });
    });

    $('#modal-apply-defaults-btn').on('click', function () {
        const role = $('#modal-select-role').val();
        if (!role) return;

        $.post(window.loadDefaultPermissionUrl, { role: role })
            .done(function (response) {
                if (!response.error && response.resp) {
                    $('#role-modal-checkboxes .form-check-input').prop('checked', false);
                    response.resp.forEach(function (id) {
                        $('#modal_perm_' + id).prop('checked', true);
                    });
                    notify('info', 'Loaded default role template for ' + role);
                }
            });
    });

    $(document).on('click', '.select-group-toggle', function () {
        const card = $(this).closest('.card');
        const checkboxes = card.find('.modal-perm-chk');
        const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        checkboxes.prop('checked', !allChecked);
        $(this).text(allChecked ? 'Select All' : 'Deselect All');
    });

    // ----------------------------------------------------
    // Role Template Form Submission with Existing User Prompt
    // ----------------------------------------------------
    let templateFormSubmitting = false;

    $('#update-role-template-form').on('submit', function (e) {
        if (templateFormSubmitting) {
            return true;
        }
        e.preventDefault();

        const form = this;
        const role = $('#modal-select-role').val();
        if (!role) {
            notify('error', 'Please select a role template first.');
            return false;
        }

        const safeRoleName = $('<div>').text(role).html();
        const swalObj = window.Swal || window.swal;

        if (swalObj && typeof swalObj.fire === 'function') {
            swalObj.fire({
                title: 'Update Existing Employees?',
                html: `<div class="text-start" style="font-size: 13.5px; color: #475569; line-height: 1.55;">
                        <p class="mb-3">Do you want to update permissions for all existing employees who currently have the <strong>${safeRoleName}</strong> role?</p>
                        <div class="p-3 rounded border" style="background-color: #f8fafc; font-size: 12.5px;">
                            <div class="mb-2">
                                <strong class="text-primary"><i class="bx bx-user-check me-1"></i> Update Existing Employees:</strong>
                                <div>Propagates these changes to all current employees assigned this role and updates the template.</div>
                            </div>
                            <div>
                                <strong class="text-secondary"><i class="bx bx-bookmark me-1"></i> Template Only:</strong>
                                <div>Updates the template for future employee assignments only; existing employees keep their current permissions.</div>
                            </div>
                        </div>
                       </div>`,
                icon: 'question',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: '<i class="bx bx-user-check me-1"></i> Update Existing Employees',
                denyButtonText: '<i class="bx bx-bookmark me-1"></i> Template Only',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#0980B2',
                denyButtonColor: '#475569',
                cancelButtonColor: '#94a3b8',
                reverseButtons: false,
                focusCancel: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    $('#modal-sync-existing-input').val('1');
                    templateFormSubmitting = true;
                    $('#btn-save-role-template').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
                    form.submit();
                } else if (result.isDenied) {
                    $('#modal-sync-existing-input').val('0');
                    templateFormSubmitting = true;
                    $('#btn-save-role-template').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
                    form.submit();
                }
            });
        } else {
            const sync = confirm(`Update permissions for all existing employees who have the "${role}" role as well?\n\n- Click OK to update Existing Employees & Template\n- Click Cancel to update ONLY the Template`);
            $('#modal-sync-existing-input').val(sync ? '1' : '0');
            templateFormSubmitting = true;
            $('#btn-save-role-template').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
            form.submit();
        }
    });

    // ----------------------------------------------------
    // Notification Helper
    // ----------------------------------------------------
    function notify(type, message) {
        if (typeof showAlert === 'function') {
            showAlert(type, message);
        } else if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else if (typeof tata !== 'undefined') {
            tata[type](type.toUpperCase(), message);
        } else {
            alert(message);
        }
    }

    // Run initial column filters
    applyColumnFilters();
    applyFilters();
    updateSaveBar();
});