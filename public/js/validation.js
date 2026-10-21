jQuery.validator.addMethod(
    "emailExt",
    function (value, element, param) {
        return value.match(
            /^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z\.\-]+\.[a-zA-Z]{2,}$/
        );
    },
    "Looks like you forgot something"
);
jQuery.validator.addMethod(
    "phoneCA",
    function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return (
            this.optional(element) ||
            (phone_number.length > 9 &&
                phone_number.match(
                    /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/
                ))
        );
    },
    "Please specify a valid phone number"
);

$("input[name='mobile']").keyup(function () {
    $(this).val(
        $(this)
            .val()
            .replace(/^(\d{3})(\d{3})(\d)+$/, "$1-$2-$3")
    );
});

$.validator.addMethod("numericdigit", function (value, element, regexp) {
    return this.optional(element) || regexp.test(value);
});

$.validator.addMethod(
    "decimal",
    function (value, element) {
        return (
            this.optional(element) ||
            /^((\d+(\\.\d{0,2})?)|((\d*(\.\d{1,2}))))$/.test(value)
        );
    },
    "Please enter a correct number, format 0.00"
);

/**
 * Validation to staff and customer.
 *
 */
$("form[name='roleActionForm']").validate({
    // Specify validation rules
    rules: {
        // The key name on the left side is the name attribute
        // of an input field. Validation rules are defined
        // on the right side
        name: "required",
        status: "required",
    },
    // Specify validation error messages
    messages: {
        name: "Please enter the role name.",
        status: "Please select the status.",
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function (form) {
        form.submit();
    },
});
/**
 * Validation to form and customer.
 *
 */
$("form[name='actionForm']").validate({
    // Specify validation rules
    rules: {
        // The key name on the left side is the name attribute
        // of an input field. Validation rules are defined
        // on the right side
        start_date: "required",
        end_date: "required",
        tax_name: "required",
        tax_session_id: "required",
        tax_master_id: "required",
        sub_tax_name: "required",
        category_id: "required",
        date: "required",
        time: "required",
        party: "required",
        table: "required",
        confirm: "required",
        name: "required",
        // message: "required",
        email: {
            required: true,
            email: true,
            emailExt: true,
        },
        mobile: {
            required: true,
            phoneCA: true,
            minlength: 14,
            maxlength: 14,
        },
        tax_percent: {
            required: true,
            decimal: true,
            // numericdigit: true,
            minlength: 0,
            maxlength: 5,
        },
        expense_type: {
            required: true,
            minlength: 2,
            maxlength: 100,
        },
        expenses_type_id: "required",
        expense_category: {
            required: true,
            minlength: 2,
            maxlength: 100,
        },
        expense_amount: {
            required: true,
            decimal: true,
            minlength: 1,
            maxlength: 10,
        },
        expense_description: "required",
        status: "required",
    },
    // Specify validation error messages
    messages: {
        // message: "Please enter your message here...",
        name: "Please enter your full name.",
        confirm: "Please confirm are you agree?",
        table: "Please choose your table number with the party size.",
        party: "Please enter the party zise.",
        date: "Please select your booking date.",
        time: "Please select your booking time.",
        start_date: "Please select the start date.",
        end_date: "Please select the End date.",
        tax_name: "Please enter the tax code name.",
        tax_session_id: "Please select the session Year.",
        tax_master_id: "Please select the tax class name.",
        sub_tax_name: "Please enter the sub tax class name.",
        category_id: "Please select the item category name.",
        email: {
            required: "Please enter the email id.",
            email: "Please enter a valid email address.",
            emailExt: "Invalid domain name.",
        },
        mobile: {
            required: "Phone number is required.",
            phoneCA: "Please enter a valid phone number.",
            minlength: "Phone number should be 10 digit.",
            maxlength: "Phone number should be 10 digit.",
        },
        tax_percent: {
            required: "Please enter the tax percent.",
            // numericdigit: "Please enter the numerical value percentage.",
            minlength: "Please enter a value greater than or equal to 0.",
            maxlength: "Please enter a value less than or equal to 99.",
        },
        expense_type: {
            required: "Please enter the expense type name.",
            minlength: "Please enter a value greater than or equal to 2.",
            maxlength: "Please enter a value less than or equal to 100.",
        },
        expenses_type_id: "Please select the expenses type.",
        expense_category: {
            required: "Please enter the expense name.",
            minlength: "Please enter a value greater than or equal to 2.",
            maxlength: "Please enter a value less than or equal to 100.",
        },
        expense_amount: {
            required: "Please enter the expense amount.",
            minlength: "Please enter a value greater than or equal to 1.",
            maxlength: "Please enter a value less than or equal to 10.",
        },
        expense_description: "Please enter the expense description.",
        status: "Please select the status.",
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function (form) {
        form.submit();
    },
});

/**
 * Validation to Add session.
 *
 */
$("form[name='actionFormOne']").validate({
    // Specify validation rules
    rules: {
        // The key name on the left side is the name attribute
        // of an input field. Validation rules are defined
        // on the right side
        start_date: "required",
        end_date: "required",
        status: "required",
    },
    // Specify validation error messages
    messages: {
        start_date: "Please select the start date.",
        end_date: "Please select the End date.",
        status: "Please select the status.",
    },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function (form) {
        form.submit();
    },
});

$("#tax_percent,#expense_amount").bind("change", function () {
    var value = $(this).val();
    if (isNaN(parseFloat(value))) {
        $(this).val("");
        return false;
    } else {
        $(this).val(parseFloat(value).toFixed(2));
    }
});
