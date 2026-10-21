$(function () {
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if (month < 10) month = "0" + month.toString();
    if (day < 10) day = "0" + day.toString();
    var maxDate = year + "-" + month + "-" + day;
    $("#date").attr("min", maxDate);
});


$("#summernote").summernote({
    placeholder: "Hello Tequilas",
    tabsize: 2,
    height: 200,
});

// $("#generatWeekDays").on("click", function() {
//     $mydays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
//     $week = '';
//     $week += '<li>' + $mydays[$i] + '</li>';
//     for($i = 0; $i < 6; $i++) {

//        if($i) {
//           week += '<li>' + $mydays[$i] + '</li>';
//        } else {
//         week = '<li class="active">' + $mydays[$i]+ '</li>';
//        }
//     }
// });

$("#giftcard").on("change", function () {
    if (this.checked) {
        // Do something when the checkbox is checked
        $("#gift_card_number").attr("disabled", false);
    } else {
        // Do something when the checkbox is unchecked
        $("#gift_card_number").attr("disabled", true).val("");
    }
});

// alert(base_url);
// alert(pathArray[1]);
// if(pathArray[1] == 'reservation') {
//     alert(pathArray[1])
//     $(wondow).on("load", function() {
//         alert(pathArray)
//         $("#getBookingList").trigger( "click", ['booking_list']);
//     });
// }

//fetch data to reservation booing details from storage


// form reset
$("#button_reset").on("click", function () {
    // $('#actionForm')[0].reset();
    $("#gift_card_number").attr("disabled", true).val("");
    $("#giftcard").attr("checked", false);
    $("#submit_button").html("Booking");
    $(".btnLoaderName").html("Booking");
    $("#heading-form").html("Reservation");
    $("#actionForm").attr("action", base_url + "/" + pathArray[1]);
    $("#patch").html("");
});
/**
 * Changing the booking status of customers.
 */
$(document).on("click", ".flexCheckDefault", function () {
    // alert($(this).attr('id'));
    if ($(this).is(":checked")) {
        if (!confirm("Set reservation status to 'Arrived'?")) {
            return false;
        }
    }

    $recordId = $(this).attr("id");
    $party_confirm = $(this).val();
    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/reservationChangeStatus";
    var data =
        "actionType=status&recordId=" +
        $recordId +
        "&party_confirm=" +
        $party_confirm;
    fetch_action($uri, data, "post", function (data) {
        console.log(data);
        if (data.status == 200) {
            $rdID = $recordId.split("!");
            msg_alert("", data.msg, "success");
            $(":checkbox").prop("checked", false).removeAttr("checked");
            $("tr")
                .find(".status" + $rdID[1])
                .html("Arrived");
            $("tr")
                .find(".flexCheckDefault_" + $rdID[1])
                .attr("disabled", true)
                .val(data.party_confirm);

            $("#loading").removeClass("showImg").addClass("hideImg");
            return false;
        } else if (data.status == 404) {
            msg_alert("", data.msg, "error");
            $("#loading").removeClass("showImg").addClass("hideImg");
            return false;
        }
    });
});
//Get booking details of customers as per their booking date.
$("#search_booking").on("change", function (e, dateTime, actions) {
    if (actions === undefined) {
        $recordId = $(this).val();
    } else {
        $recordId = "";
        if (dateTime == $(this).val()) {
            return false;
        }
    }

    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/" + pathArray[1];
    var data = "actionType=getBooking&booking_date=" + $recordId;
    fetch_action($uri, data, "get", function (data) {
        console.log(data);
        $("#weekName").text(data.week_name);
        $("#search_booking").val(data.week_date);
        $("#content_booking_list").html(data.BookingList);
        $("#loading").removeClass("showImg").addClass("hideImg");
    });
});

//fetch data to payment reservation booing details from storage
$(document).off("click").on("click", ".payPayment", function (event, action) {
    // alert(action);
    //return false;
    if(action === undefined) {
        $recordId = $(this).attr("id");
    } else {
        $recordId = action;
    }
    
    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/" + pathArray[1] + "/" + $recordId + "/edit";
    var data = "actionType=payDetails&recordId=" + $recordId;
    fetch_action($uri, data, "get", function (data) {
        $("#ticketDetails").html(data.ticketDetails);
        // if($("#pay_details_id").val()) {
            paymentDetails($("#pay_details_id").val());
        // }
        
        $("#loading").removeClass("showImg").addClass("hideImg");
        return false;
        // if (data.status == 200) {
        //     data = data[0];
        //     $("#payment_status").text(data.party + " guests (Payment Pending)");
        //     $("#payment_location").text("Null");
        //     $("#payment_party").text(data.party);
        //     $("#payment_name").text(data.name);
        //     $("#payment_mobile").text(data.mobile);
        //     $("#payment_msg").text(data.message);
        //     $("#payment_table").text(data.tableNumber);
        //     var date = new Date(data.date + " " + data.time);
        //     var newDate = date.toString("d, M Y h:i A");
        //     newDate = newDate.split("G")[0];
        //     $("#payment_time").text(newDate);
        //     $("#total_pay_amt").val(10.5);
        //     $("#totalAmount").text(10.5);
        //     $("#pay_type").val("");

        //     $("#pay_details_id").val(data.rcId + "!" + data.rId);
        //     paymentDetails(data.rcId + "!" + data.rId);
        //     $("#pay_deposit").attr("disabled", false);
        //     $("#cancel_button").attr("disabled", false);
        //     msg_alert("", "Edit successfully.", "success");
        //     $("#loading").removeClass("showImg").addClass("hideImg");
        // } else if (data.status == 404) {
        //     msg_alert("", data.error, "error");
        //     return false;
        // } else {
        //     msg_alert(
        //         "",
        //         "Something went wrong. Please contact Tequilas support",
        //         "error"
        //     );
        //     return false;
        // }
    });
});

$(document).on("change", "#pay_type, #total_pay_amt", function () {
    if ($("#total_pay_amt").val() != "") {
        $("#total_pay_amt").css("border", "1px solid #ced4da");
    }
    if ($("#pay_type").val() != "") {
        $("#pay_type").css("border", "1px solid #ced4da");
    }
    if ($("#total_pay_amt").val() != "" || $("#pay_type").val() != "") {
        return false;
    }
});
/**
 * pay deposit.


//Get payment details of customers as per their booking.
function paymentDetails($customer_reservation_id) {
    $uri = base_url + "/payDeposit";
    var data =
        "actionType=pay_details&customer_book_id=" + $customer_reservation_id;
    fetch_action($uri, data, "post", function (data) {
        // console.log(data);
        if (data.payAvailable > 0) {
            $(".refaund_cancel").html(
                '<button type="button" class="btn btn-danger refund_button" name="refund_button" id="refund_button" style="height: 40px; float: right;">Payment Refund</button>'
            );
        } else {
            $(".refaund_cancel").html(
                '<button type="button" class="btn btn-danger cancel_button" name="cancel_button" id="cancel_button" style="height: 40px; float: right;">Cancel</button>'
            );
        }
        $("#total_pay_amt").val("");
        $("#pay_type").val("");
        $("#payCustomerDetails").html(data.PaymentList);
    });
}

/**
 * pay deposit.
 */
$(document).on("click", ".cancel_button123", function () {
    $total_pay_amt = $("#total_pay_amt").val();
    $pay_type = $("#pay_type").val();
    $customer_book_id = $("#pay_details_id").val();
    if ($customer_book_id) {
        if (
            !confirm("Are you sure you want to remove the reservation booking?")
        ) {
            return false;
        }
    }

    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/payDeposit";
    var data = "actionType=pay_cancel&customer_book_id=" + $customer_book_id;
    fetch_action($uri, data, "post", function (data) {
        console.log(data);        
        if (data.status == 200) {
            $(".payPayment").trigger("click", [$customer_book_id]);
            $("#loading").removeClass("showImg").addClass("hideImg");
            msg_alert("", data.msg, "success");
        } else if (data.status == 404) {
            msg_alert("", data.msg, "error");
            $("#loading").removeClass("showImg").addClass("hideImg");
            return false;
        }
    });
}); 

//Get booking details of customers as per their booking date.
$(".restorant_booking_filter").on("click", function (e, actions) {
    if (actions === undefined) {
        $recordId = $(this).attr('id');
    } else {
        $recordId = actions;       
    }

    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/" + pathArray[1] + "/create";
    var data = "actionType=bookingFilter&action=" + $recordId;
    fetch_action($uri, data, "get", function (data) {
        console.log(data);
        $("#restaurantBooking").html(data.BookingList);
        $("#loading").removeClass("showImg").addClass("hideImg");
    });
});

/**
 * Amount refaund.
 */
$(document).on("click", "#refund_button", function (e, actions) {
    $customer_book_id = $("#pay_details_id").val();

    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/paymentRefund";
    var data = "actionType=refund&customer_book_id=" + $customer_book_id;
    fetch_action($uri, data, "post", function (data) {
        console.log(data);        
        if (data.status == 200) {
            $("#loading").removeClass("showImg").addClass("hideImg");
            msg_alert("", data.msg, "success");
        } else if (data.status == 204) {
            $("#loading").removeClass("showImg").addClass("hideImg");
            msg_alert("", data.msg, "success");
        } else if (data.status == 404) {
            msg_alert("", data.msg, "error");
            $("#loading").removeClass("showImg").addClass("hideImg");
            return false;
        }
    });
});
