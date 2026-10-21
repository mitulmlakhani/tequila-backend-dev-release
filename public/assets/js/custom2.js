
$(document).on('click', '.confirm-button', function (event) {
    var form = $(this).closest("form");
    event.preventDefault();
    swal.fire({
        title: `Are you sure you want to delete this row?`,
        text: "It will gone forever",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
    }).then((willDelete) => {
        if (willDelete.isConfirmed) {
            swal.fire({
                title: `Deleted?`,
                text: "Your file has been deleted.",
                icon: "success",
                buttons: true,
                dangerMode: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then((successDelete) => {
                form.submit();
            });
        }
    });
});



/*max 10 length*/
$(document).on('keypress', '.max15Length', function (e) {
    var l = $(this).val().length;
    if (parseInt(l) >= 15) {
        return false;
    }

});
$(document).on('keypress', '.max10Length', function (e) {
    var l = $(this).val().length;
    if (parseInt(l) >= 14) {
        return false;
    }

});

$(document).on('keypress', '.max30Length', function (e) {
    var l = $(this).val().length;
    if (parseInt(l) >= 30) {
        return false;
    }

});
$(document).on('keypress', '.onlyNumber', function (e) {
    if (event.which != 8 && e.which != 0 && e.which < 48 || e.which > 5) {
        return false;
    }

});

$(document).ready(function () {
    $('input[type="number"]').attr('min', 0);
})
$(document).on('keypress', 'input[type="number"]', function (e) {
    var key = e.which || e.charCode || e.keyCode;

    // Allow control keys (backspace, enter, tab, navigation, shortcuts)
    if (key === 8 || key === 0 || key === 13 || key === 9 || e.ctrlKey || e.metaKey) {
        return true;
    }

    // Allow decimal point if input allows decimals and does not already contain a dot
    if (key === 46 || e.key === '.') {
        var step = $(this).attr('step');
        if (step === '1' || $(this).hasClass('onlyNumber') || $(this).hasClass('onlyInteger')) {
            return false;
        }
        var val = $(this).val() || '';
        if (val.indexOf('.') === -1) {
            return true;
        }
        return false;
    }

    // Allow digits 0-9
    if (key >= 48 && key <= 57) {
        if (parseFloat($(this).val() || 0) > 100000) {
            return false;
        }
        return true;
    }

    return false;
});

$(document).on('keypress', '.numberInput', function () {

    if ($(this).val() > 100000) {
        return false
    }
    
    var $this = $(this);
    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
        ((event.which < 48 || event.which > 57) &&
            (event.which != 0 && event.which != 8))) {
        event.preventDefault();
    }

    var text = $(this).val();
    if ((event.which == 46) && (text.indexOf('.') == -1)) {
        setTimeout(function () {
            if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
            }
        }, 1);
    }

    if ((text.indexOf('.') != -1) &&
        (text.substring(text.indexOf('.')).length > 2) &&
        (event.which != 0 && event.which != 8) &&
        ($(this)[0].selectionStart >= text.length - 2)) {
        event.preventDefault();
    }

    $(this).bind("paste", function (e) {
        var text = e.originalEvent.clipboardData.getData('Text');
        if ($.isNumeric(text)) {
            if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
                e.preventDefault();
                $(this).val(text.substring(0, text.indexOf('.') + 3));
            }
        }
        else {
            e.preventDefault();
        }
    });
});

$(document).on('keyup', '.percentageInput123', function () {
    var $this = $(this);
    if ($(this).val() > 100) {
        $this.val($this.val().substring(0, 2));
        return false
    }
});

$(document).on('keypress', '.percentageInput', function () {

    var $this = $(this);
    var text = $(this).val();
    if ($(this).val() >= 10) {
        
        if (text.indexOf('.') == -1 && event.which != 46 ){
            event.preventDefault();
        }
    }

    
    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
        ((event.which < 48 || event.which > 57) &&
            (event.which != 0 && event.which != 8))) {
        event.preventDefault();
    }

    if ((event.which == 46) && (text.indexOf('.') == -1)) {
        setTimeout(function () {
            if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
            }
        }, 1);
    }

    if ((text.indexOf('.') != -1) &&
        (text.substring(text.indexOf('.')).length > 2) &&
        (event.which != 0 && event.which != 8) &&
        ($(this)[0].selectionStart >= text.length - 2)) {
        event.preventDefault();
    }

    $(this).bind("paste", function (e) {
        var text = e.originalEvent.clipboardData.getData('Text');
        if ($.isNumeric(text)) {
            if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
                e.preventDefault();
                $(this).val(text.substring(0, text.indexOf('.') + 3));
            }
        }
        else {
            e.preventDefault();
        }
    });
});

    

$(document).on("submit", "#floor-add-modal,#table-add-modal,#category-add-modal,#modifier-add-modal,#ingredient-add-modal,#variant-add-modal,#role-add-modal,#user-add-modal,#item_add", function () {
    $('button[type=submit]').prop('disabled', true);
});

$(document).on("click", "#deleteFloorBtn,#deleteTableBtn,#deleteCategoryBtn,#deleteModifierBtn,#deleteIngredientBtn,#deleteVariantBtn,#deleteRoleBtn,#deleteUserBtn,#deleteItemBtn", function () {
    $(this).addClass("disabled");
});

$(document).ready(function(){
    $('#floor-add-modal,#table-add-modal,#category-add-modal,#modifier-add-modal,#ingredient-add-modal,#variant-add-modal,#role-add-modal,#user-add-modal').modal({
        backdrop: 'static',
        keyboard: false
    })
});


function setTempData(key, value) {
    sessionStorage.setItem(key, value);
    return sessionStorage.getItem(key);
}

function getTempData(key) {
    var tempData = sessionStorage.getItem(key);
    // sessionStorage.removeItem(key);
    return tempData;
}

window.setTimeout(function () {
    $(".response-msg")
        .fadeTo(3000, 0.4)
        .slideUp(3000, function () {
            $(this).remove();
        });
}, 1500);


// document.querySelectorAll('.color-swatch').forEach(function(swatch) {
//     swatch.addEventListener('click', function() {
//         var color = this.getAttribute('data-color');
//         document.getElementById('color').value = color;
//     });
// });

document.querySelectorAll('.color-swatch').forEach(function(swatch) {
    swatch.addEventListener('click', function() {
        var color = this.getAttribute('data-color');
        var parentContainer = this.closest('.color-swatches').parentElement;
        var colorInput = parentContainer.querySelector('input[type="color"]');
        colorInput.value = color;
    });
});