/**
 * happy-hours.js
 * Handles individual item & variant selection with special price management for Happy Hours.
 */
$(document).ready(function () {

    /* ═══════════════════════════════════════════
       STATE
    ═══════════════════════════════════════════ */
    // selectedItems = { [key]: { key, itemId, itemVariantId, name, baseName, variantName, catName, regPrice, price } }
    // key is 'item_{itemId}' for simple items, or 'var_{variantId}' for variant items.
    var selectedItems = {};
    var enteredPrices = {};
    var activeCatId   = null;

    /* ═══════════════════════════════════════════
       ── SCHEDULE ──
    ═══════════════════════════════════════════ */

    // All Days checkbox
    $('#hh_all_days').on('click change', function () {
        var isChecked = $(this).is(':checked');
        $('.hh-days-checkbox').prop('checked', isChecked);
    });

    $(document).on('change', '.hh-days-checkbox', function () {
        var total = $('.hh-days-checkbox').length;
        var checked = $('.hh-days-checkbox:checked').length;
        if (checked === total && total > 0) {
            $('#hh_all_days').prop('indeterminate', false).prop('checked', true);
        } else if (checked > 0 && checked < total) {
            $('#hh_all_days').prop('indeterminate', true).prop('checked', false);
        } else {
            $('#hh_all_days').prop('indeterminate', false).prop('checked', false);
        }
    });

    // Bulk from-time setter
    $('#hh_schedule_from_time').on('input change', function () {
        var fromTime = $(this).val();
        $('.hh-from-field').val(fromTime);
    });

    // Bulk to-time setter
    $('#hh_schedule_to_time').on('input change', function () {
        var toTime = $(this).val();
        $('.hh-to-field').val(toTime);
    });

    /* ═══════════════════════════════════════════
       HELPERS — Category / Items / Variants
    ═══════════════════════════════════════════ */

    function getCategoryById(catId) {
        return hhCategories.find(function (c) { return c.id == catId; }) || null;
    }

    function getItemsForCat(catId) {
        var cat = getCategoryById(catId);
        return cat ? cat.items : [];
    }

    /** Count total selectable units (simple items + each variant) */
    function getTotalUnitsCount() {
        var count = 0;
        hhCategories.forEach(function (cat) {
            cat.items.forEach(function (item) {
                if (item.variants && item.variants.length > 0) {
                    count += item.variants.length;
                } else {
                    count += 1;
                }
            });
        });
        return count;
    }

    /** Get count of selectable units in a category */
    function getCatUnitsCount(catId) {
        var items = getItemsForCat(catId);
        var count = 0;
        items.forEach(function (item) {
            if (item.variants && item.variants.length > 0) {
                count += item.variants.length;
            } else {
                count += 1;
            }
        });
        return count;
    }

    /** Get all selectable units map across all categories */
    function getAllUnitsMap() {
        var all = {};
        hhCategories.forEach(function (cat) {
            cat.items.forEach(function (item) {
                if (item.variants && item.variants.length > 0) {
                    item.variants.forEach(function (v) {
                        var key = 'var_' + v.id;
                        var curPrice = (selectedItems[key] && selectedItems[key].price !== '' && selectedItems[key].price !== undefined)
                            ? selectedItems[key].price
                            : (enteredPrices[key] !== undefined ? enteredPrices[key] : '');
                        all[key] = {
                            key: key,
                            itemId: item.id,
                            itemVariantId: v.id,
                            name: item.name + ' (' + v.name + ')',
                            baseName: item.name,
                            variantName: v.name,
                            catName: cat.name,
                            regPrice: parseFloat(v.price || 0),
                            price: curPrice
                        };
                    });
                } else {
                    var key = 'item_' + item.id;
                    var curPrice = (selectedItems[key] && selectedItems[key].price !== '' && selectedItems[key].price !== undefined)
                        ? selectedItems[key].price
                        : (enteredPrices[key] !== undefined ? enteredPrices[key] : '');
                    all[key] = {
                        key: key,
                        itemId: item.id,
                        itemVariantId: null,
                        name: item.name,
                        baseName: item.name,
                        variantName: '',
                        catName: cat.name,
                        regPrice: parseFloat(item.price || 0),
                        price: curPrice
                    };
                }
            });
        });
        return all;
    }

    function isPriceInvalid(price, regPrice) {
        if (price === '' || price === null || price === undefined || isNaN(price)) {
            return false;
        }
        var p = parseFloat(price);
        var reg = parseFloat(regPrice || 0);
        return (reg > 0 && p >= reg);
    }

    /** Render the items grid for both inline picker and popup modal */
    function renderItemsGrid(catId) {
        var $inlineGrid = $('#hh-items-grid');
        var $modalGrid  = $('#hh-modal-items-grid');
        var items       = getItemsForCat(catId);
        var cat         = getCategoryById(catId);
        var catName     = cat ? cat.name : '';
        var searchQuery = ($('#hh-modal-search').val() || '').trim().toLowerCase();

        if (!items.length) {
            var emptyHtml = '<div class="hh-empty-cat">No items in this category</div>';
            $inlineGrid.html(emptyHtml);
            $modalGrid.html(emptyHtml);
            updateSelectAllControls(catId);
            return;
        }

        var inlineHtml = '';
        var modalHtml  = '';

        items.forEach(function (item) {
            var hasVariants = (item.variants && item.variants.length > 0);

            if (hasVariants) {
                // Item with variants
                var totalVars = item.variants.length;
                var checkedVarsCount = 0;
                var variantsHtml = '';

                item.variants.forEach(function (v) {
                    var vKey = 'var_' + v.id;
                    var isVSelected = selectedItems.hasOwnProperty(vKey);
                    if (isVSelected) checkedVarsCount++;

                    var vRegPrice = parseFloat(v.price || 0);
                    var vDisplayPrice = (isVSelected && selectedItems[vKey].price !== '' && selectedItems[vKey].price !== null && selectedItems[vKey].price !== undefined)
                        ? selectedItems[vKey].price
                        : (enteredPrices[vKey] !== undefined ? enteredPrices[vKey] : '');

                    var isVInvalid = isPriceInvalid(vDisplayPrice, vRegPrice);

                    variantsHtml +=
                        '<div class="hh-variant-row' + (isVSelected ? ' selected' : '') + '"' +
                        ' data-item-id="' + item.id + '" data-variant-id="' + v.id + '">' +
                            '<input type="hidden" name="regular_price" class="hh-regular-price" value="' + vRegPrice + '">' +
                            '<div class="hh-variant-left">' +
                                '<input type="checkbox" class="hh-variant-check"' +
                                ' data-item-id="' + item.id + '"' +
                                ' data-variant-id="' + v.id + '"' +
                                ' data-item-name="' + escapeHtml(item.name) + '"' +
                                ' data-variant-name="' + escapeHtml(v.name) + '"' +
                                ' data-cat-name="' + escapeHtml(catName) + '"' +
                                ' data-reg-price="' + vRegPrice + '"' +
                                (isVSelected ? ' checked' : '') + '>' +
                                '<div class="hh-variant-name" title="' + escapeHtml(v.name) + '">' + escapeHtml(v.name) + '</div>' +
                            '</div>' +
                            '<div class="hh-variant-reg">Reg: $' + vRegPrice.toFixed(2) + '</div>' +
                            '<div class="d-flex flex-column align-items-end">' +
                                '<div class="input-group input-group-sm hh-variant-price-group">' +
                                    '<span class="input-group-text">$</span>' +
                                    '<input type="number" step="0.01" min="0" class="form-control hh-variant-price-input' + (isVInvalid ? ' is-invalid' : '') + '"' +
                                    ' data-item-id="' + item.id + '"' +
                                    ' data-variant-id="' + v.id + '"' +
                                    ' value="' + (vDisplayPrice !== '' && vDisplayPrice !== null && vDisplayPrice !== undefined ? vDisplayPrice : '') + '"' +
                                    ' placeholder="0.00">' +
                                '</div>' +
                                '<div class="hh-price-error" style="display:' + (isVInvalid ? 'block' : 'none') + ';">' +
                                    'Must be &lt; $' + vRegPrice.toFixed(2) +
                                '</div>' +
                            '</div>' +
                        '</div>';
                });

                var isParentChecked = (checkedVarsCount === totalVars && totalVars > 0);

                var cardHtml =
                    '<div class="hh-item-card' + (checkedVarsCount > 0 ? ' selected' : '') + '"' +
                    ' data-item-id="' + item.id + '" data-has-variants="1">' +
                        '<div class="hh-card-header">' +
                            '<input type="checkbox" class="hh-parent-item-check" data-item-id="' + item.id + '"' +
                            (isParentChecked ? ' checked' : '') + '>' +
                            '<div class="hh-item-title-box">' +
                                '<div class="d-flex align-items-center justify-content-between gap-2">' +
                                    '<span class="hh-item-name" title="' + escapeHtml(item.name) + '">' + escapeHtml(item.name) + '</span>' +
                                    '<div class="d-flex align-items-center gap-1 flex-shrink-0">' +
                                        '<span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size:11px; font-weight:600;">' + totalVars + ' Variants</span>' +
                                        '<span class="hh-item-reg-price text-muted ms-1" style="font-size:11.5px;">(' + checkedVarsCount + '/' + totalVars + ')</span>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="hh-variants-container">' +
                            variantsHtml +
                        '</div>' +
                    '</div>';

                inlineHtml += cardHtml;

                // Modal search check
                var matchesSearch = !searchQuery ||
                    item.name.toLowerCase().indexOf(searchQuery) !== -1 ||
                    item.variants.some(function(v) { return v.name.toLowerCase().indexOf(searchQuery) !== -1; });

                if (matchesSearch) {
                    modalHtml += cardHtml;
                }

            } else {
                // Simple item without variants
                var itemKey = 'item_' + item.id;
                var isSelected = selectedItems.hasOwnProperty(itemKey);
                var regPrice = parseFloat(item.price || 0);
                var displayPrice = (isSelected && selectedItems[itemKey].price !== '' && selectedItems[itemKey].price !== null && selectedItems[itemKey].price !== undefined)
                    ? selectedItems[itemKey].price
                    : (enteredPrices[itemKey] !== undefined ? enteredPrices[itemKey] : '');

                var isInvalid = isPriceInvalid(displayPrice, regPrice);

                var cardHtml =
                    '<div class="hh-item-card' + (isSelected ? ' selected' : '') + '"' +
                    ' data-item-id="' + item.id + '" data-has-variants="0"' +
                    ' data-item-name="' + escapeHtml(item.name) + '"' +
                    ' data-cat-name="' + escapeHtml(catName) + '"' +
                    ' data-item-reg-price="' + regPrice + '">' +
                        '<div class="hh-card-header">' +
                            '<input type="checkbox" class="hh-item-check" data-item-id="' + item.id + '"' + (isSelected ? ' checked' : '') + '>' +
                            '<div class="hh-item-title-box">' +
                                '<div class="d-flex align-items-center justify-content-between gap-2">' +
                                    '<span class="hh-item-name" title="' + escapeHtml(item.name) + '">' + escapeHtml(item.name) + '</span>' +
                                    '<span class="badge bg-light text-muted border rounded-pill px-2 py-1 flex-shrink-0" style="font-size:11.5px;">Reg: $' + regPrice.toFixed(2) + '</span>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="hh-simple-card-body">' +
                            '<input type="hidden" name="regular_price" class="hh-regular-price" value="' + regPrice + '">' +
                            '<span class="hh-price-label">Happy Hour Price:</span>' +
                            '<div class="d-flex flex-column align-items-end">' +
                                '<div class="input-group input-group-sm hh-price-group">' +
                                    '<span class="input-group-text">$</span>' +
                                    '<input type="number" step="0.01" min="0" class="form-control hh-card-price-input' + (isInvalid ? ' is-invalid' : '') + '"' +
                                    ' data-item-id="' + item.id + '"' +
                                    ' value="' + (displayPrice !== '' && displayPrice !== null && displayPrice !== undefined ? displayPrice : '') + '"' +
                                    ' placeholder="0.00">' +
                                '</div>' +
                                '<div class="hh-price-error" style="display:' + (isInvalid ? 'block' : 'none') + ';">' +
                                    'Must be &lt; $' + regPrice.toFixed(2) +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';

                inlineHtml += cardHtml;

                if (!searchQuery || item.name.toLowerCase().indexOf(searchQuery) !== -1) {
                    modalHtml += cardHtml;
                }
            }
        });

        $inlineGrid.html(inlineHtml);

        if (modalHtml === '') {
            $modalGrid.html('<div class="hh-empty-cat">No matching items found</div>');
        } else {
            $modalGrid.html(modalHtml);
        }

        // Apply indeterminate state to parent checkboxes where applicable
        $('.hh-parent-item-check').each(function () {
            var $card = $(this).closest('.hh-item-card');
            var totalV = $card.find('.hh-variant-check').length;
            var checkedV = $card.find('.hh-variant-check:checked').length;

            if (checkedV > 0 && checkedV < totalV) {
                $(this).prop('indeterminate', true);
            } else {
                $(this).prop('indeterminate', false);
            }
        });

        updateSelectAllControls(catId);
    }

    /** Update both Category Select All and Global Select All checkbox states */
    function updateSelectAllControls(catId) {
        var totalCatUnits = getCatUnitsCount(catId);
        var items = getItemsForCat(catId);
        var $inlineCatAll = $('#hh-select-all-cat');
        var $modalCatAll  = $('#hh-modal-select-all-cat');
        var $inlineCnt    = $('#hh-cat-count');
        var $modalCnt     = $('#hh-modal-cat-count');

        if (totalCatUnits === 0) {
            $inlineCatAll.prop('indeterminate', false).prop('checked', false);
            $modalCatAll.prop('indeterminate', false).prop('checked', false);
            $inlineCnt.text('');
            $modalCnt.text('');
        } else {
            var selectedInCatCount = 0;
            items.forEach(function (item) {
                if (item.variants && item.variants.length > 0) {
                    item.variants.forEach(function (v) {
                        if (selectedItems.hasOwnProperty('var_' + v.id)) selectedInCatCount++;
                    });
                } else {
                    if (selectedItems.hasOwnProperty('item_' + item.id)) selectedInCatCount++;
                }
            });

            var countText = '(' + selectedInCatCount + ' / ' + totalCatUnits + ')';
            $inlineCnt.text(countText);
            $modalCnt.text(countText);

            if (selectedInCatCount === totalCatUnits && totalCatUnits > 0) {
                $inlineCatAll.prop('indeterminate', false).prop('checked', true);
                $modalCatAll.prop('indeterminate', false).prop('checked', true);
            } else if (selectedInCatCount > 0 && selectedInCatCount < totalCatUnits) {
                $inlineCatAll.prop('indeterminate', true).prop('checked', false);
                $modalCatAll.prop('indeterminate', true).prop('checked', false);
            } else {
                $inlineCatAll.prop('indeterminate', false).prop('checked', false);
                $modalCatAll.prop('indeterminate', false).prop('checked', false);
            }
        }

        var totalUnits    = getTotalUnitsCount();
        var totalSelected = Object.keys(selectedItems).length;
        var $inlineGlobal = $('#hh-select-all-global');
        var $modalGlobal  = $('#hh-modal-select-all-global');

        if (totalUnits > 0 && totalSelected >= totalUnits) {
            $inlineGlobal.prop('indeterminate', false).prop('checked', true);
            $modalGlobal.prop('indeterminate', false).prop('checked', true);
        } else if (totalSelected > 0 && totalSelected < totalUnits) {
            $inlineGlobal.prop('indeterminate', true).prop('checked', false);
            $modalGlobal.prop('indeterminate', true).prop('checked', false);
        } else {
            $inlineGlobal.prop('indeterminate', false).prop('checked', false);
            $modalGlobal.prop('indeterminate', false).prop('checked', false);
        }
    }

    /** Update hidden inputs for form submission */
    function updateHiddenInputs() {
        var $hidden = $('#hh-items-hidden');
        $hidden.empty();
        var idx = 0;
        Object.values(selectedItems).forEach(function (item) {
            var priceVal = (item.price !== undefined && item.price !== null && item.price !== '') ? item.price : 0;
            $hidden.append('<input type="hidden" name="items[' + idx + '][item_id]" value="' + item.itemId + '">');
            $hidden.append('<input type="hidden" name="items[' + idx + '][item_variant_id]" value="' + (item.itemVariantId || '') + '">');
            $hidden.append('<input type="hidden" name="items[' + idx + '][price]" value="' + priceVal + '">');
            idx++;
        });
    }

    /** Re-render the summary table + hidden inputs + count badges */
    function refreshSelectedUI() {
        var $inlineTbody       = $('#hh-summary-tbody');
        var $modalTbody        = $('#hh-modal-summary-tbody');
        var count              = Object.keys(selectedItems).length;

        // Badges
        $('#hh-selected-count').text(count + ' selected');
        $('#hh-modal-selected-count').text(count + ' selected');

        if (count === 0) {
            $inlineTbody.html('<tr><td colspan="4" class="hh-summary-empty">No items selected yet. Pick items from categories above and set their special prices.</td></tr>');
            $modalTbody.html('<tr><td colspan="4" class="hh-summary-empty">No items selected yet.</td></tr>');
        } else {
            var rowsHtml = '';
            Object.values(selectedItems).forEach(function (item) {
                var displayName = escapeHtml(item.baseName);
                var variantBadge = item.variantName ? '<span class="badge bg-primary-subtle text-primary border ms-1" style="font-size:11px;">' + escapeHtml(item.variantName) + '</span>' : '';
                var catBadge = item.catName ? '<span class="badge bg-light text-secondary border ms-1" style="font-size:10.5px;">' + escapeHtml(item.catName) + '</span>' : '';
                var displayPrice = (item.price !== null && item.price !== undefined && item.price !== '') ? item.price : '';
                var regPrice = parseFloat(item.regPrice || 0);
                var isInvalid = isPriceInvalid(displayPrice, regPrice);

                rowsHtml +=
                    '<tr data-key="' + item.key + '">' +
                        '<td>' +
                            '<div class="d-flex align-items-center flex-wrap gap-1">' +
                                '<span class="fw-semibold">' + displayName + '</span>' +
                                variantBadge +
                                catBadge +
                            '</div>' +
                        '</td>' +
                        '<td><span class="text-muted">$' + regPrice.toFixed(2) + '</span></td>' +
                        '<td>' +
                            '<div class="d-flex flex-column">' +
                                '<div class="input-group input-group-sm" style="max-width: 120px;">' +
                                    '<span class="input-group-text">$</span>' +
                                    '<input type="number" step="0.01" min="0" class="form-control hh-summary-price-input' + (isInvalid ? ' is-invalid' : '') + '"' +
                                    ' data-key="' + item.key + '"' +
                                    ' value="' + displayPrice + '" placeholder="0.00">' +
                                '</div>' +
                                '<div class="hh-price-error" style="text-align: left; display:' + (isInvalid ? 'block' : 'none') + ';">' +
                                    'Must be &lt; $' + regPrice.toFixed(2) +
                                '</div>' +
                            '</div>' +
                        '</td>' +
                        '<td class="text-end">' +
                            '<button type="button" class="btn btn-sm btn-link text-danger p-0 hh-summary-remove-btn" data-key="' + item.key + '" title="Remove">' +
                                '<i class="bx bx-trash fs-5"></i>' +
                            '</button>' +
                        '</td>' +
                    '</tr>';
            });

            $inlineTbody.html(rowsHtml);
            $modalTbody.html(rowsHtml);
        }

        updateHiddenInputs();
    }

    function escapeHtml(str) {
        return $('<div>').text(str || '').html();
    }

    /* ═══════════════════════════════════════════
       CATEGORY TAB CLICK
    ═══════════════════════════════════════════ */
    $(document).on('click', '.hh-cat-tab', function () {
        activeCatId = $(this).data('cat-id');
        $('.hh-cat-tab').removeClass('active');
        $('.hh-cat-tab[data-cat-id="' + activeCatId + '"]').addClass('active');
        renderItemsGrid(activeCatId);
    });

    /* ═══════════════════════════════════════════
       SIMPLE ITEM CARD HEADER CLICK (Toggle checkbox)
    ═══════════════════════════════════════════ */
    $(document).on('click', '.hh-item-card[data-has-variants="0"] .hh-card-header', function (e) {
        if ($(e.target).is('input[type="checkbox"]')) return;
        var $card = $(this).closest('.hh-item-card');
        var $cb   = $card.find('.hh-item-check');
        $cb.prop('checked', !$cb.prop('checked')).trigger('change');
    });

    $(document).on('change', '.hh-item-check', function () {
        var $cb       = $(this);
        var itemId    = $cb.data('item-id');
        var itemKey   = 'item_' + itemId;
        var $card     = $cb.closest('.hh-item-card');
        var name      = $card.data('item-name');
        var catName   = $card.data('cat-name');
        var regPrice  = parseFloat($card.data('item-reg-price') || 0);
        var inputVal  = $card.find('.hh-card-price-input').val();
        var price     = (inputVal !== '' && inputVal !== undefined && !isNaN(inputVal)) ? parseFloat(inputVal) : '';
        var isChecked = $cb.is(':checked');

        if (isChecked) {
            selectedItems[itemKey] = {
                key: itemKey,
                itemId: itemId,
                itemVariantId: null,
                name: name,
                baseName: name,
                variantName: '',
                catName: catName,
                regPrice: regPrice,
                price: price
            };
            if (price !== '') {
                enteredPrices[itemKey] = price;
            }
        } else {
            delete selectedItems[itemKey];
        }

        // Sync card states across grids
        $('.hh-item-card[data-item-id="' + itemId + '"][data-has-variants="0"]').each(function () {
            $(this).toggleClass('selected', isChecked);
            $(this).find('.hh-item-check').prop('checked', isChecked);
        });

        updateSelectAllControls(activeCatId);
        refreshSelectedUI();
    });

    /* ═══════════════════════════════════════════
       SIMPLE ITEM CARD PRICE INPUT
    ═══════════════════════════════════════════ */
    $(document).on('input change', '.hh-card-price-input', function () {
        var $input   = $(this);
        var itemId   = $input.data('item-id');
        var itemKey  = 'item_' + itemId;
        var rawVal   = $input.val();
        var newPrice = (rawVal !== '' && !isNaN(rawVal)) ? parseFloat(rawVal) : (rawVal === '' ? '' : 0);
        var $card    = $input.closest('.hh-item-card');
        var name     = $card.data('item-name');
        var catName  = $card.data('cat-name');
        var regPrice = parseFloat($card.find('.hh-regular-price').val() || $card.data('item-reg-price') || 0);

        if (newPrice !== '') {
            enteredPrices[itemKey] = newPrice;
        }

        selectedItems[itemKey] = {
            key: itemKey,
            itemId: itemId,
            itemVariantId: null,
            name: name,
            baseName: name,
            variantName: '',
            catName: catName,
            regPrice: regPrice,
            price: newPrice
        };

        var isInvalid = isPriceInvalid(newPrice, regPrice);

        $('.hh-item-card[data-item-id="' + itemId + '"][data-has-variants="0"]').each(function () {
            $(this).addClass('selected');
            $(this).find('.hh-item-check').prop('checked', true);
            var $otherInput = $(this).find('.hh-card-price-input');
            $otherInput.not($input).val(rawVal);
            $otherInput.toggleClass('is-invalid', isInvalid);
            $(this).find('.hh-price-error').toggle(isInvalid);
        });

        $input.toggleClass('is-invalid', isInvalid);
        $card.find('.hh-price-error').toggle(isInvalid);

        // Update summary input without full re-render
        var $sumInput = $('.hh-summary-price-input[data-key="' + itemKey + '"]');
        $sumInput.val(rawVal);
        $sumInput.toggleClass('is-invalid', isInvalid);
        $sumInput.closest('td').find('.hh-price-error').toggle(isInvalid);

        updateSelectAllControls(activeCatId);
        updateHiddenInputs();
        $('#hh-selected-count').text(Object.keys(selectedItems).length + ' selected');
        $('#hh-modal-selected-count').text(Object.keys(selectedItems).length + ' selected');
    });

    /* ═══════════════════════════════════════════
       PARENT ITEM (WITH VARIANTS) HEADER CLICK
    ═══════════════════════════════════════════ */
    $(document).on('click', '.hh-item-card[data-has-variants="1"] .hh-card-header', function (e) {
        if ($(e.target).is('input[type="checkbox"]')) return;
        var $pCb = $(this).find('.hh-parent-item-check');
        $pCb.prop('checked', !$pCb.prop('checked')).trigger('change');
    });

    $(document).on('change', '.hh-parent-item-check', function () {
        var $pCb      = $(this);
        var itemId    = $pCb.data('item-id');
        var isChecked = $pCb.is(':checked');
        var item      = null;

        hhCategories.forEach(function (cat) {
            cat.items.forEach(function (i) {
                if (i.id == itemId) item = i;
            });
        });

        if (!item || !item.variants) return;
        var cat = getCategoryById(activeCatId);
        var catName = cat ? cat.name : '';

        item.variants.forEach(function (v) {
            var vKey = 'var_' + v.id;
            if (isChecked) {
                var curPrice = (selectedItems[vKey] && selectedItems[vKey].price !== '' && selectedItems[vKey].price !== undefined)
                    ? selectedItems[vKey].price
                    : (enteredPrices[vKey] !== undefined ? enteredPrices[vKey] : '');
                selectedItems[vKey] = {
                    key: vKey,
                    itemId: item.id,
                    itemVariantId: v.id,
                    name: item.name + ' (' + v.name + ')',
                    baseName: item.name,
                    variantName: v.name,
                    catName: catName,
                    regPrice: parseFloat(v.price || 0),
                    price: curPrice
                };
            } else {
                delete selectedItems[vKey];
            }
        });

        renderItemsGrid(activeCatId);
        refreshSelectedUI();
    });

    /* ═══════════════════════════════════════════
       INDIVIDUAL VARIANT ROW / CHECKBOX CLICK
    ═══════════════════════════════════════════ */
    $(document).on('click', '.hh-variant-left', function (e) {
        if ($(e.target).is('input[type="checkbox"]')) return;
        var $vCb = $(this).find('.hh-variant-check');
        $vCb.prop('checked', !$vCb.prop('checked')).trigger('change');
    });

    $(document).on('change', '.hh-variant-check', function () {
        var $vCb       = $(this);
        var itemId     = $vCb.data('item-id');
        var variantId  = $vCb.data('variant-id');
        var vKey       = 'var_' + variantId;
        var itemName   = $vCb.data('item-name');
        var varName    = $vCb.data('variant-name');
        var catName    = $vCb.data('cat-name');
        var regPrice   = parseFloat($vCb.data('reg-price') || 0);
        var $row       = $vCb.closest('.hh-variant-row');
        var inputVal   = $row.find('.hh-variant-price-input').val();
        var price      = (inputVal !== '' && inputVal !== undefined && !isNaN(inputVal)) ? parseFloat(inputVal) : '';
        var isChecked  = $vCb.is(':checked');

        if (isChecked) {
            selectedItems[vKey] = {
                key: vKey,
                itemId: itemId,
                itemVariantId: variantId,
                name: itemName + ' (' + varName + ')',
                baseName: itemName,
                variantName: varName,
                catName: catName,
                regPrice: regPrice,
                price: price
            };
            if (price !== '') {
                enteredPrices[vKey] = price;
            }
        } else {
            delete selectedItems[vKey];
        }

        // Sync variant states across grids
        $('.hh-variant-check[data-variant-id="' + variantId + '"]').each(function () {
            $(this).prop('checked', isChecked);
            $(this).closest('.hh-variant-row').toggleClass('selected', isChecked);
        });

        // Update parent card status
        $('.hh-item-card[data-item-id="' + itemId + '"][data-has-variants="1"]').each(function () {
            var totalV = $(this).find('.hh-variant-check').length;
            var checkedV = $(this).find('.hh-variant-check:checked').length;
            var $pCb = $(this).find('.hh-parent-item-check');

            $(this).toggleClass('selected', checkedV > 0);
            $(this).find('.hh-item-reg-price').text('(' + checkedV + '/' + totalV + ')');

            if (checkedV === totalV && totalV > 0) {
                $pCb.prop('indeterminate', false).prop('checked', true);
            } else if (checkedV > 0 && checkedV < totalV) {
                $pCb.prop('indeterminate', true).prop('checked', false);
            } else {
                $pCb.prop('indeterminate', false).prop('checked', false);
            }
        });

        updateSelectAllControls(activeCatId);
        refreshSelectedUI();
    });

    /* ═══════════════════════════════════════════
       INDIVIDUAL VARIANT PRICE INPUT
    ═══════════════════════════════════════════ */
    $(document).on('input change', '.hh-variant-price-input', function () {
        var $input    = $(this);
        var itemId    = $input.data('item-id');
        var variantId = $input.data('variant-id');
        var vKey      = 'var_' + variantId;
        var rawVal    = $input.val();
        var newPrice  = (rawVal !== '' && !isNaN(rawVal)) ? parseFloat(rawVal) : (rawVal === '' ? '' : 0);
        var $row      = $input.closest('.hh-variant-row');
        var $vCb      = $row.find('.hh-variant-check');
        var itemName  = $vCb.data('item-name');
        var varName   = $vCb.data('variant-name');
        var catName   = $vCb.data('cat-name');
        var regPrice  = parseFloat($row.find('.hh-regular-price').val() || $vCb.data('reg-price') || 0);

        if (newPrice !== '') {
            enteredPrices[vKey] = newPrice;
        }

        selectedItems[vKey] = {
            key: vKey,
            itemId: itemId,
            itemVariantId: variantId,
            name: itemName + ' (' + varName + ')',
            baseName: itemName,
            variantName: varName,
            catName: catName,
            regPrice: regPrice,
            price: newPrice
        };

        var isInvalid = isPriceInvalid(newPrice, regPrice);

        $('.hh-variant-price-input[data-variant-id="' + variantId + '"]').not($input).val(rawVal);
        $('.hh-variant-price-input[data-variant-id="' + variantId + '"]').toggleClass('is-invalid', isInvalid);
        $('.hh-variant-row[data-variant-id="' + variantId + '"]').each(function () {
            $(this).find('.hh-price-error').toggle(isInvalid);
            $(this).find('.hh-variant-check').prop('checked', true);
            $(this).addClass('selected');
        });

        $input.toggleClass('is-invalid', isInvalid);
        $row.find('.hh-price-error').toggle(isInvalid);

        // Update summary input without full re-render
        var $sumInput = $('.hh-summary-price-input[data-key="' + vKey + '"]');
        $sumInput.val(rawVal);
        $sumInput.toggleClass('is-invalid', isInvalid);
        $sumInput.closest('td').find('.hh-price-error').toggle(isInvalid);

        // Update parent card
        $('.hh-item-card[data-item-id="' + itemId + '"][data-has-variants="1"]').each(function () {
            var totalV = $(this).find('.hh-variant-check').length;
            var checkedV = $(this).find('.hh-variant-check:checked').length;
            var $pCb = $(this).find('.hh-parent-item-check');

            $(this).addClass('selected');
            $(this).find('.hh-item-reg-price').text('(' + checkedV + '/' + totalV + ')');

            if (checkedV === totalV && totalV > 0) {
                $pCb.prop('indeterminate', false).prop('checked', true);
            } else if (checkedV > 0 && checkedV < totalV) {
                $pCb.prop('indeterminate', true).prop('checked', false);
            } else {
                $pCb.prop('indeterminate', false).prop('checked', false);
            }
        });

        updateSelectAllControls(activeCatId);
        updateHiddenInputs();
        $('#hh-selected-count').text(Object.keys(selectedItems).length + ' selected');
        $('#hh-modal-selected-count').text(Object.keys(selectedItems).length + ' selected');
    });

    /* ═══════════════════════════════════════════
       SUMMARY TABLE PRICE INPUT
    ═══════════════════════════════════════════ */
    $(document).on('input change', '.hh-summary-price-input', function () {
        var key      = $(this).data('key');
        var rawVal   = $(this).val();
        var newPrice = (rawVal !== '' && !isNaN(rawVal)) ? parseFloat(rawVal) : (rawVal === '' ? '' : 0);
        var regPrice = selectedItems[key] ? parseFloat(selectedItems[key].regPrice || 0) : 0;
        var isInvalid = isPriceInvalid(newPrice, regPrice);

        if (selectedItems[key]) {
            selectedItems[key].price = newPrice;
        }

        if (newPrice !== '') {
            enteredPrices[key] = newPrice;
        }

        // Sync inputs in summary tables and cards without recreating DOM
        $('.hh-summary-price-input[data-key="' + key + '"]').val(rawVal).toggleClass('is-invalid', isInvalid);
        $('.hh-summary-price-input[data-key="' + key + '"]').closest('td').find('.hh-price-error').toggle(isInvalid);

        if (key.indexOf('var_') === 0) {
            var variantId = key.replace('var_', '');
            var $vInput = $('.hh-variant-price-input[data-variant-id="' + variantId + '"]');
            $vInput.val(rawVal).toggleClass('is-invalid', isInvalid);
            $vInput.closest('.d-flex.flex-column').find('.hh-price-error').toggle(isInvalid);
        } else {
            var itemId = key.replace('item_', '');
            var $cInput = $('.hh-card-price-input[data-item-id="' + itemId + '"]');
            $cInput.val(rawVal).toggleClass('is-invalid', isInvalid);
            $cInput.closest('.d-flex.flex-column').find('.hh-price-error').toggle(isInvalid);
        }

        updateHiddenInputs();
    });

    /* ═══════════════════════════════════════════
       SUMMARY REMOVE BUTTON
    ═══════════════════════════════════════════ */
    $(document).on('click', '.hh-summary-remove-btn', function () {
        var key = $(this).data('key');
        delete selectedItems[key];

        renderItemsGrid(activeCatId);
        refreshSelectedUI();
    });

    /* ═══════════════════════════════════════════
       SELECT-ALL IN CATEGORY (Inline & Modal)
    ═══════════════════════════════════════════ */
    $('#hh-select-all-cat, #hh-modal-select-all-cat').on('change', function () {
        var selectAll = $(this).is(':checked');
        var items     = getItemsForCat(activeCatId);
        var cat       = getCategoryById(activeCatId);
        var catName   = cat ? cat.name : '';

        items.forEach(function (item) {
            if (item.variants && item.variants.length > 0) {
                item.variants.forEach(function (v) {
                    var vKey = 'var_' + v.id;
                    if (selectAll) {
                        var curPrice = (selectedItems[vKey] && selectedItems[vKey].price !== '' && selectedItems[vKey].price !== undefined)
                            ? selectedItems[vKey].price
                            : (enteredPrices[vKey] !== undefined ? enteredPrices[vKey] : '');
                        selectedItems[vKey] = {
                            key: vKey,
                            itemId: item.id,
                            itemVariantId: v.id,
                            name: item.name + ' (' + v.name + ')',
                            baseName: item.name,
                            variantName: v.name,
                            catName: catName,
                            regPrice: parseFloat(v.price || 0),
                            price: curPrice
                        };
                    } else {
                        delete selectedItems[vKey];
                    }
                });
            } else {
                var itemKey = 'item_' + item.id;
                if (selectAll) {
                    var curPrice = (selectedItems[itemKey] && selectedItems[itemKey].price !== '' && selectedItems[itemKey].price !== undefined)
                        ? selectedItems[itemKey].price
                        : (enteredPrices[itemKey] !== undefined ? enteredPrices[itemKey] : '');
                    selectedItems[itemKey] = {
                        key: itemKey,
                        itemId: item.id,
                        itemVariantId: null,
                        name: item.name,
                        baseName: item.name,
                        variantName: '',
                        catName: catName,
                        regPrice: parseFloat(item.price || 0),
                        price: curPrice
                    };
                } else {
                    delete selectedItems[itemKey];
                }
            }
        });

        renderItemsGrid(activeCatId);
        refreshSelectedUI();
    });

    /* ═══════════════════════════════════════════
       GLOBAL SELECT-ALL (All Categories)
    ═══════════════════════════════════════════ */
    $('#hh-select-all-global, #hh-modal-select-all-global').on('change', function () {
        var selectAll = $(this).is(':checked');

        if (selectAll) {
            selectedItems = getAllUnitsMap();
        } else {
            selectedItems = {};
        }

        renderItemsGrid(activeCatId);
        refreshSelectedUI();
    });

    /* ═══════════════════════════════════════════
       QUICK DISCOUNT FOR CATEGORY
    ═══════════════════════════════════════════ */
    function applyCategoryDiscount(discountPct) {
        if (discountPct < 0 || isNaN(discountPct)) return;
        var items   = getItemsForCat(activeCatId);

        items.forEach(function (item) {
            if (item.variants && item.variants.length > 0) {
                item.variants.forEach(function (v) {
                    var vKey = 'var_' + v.id;
                    var reg = parseFloat(v.price || 0);
                    var discounted = Math.max(0, reg * (1 - (discountPct / 100)));
                    discounted = Math.round(discounted * 100) / 100;

                    enteredPrices[vKey] = discounted;
                    if (selectedItems.hasOwnProperty(vKey)) {
                        selectedItems[vKey].price = discounted;
                    }
                    $('.hh-variant-price-input[data-variant-id="' + v.id + '"]').val(discounted).removeClass('is-invalid');
                    $('.hh-variant-row[data-variant-id="' + v.id + '"]').find('.hh-price-error').hide();
                });
            } else {
                var itemKey = 'item_' + item.id;
                var reg = parseFloat(item.price || 0);
                var discounted = Math.max(0, reg * (1 - (discountPct / 100)));
                discounted = Math.round(discounted * 100) / 100;

                enteredPrices[itemKey] = discounted;
                if (selectedItems.hasOwnProperty(itemKey)) {
                    selectedItems[itemKey].price = discounted;
                }
                $('.hh-card-price-input[data-item-id="' + item.id + '"]').val(discounted).removeClass('is-invalid');
                $('.hh-item-card[data-item-id="' + item.id + '"]').find('.hh-price-error').hide();
            }
        });

        refreshSelectedUI();
    }

    $('#hh-apply-cat-discount').on('click', function () {
        var pct = parseFloat($('#hh-quick-cat-discount').val());
        applyCategoryDiscount(pct);
    });

    $('#hh-modal-apply-cat-discount').on('click', function () {
        var pct = parseFloat($('#hh-modal-cat-discount').val());
        applyCategoryDiscount(pct);
    });

    /* ═══════════════════════════════════════════
       MODAL SEARCH FILTER
    ═══════════════════════════════════════════ */
    $('#hh-modal-search').on('input', function () {
        renderItemsGrid(activeCatId);
    });

    /* ═══════════════════════════════════════════
       CLEAR ALL
    ═══════════════════════════════════════════ */
    $('#hh-clear-all-btn, #hh-modal-clear-all').on('click', function () {
        selectedItems = {};
        renderItemsGrid(activeCatId);
        refreshSelectedUI();
    });

    /* ═══════════════════════════════════════════
       MODAL SHOWN EVENT
    ═══════════════════════════════════════════ */
    $('#hhItemsModal').on('shown.bs.modal', function () {
        if (!activeCatId) {
            var $first = $('.hh-cat-tab').first();
            if ($first.length) {
                activeCatId = $first.data('cat-id');
            }
        }
        $('.hh-cat-tab').removeClass('active');
        $('.hh-cat-tab[data-cat-id="' + activeCatId + '"]').addClass('active');
        renderItemsGrid(activeCatId);
        refreshSelectedUI();
    });

    /* ═══════════════════════════════════════════
       DELETE MODAL
    ═══════════════════════════════════════════ */
    $(document).on('click', '.hh-delete-btn', function () {
        var url = $(this).data('url');
        $('#deleteHHBtn').attr('href', url);
    });

    /* ═══════════════════════════════════════════
       EDIT BUTTON — AJAX POPULATE
    ═══════════════════════════════════════════ */
    $(document).on('click', '.hh-edit-btn', function (e) {
        e.preventDefault();
        var id  = $(this).data('id');
        var url = hhDetailUrl.replace(':id', id);

        // Highlight row
        $('#hh_table').find('tr').each(function () {
            this.style.removeProperty('background-color');
        });
        $(this).closest('tr').css('background-color', '#fffbe6');

        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                if (!response.success) return;
                var data = response.data;

                // Form action & title
                var formUrl = hhUpdateUrl.replace(':id', data.id);
                $('#hh-form').attr('action', formUrl);
                $('#hh-form-title').text('Edit Happy Hours');
                $('button[type=submit]').text('Update');
                $('#hh_edit_id').val(data.id);

                // Name & Status
                $('#hh_name').val(data.name).focus();
                $('#hh_is_active').val(data.is_active);

                // Order types
                $('.hh-order-type-check').prop('checked', false);
                (data.order_types || []).forEach(function (ot) {
                    $('#ot_' + ot).prop('checked', true);
                });

                // ── Schedule ──
                $('.hh-days-checkbox').prop('checked', false);
                $('.hh-from-field').val('');
                $('.hh-to-field').val('');
                $('#hh_schedule_from_time').val('');
                $('#hh_schedule_to_time').val('');

                (data.days || []).forEach(function (day) {
                    $('#hh_' + day).prop('checked', true);
                });

                if (data.schedule) {
                    for (var day in data.schedule) {
                        var times = data.schedule[day];
                        $('#hh_' + day + '_from_time').val(times.from || '');
                        $('#hh_' + day + '_to_time').val(times.to || '');
                    }
                }

                if ((data.days || []).length === 7) {
                    $('#hh_all_days').prop('checked', true);
                } else {
                    $('#hh_all_days').prop('checked', false);
                }

                // ── Items & Variants ──
                selectedItems = {};
                enteredPrices = {};
                (data.items || []).forEach(function (item) {
                    var isVar = !!item.item_variant_id;
                    var key = isVar ? ('var_' + item.item_variant_id) : ('item_' + item.item_id);
                    var priceVal = (item.price !== null && item.price !== undefined && item.price !== '') ? parseFloat(item.price) : '';
                    if (priceVal !== '') {
                        enteredPrices[key] = priceVal;
                    }

                    var catName = '';
                    var baseName = item.name;
                    var variantName = '';

                    hhCategories.forEach(function (c) {
                        c.items.forEach(function (ci) {
                            if (ci.id == item.item_id) {
                                catName = c.name;
                                baseName = ci.name;
                                if (isVar && ci.variants) {
                                    ci.variants.forEach(function (civ) {
                                        if (civ.id == item.item_variant_id) {
                                            variantName = civ.name;
                                        }
                                    });
                                }
                            }
                        });
                    });

                    selectedItems[key] = {
                        key: key,
                        itemId: item.item_id,
                        itemVariantId: item.item_variant_id || null,
                        name: item.name,
                        baseName: baseName,
                        variantName: variantName,
                        catName: catName,
                        regPrice: parseFloat(item.reg_price || 0),
                        price: parseFloat(item.price || 0)
                    };
                });

                if (!activeCatId) {
                    var $first = $('.hh-cat-tab').first();
                    if ($first.length) {
                        activeCatId = $first.data('cat-id');
                    }
                }
                if (activeCatId) {
                    $('.hh-cat-tab').removeClass('active');
                    $('.hh-cat-tab[data-cat-id="' + activeCatId + '"]').addClass('active');
                    renderItemsGrid(activeCatId);
                }
                updateSelectAllControls(activeCatId);
                refreshSelectedUI();

                // Scroll form into view
                $('html, body').animate({ scrollTop: $('#hh-form').offset().top - 80 }, 300);
                $('.validation-error').hide();
                $('#hh-items-error').hide();
            },
        });
    });

    /* ═══════════════════════════════════════════
       CANCEL BUTTON — reset form
    ═══════════════════════════════════════════ */
    $('#hh-cancel-btn').on('click', function () {
        resetForm();
    });

    function resetForm() {
        $('#hh-form')[0].reset();
        $('#hh-form').attr('action', hhCreateUrl);
        $('#hh-form-title').text('Add Happy Hours');
        $('button[type=submit]').text('Save');
        $('#hh_edit_id').val('');
        selectedItems = {};
        enteredPrices = {};
        activeCatId   = null;
        $('.hh-cat-tab').removeClass('active');
        $('#hh-items-grid').html('<div class="hh-empty-cat">Select a category above</div>');
        $('#hh-select-all-cat, #hh-modal-select-all-cat').prop('checked', false).prop('indeterminate', false);
        $('#hh-select-all-global, #hh-modal-select-all-global').prop('checked', false).prop('indeterminate', false);
        $('#hh_all_days').prop('checked', false);
        $('.hh-order-type-check').prop('checked', false);
        $('.hh-days-checkbox').prop('checked', false);
        $('.hh-from-field').val('');
        $('.hh-to-field').val('');
        $('#hh_schedule_from_time').val('');
        $('#hh_schedule_to_time').val('');
        refreshSelectedUI();
        $('#hh_table').find('tr').each(function () {
            this.style.removeProperty('background-color');
        });
        $('.validation-error').hide();
        $('#hh-items-error').hide();

        var $firstTab = $('.hh-cat-tab').first();
        if ($firstTab.length) {
            $firstTab.trigger('click');
        }
    }

    /* ═══════════════════════════════════════════
       FORM SUBMISSION VALIDATION
    ═══════════════════════════════════════════ */
    $('#hh-form').on('submit', function (e) {
        // 1. Validate Name
        var name = ($('#hh_name').val() || '').trim();
        if (!name) {
            e.preventDefault();
            $('#hh_name').focus();
            if (typeof toastr !== 'undefined') {
                toastr.error('Please enter a Happy Hour name.');
            } else {
                alert('Please enter a Happy Hour name.');
            }
            return false;
        }

        // 2. Validate Order Types (Mandatory)
        var checkedOrderTypes = $('.hh-order-type-check:checked');
        if (checkedOrderTypes.length === 0) {
            e.preventDefault();
            if (typeof toastr !== 'undefined') {
                toastr.error('Please select at least one Order Type.');
            } else {
                alert('Please select at least one Order Type.');
            }
            $('html, body').animate({ scrollTop: $('#hh-form').find('.hh-order-type-check').first().offset().top - 100 }, 200);
            return false;
        }

        // 3. Validate Schedule Days
        var checkedDays = $('.hh-days-checkbox:checked');
        if (checkedDays.length === 0) {
            e.preventDefault();
            if (typeof toastr !== 'undefined') {
                toastr.error('Please select at least one day in the Schedule.');
            } else {
                alert('Please select at least one day in the Schedule.');
            }
            $('html, body').animate({ scrollTop: $('#hh-form').find('.hh-days-checkbox').first().offset().top - 100 }, 200);
            return false;
        }

        // 3. Validate Schedule Times for selected days
        var timeMissing = false;
        checkedDays.each(function () {
            var day = $(this).val();
            var from = $('#hh_' + day + '_from_time').val();
            var to   = $('#hh_' + day + '_to_time').val();
            if (!from || !to) {
                timeMissing = true;
            }
        });
        if (timeMissing) {
            e.preventDefault();
            if (typeof toastr !== 'undefined') {
                toastr.error('Please provide both From and To time for all selected days.');
            } else {
                alert('Please provide both From and To time for all selected days.');
            }
            return false;
        }

        // 4. Validate Items (Items Required)
        var selectedCount = Object.keys(selectedItems).length;
        if (selectedCount === 0) {
            e.preventDefault();
            $('#hh-items-error').text('Please select at least one item.').show();
            if (typeof toastr !== 'undefined') {
                toastr.error('Please select at least one item.');
            } else {
                alert('Please select at least one item.');
            }
            $('html, body').animate({ scrollTop: $('.hh-item-picker').offset().top - 100 }, 200);
            return false;
        } else {
            $('#hh-items-error').hide();
        }

        // 5. Validate Item Prices
        var invalidItems = [];
        Object.values(selectedItems).forEach(function (item) {
            var regPrice = parseFloat(item.regPrice || 0);
            var price = item.price;
            if (price === '' || price === null || price === undefined || isNaN(price)) {
                invalidItems.push(item.name + ': Price cannot be empty');
            } else {
                var p = parseFloat(price);
                if (p < 0) {
                    invalidItems.push(item.name + ': Price cannot be negative');
                } else if (regPrice > 0 && p >= regPrice) {
                    invalidItems.push(item.name + ': Happy Hour price ($' + p.toFixed(2) + ') must be lower than original price ($' + regPrice.toFixed(2) + ')');
                }
            }
        });

        if (invalidItems.length > 0) {
            e.preventDefault();
            var msg = 'Please fix invalid Happy Hour prices:\n\n' + invalidItems.slice(0, 5).join('\n');
            if (invalidItems.length > 5) {
                msg += '\n...and ' + (invalidItems.length - 5) + ' more item(s).';
            }
            if (typeof toastr !== 'undefined') {
                toastr.error('Happy Hour price must be lower than original price.');
            } else {
                alert(msg);
            }
            return false;
        }

        // 6. Validate Overlapping Happy Hours for selected items
        var scheduleObj = {};
        checkedDays.each(function () {
            var day = $(this).val();
            scheduleObj[day] = {
                from: $('#hh_' + day + '_from_time').val(),
                to:   $('#hh_' + day + '_to_time').val()
            };
        });
        var selectedOrderTypes = [];
        $('.hh-order-type-check:checked').each(function () {
            selectedOrderTypes.push(String($(this).val()));
        });
        var currentEditId = $('#hh_edit_id').val();
        var overlapConflicts = checkOverlappingHappyHours(scheduleObj, selectedItems, currentEditId, selectedOrderTypes);

        if (overlapConflicts.length > 0) {
            e.preventDefault();
            var overlapMsg = 'Cannot save Happy Hour. The following item(s) have overlapping timing with existing Happy Hours:\n\n' + overlapConflicts.slice(0, 5).join('\n');
            if (overlapConflicts.length > 5) {
                overlapMsg += '\n...and ' + (overlapConflicts.length - 5) + ' more conflict(s).';
            }
            $('#hh-items-error').text('Selected item(s) have overlapping timing with an existing Happy Hour schedule.').show();
            if (typeof toastr !== 'undefined') {
                toastr.error('Selected item(s) overlap with an existing Happy Hour schedule.');
            } else {
                alert(overlapMsg);
            }
            $('html, body').animate({ scrollTop: $('#hh-items-error').offset().top - 100 }, 200);
            return false;
        }
    });

    /**
     * Check if two time ranges overlap.
     */
    function timesOverlap(fromA, toA, fromB, toB) {
        if (!fromA || !toA || !fromB || !toB) return false;
        if (fromA === toA || fromB === toB) return true;

        function toMinutes(t) {
            var parts = (t || '').split(':');
            return parseInt(parts[0], 10) * 60 + parseInt(parts[1] || 0, 10);
        }

        var startA = toMinutes(fromA), endA = toMinutes(toA);
        var startB = toMinutes(fromB), endB = toMinutes(toB);

        var intervalsA = (startA > endA) ? [[startA, 1440], [0, endA]] : [[startA, endA]];
        var intervalsB = (startB > endB) ? [[startB, 1440], [0, endB]] : [[startB, endB]];

        for (var i = 0; i < intervalsA.length; i++) {
            for (var j = 0; j < intervalsB.length; j++) {
                if (Math.max(intervalsA[i][0], intervalsB[j][0]) < Math.min(intervalsA[i][1], intervalsB[j][1])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Find item conflicts across other happy hour schedules and order types.
     */
    function checkOverlappingHappyHours(schedule, itemsMap, currentEditId, orderTypes) {
        if (typeof existingHappyHours === 'undefined' || !Array.isArray(existingHappyHours)) {
            return [];
        }

        var conflicts = [];
        var itemsList = Object.values(itemsMap);
        var currentOrderTypes = (orderTypes || []).map(String);

        existingHappyHours.forEach(function (hh) {
            if (currentEditId && hh.id == currentEditId) {
                return;
            }

            var otherOrderTypes = (hh.order_types || []).map(String);
            if (currentOrderTypes.length > 0 && otherOrderTypes.length > 0) {
                var hasSharedOrderType = currentOrderTypes.some(function (ot) {
                    return otherOrderTypes.indexOf(ot) !== -1;
                });
                if (!hasSharedOrderType) {
                    return;
                }
            }

            var otherSchedule = hh.schedule || {};
            var overlappingDays = {};

            for (var day in schedule) {
                if (otherSchedule[day]) {
                    var fromA = schedule[day].from;
                    var toA   = schedule[day].to;
                    var fromB = otherSchedule[day].from;
                    var toB   = otherSchedule[day].to;

                    if (timesOverlap(fromA, toA, fromB, toB)) {
                        overlappingDays[day] = {
                            other: (fromB || '') + ' - ' + (toB || '')
                        };
                    }
                }
            }

            var conflictDaysKeys = Object.keys(overlappingDays);
            if (conflictDaysKeys.length === 0) {
                return;
            }

            itemsList.forEach(function (item) {
                var itemId = parseInt(item.itemId);
                var varId  = item.itemVariantId ? parseInt(item.itemVariantId) : null;

                (hh.items || []).forEach(function (otherItem) {
                    var oItemId = parseInt(otherItem.id || otherItem.item_id);
                    var oVarId  = otherItem.item_variant_id ? parseInt(otherItem.item_variant_id) : null;

                    var isMatch = false;
                    if (oItemId === itemId) {
                        if (!varId && !oVarId) {
                            isMatch = true;
                        } else if (varId && oVarId && varId === oVarId) {
                            isMatch = true;
                        } else if (!varId || !oVarId) {
                            isMatch = true;
                        }
                    }

                    if (isMatch) {
                        var daySummaries = conflictDaysKeys.map(function (d) {
                            return d.charAt(0).toUpperCase() + d.slice(1) + ' (' + overlappingDays[d].other + ')';
                        }).join(', ');

                        conflicts.push('"' + item.name + '" already exists in "' + hh.name + '" on ' + daySummaries);
                    }
                });
            });
        });

        return Array.from(new Set(conflicts));
    }

    /* ═══════════════════════════════════════════
       CSRF SETUP
    ═══════════════════════════════════════════ */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });

    /* ═══════════════════════════════════════════
       INIT — restore old items or set defaults
    ═══════════════════════════════════════════ */
    var itemsArray = [];
    if (typeof oldItemsData !== 'undefined' && oldItemsData) {
        itemsArray = Array.isArray(oldItemsData) ? oldItemsData : Object.values(oldItemsData);
    }

    if (itemsArray.length > 0) {
        selectedItems = {};
        enteredPrices = {};
        itemsArray.forEach(function (item) {
            if (!item || !item.item_id) return;
            var isVar = !!item.item_variant_id;
            var key = isVar ? ('var_' + item.item_variant_id) : ('item_' + item.item_id);
            var priceVal = (item.price !== null && item.price !== undefined && item.price !== '') ? parseFloat(item.price) : '';
            if (priceVal !== '') {
                enteredPrices[key] = priceVal;
            }

            var catName = '';
            var baseName = '';
            var variantName = '';
            var regPrice = 0;

            hhCategories.forEach(function (c) {
                c.items.forEach(function (ci) {
                    if (ci.id == item.item_id) {
                        catName = c.name;
                        baseName = ci.name;
                        if (isVar && ci.variants) {
                            ci.variants.forEach(function (civ) {
                                if (civ.id == item.item_variant_id) {
                                    variantName = civ.name;
                                    regPrice = parseFloat(civ.price || 0);
                                }
                            });
                        } else {
                            regPrice = parseFloat(ci.price || 0);
                        }
                    }
                });
            });

            var displayName = baseName + (variantName ? ' (' + variantName + ')' : '');

            selectedItems[key] = {
                key: key,
                itemId: parseInt(item.item_id),
                itemVariantId: item.item_variant_id ? parseInt(item.item_variant_id) : null,
                name: displayName,
                baseName: baseName,
                variantName: variantName,
                catName: catName,
                regPrice: regPrice,
                price: priceVal
            };
        });
    }

    if (typeof oldEditId !== 'undefined' && oldEditId) {
        $('#hh_edit_id').val(oldEditId);
        $('.hh-edit-btn[data-id="' + oldEditId + '"]').closest('tr').css('background-color', '#fffbe6');
    }

    var $firstTab = $('.hh-cat-tab').first();
    if ($firstTab.length) {
        $firstTab.trigger('click');
    }
    refreshSelectedUI();

});
