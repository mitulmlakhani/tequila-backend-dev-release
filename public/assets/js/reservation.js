$(document).ready(function(){

    $(".action_button").on("click", function () {
        
        var tabId = $(this).find("a").attr("id");
        if (tabId == 'reservation-list') {
            fetchBookingList();
            $(".customer_booking_list").show();
            $("#main_content_view").removeClass('col-lg-12');
            $("#main_content_view").addClass('col-lg-8');
        }
        else if(tabId == "payments-list"){
            //fetchBookingList();
            $(".customer_booking_list").show();
            $("#main_content_view").removeClass('col-lg-12');
            $("#main_content_view").addClass('col-lg-8');
        }
        else if(tabId == "restaurant-booking-list"){
            reservationBookingTabList();
            $(".customer_booking_list").hide();
            $("#main_content_view").removeClass('col-lg-8');
            $("#main_content_view").addClass('col-lg-12');
        }
        else if(tabId == "setting-list"){
            $(".customer_booking_list").hide();
            $("#main_content_view").removeClass('col-lg-8');
            $("#main_content_view").addClass('col-lg-12');
        }
    });
   
    $(document).on("keyup","#mobile", function () {
        mobileNo = $(this).val();
        if (mobileNo.length == 14) {

            showLoader();
            url = getCustomerByMobileUrl;
            var data = "mobile="+mobileNo;
            $.ajax({
                url: url,
                type: 'GET',
                data: data,
                success: function(response) {
                    if (response.status == 'success') {
                        var customer = response.data.customer;
                        $("#names").val(customer.name);
                        $("#email_id").val(customer.email);
                    }
                }
            });

            hideLoader();
        }
    });

    $(document).on("submit", "#reservation_save", function (e) {
        e.preventDefault();
        var thisObj = $(this);
        console.log(thisObj.data('type'));
        var data = $(this).serialize();
        // console.log(data);
        var url = $(this).attr('action');
        showLoader();
        $.ajax({
            url: url,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.status == 'success') {
                    if(thisObj.data('type') == "edit"){
                        $(".reservation-add-form-div").show();
                        $(".reservation-form-div").hide();
                    }else{
                        fetchBookingList();
                        $("#reservation_save")[0].reset();
                        $(".reservation-add-form-div").show();
                        $(".reservation-form-div").hide();
                    }
                    $(function () {
                        $("#table").selectpicker('deselectAll');
                    });
                    showAlert('success',response.message);
                }else{
                    showAlert('error',response.message);
                }
                hideLoader();
            }
        });
        
    }); 

    $("#getBookingList").on("click", function () {
        
        var searchText = $("#search_details").val();
        var url = searchReservationUrl;
        var data = {
            'search_text':searchText
        };
        // showLoader();
        $.ajax({
            url: url,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: 'POST',
            data:data,
            success: function(response) {
                if (response.status == 'success') {
                    $(".content_left_list").html(response.data.html);
                    
                    hideLoader(); 
                }
            }
        });
    });
});


$(document).on("click", "#pay_deposit", function () {
    var amount = $("#amount").val();
    var payType = $("#pay_type").val();
    var reservationId = $("#reservation_id").val();

    if (amount == "" || payType == "") {
        showAlert('error',"Required field missing");
        return false;
    }

    showLoader();
    var url = paymentDepositUrl;
    var data = {
        'amount' : amount,
        'payType' : payType,
        'reservationId' : reservationId
    }
    console.log(data);
    $.ajax({
        url: url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'POST',
        data: data,
        success: function(response) {
            if (response.status == 'success') {
                $(".payment-details-div").html(response.data.html);
                showAlert('success',response.message);
            }
            hideLoader();
        }
    });
});


// fetch payment details tab details (HTML)
$(".customer_booking_list").on("click", ".editBooking", function () {
    showLoader();
    var reservationId = $(this).data("id");
    var url = reservationDetailUrl.replace(':id', reservationId);
    var tab = activeTab();
    var data ={ tab: tab }
    
    $.ajax({
        url: url,
        type: 'GET',
        data:data,
        success: function(response) {
            if (response.status == 'success') {
                if(tab == 'reservation-list'){
                    $(".reservation-add-form-div").hide();
                    $(".reservation-form-div").show();
                    $(".reservation-form-div").html(response.data.html);
                    $(function () {
                        $("#table").selectpicker();
                    });
                }else if(tab == 'payments-list'){
                    $(".payment-details-div").html(response.data.html);
                }
                
                hideLoader(); 
            }
        }
    });
});

$(document).ready(function(){
    if(activeTab() == 'reservation-list'){
        fetchBookingList();
    }
});

//cancel booking JS 
$(document).on("click","#cancel_booking", function () {
    if($(this).data('isPayments') > 0){
        $('#cancelBooking').modal('show');
    }else{
        var reservationId = $("#reservation_id").val();
        var data = {
            'reservationId' : reservationId,
            'refundType' : 3
        }
        cancelBooking(data);
    }

});

$(document).on("change","#refund_type", function () {
    var totalAmount = $('#cancel_booking').data('total-payment');
    if($(this).val() == 1){
        $(".partial_refund_type_div").hide();
        $("#partial_refund").attr('required',false);
        $(".refund-payment-amount").html("<b>Refunded amount $"+totalAmount+"</b>");
    }
    else if($(this).val() == 2){
        $(".partial_refund_type_div").show();
        $("#partial_refund").attr('required',true);
        $(".refund-payment-amount").html("");
    }
    else if($(this).val() == 3){
        $(".partial_refund_type_div").hide();
        $("#partial_refund").attr('required',false);
        $(".refund-payment-amount").html("<b>Refunded amount $0</b>");
    }
    else{
        $(".refund-payment-amount").html("");
    }

    $('#cancelBooking').modal('show');
});

$(document).on("change","input[name=partial_refund_type]", function () {
    if($(this).val() == 'percentage'){
        $("#partial_refund").addClass("percentageInput");
        $("#partial_refund").removeClass("numberInput");
        $(".partial_refund_type_div").find(".input-group-text").text("%");
        $("#partial_refund").attr('placeholder','Enter percentage');
    }else{
        $("#partial_refund").removeClass("percentageInput");
        $("#partial_refund").addClass("numberInput");
        $(".partial_refund_type_div").find(".input-group-text").text("$");
        $("#partial_refund").attr('placeholder','Enter amount');
    }
    $("#partial_refund").val("");

    $(".refund-payment-amount").html("");
});

$(document).on("keyup","#partial_refund", function () {
    var totalAmount = $('#cancel_booking').data('total-payment');
    var partialRefundType = $("input[name=partial_refund_type]:checked").val();
    console.log(partialRefundType);
    var partialRefund = $(this).val();
    if(partialRefundType == 'percentage'){
        var tempAmount = (totalAmount*partialRefund)/100;
        $(".refund-payment-amount").html("<b>Refunded amount $"+tempAmount.toFixed(2)+"</b>");
    }else{
        $(".refund-payment-amount").html("<b>Refunded amount $"+partialRefund+"</b>");
    }
});

$(document).on("submit", "#cancel_booking_form", function (e) {
    e.preventDefault();
    var reservationId = $("#reservation_id").val();
    if($("#refund_type").val() == 2){
        var partialRefund = $("#partial_refund").val();
        if($('input[name="partial_refund_type"]:checked').val() == 'percentage'){
            if(partialRefund < 0 || partialRefund > 100){
                showAlert('error',"Percentage should be between 0-100");
                return false;
            }
        }
    }
    var data = {
        'reservationId' : reservationId,
        'refundType' : $("#refund_type").val(),
        'partialRefundType' : $('input[name="partial_refund_type"]:checked').val(),
        'partialRefund' : $("#partial_refund").val()
    }
    cancelBooking(data);
    
}); 

function cancelBooking(data){
    showLoader();
    var url = cancelBookingUrl;
    $.ajax({
        url: url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'POST',
        data: data,
        success: function(response) {
            if (response.status == 'success') {
                $('#cancelBooking').modal('hide');
                $(".payment-details-div").html(response.data.html);
                showAlert('success',response.message);
            }else{
                showAlert('error',response.message);
            }
            hideLoader();
        }
    });
}

//end of cancel booking JS 

//restaurant booking tab start
$(document).on("click",".reservation_filter",function () {
    var filterType = $(this).data('type');
    $(".reservation_filter").removeClass('text-decoration-underline');
    $(this).addClass('text-decoration-underline');
    var url = reservationListFilterByDateUrl;
    var data = {
        'filter_type': filterType
    };
    showLoader();
    $.ajax({
        url: url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'POST',
        data: data,
        success: function(response) {
            if (response.status == 'success') {
                $("#reservation_list_div").html(response.data.html);
                // $("#floormanagement").ajax.reload();
                // var table=('#floormanagement').Datatable();
                // table.draw();
            }else{
                showAlert('error',response.message);
            }
            hideLoader();
        }
    });
});

$(document).on("click",".reservation_filter_2",function () {
    var filterType = $(this).data('type');
    $(".reservation_filter_2").removeClass('text-decoration-underline');
    $(this).addClass('text-decoration-underline');
    var url = reservationListFilterByStatusUrl;
    var data = {
        'filter_type': filterType
    };
    showLoader();
    $.ajax({
        url: url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'POST',
        data: data,
        success: function(response) {
            if (response.status == 'success') {
                $("#reservation_list_div").html(response.data.html);
                // $("#floormanagement").ajax.reload();
                // var table=('#floormanagement').Datatable();
                // table.draw();
            }else{
                showAlert('error',response.message);
            }
            hideLoader();
        }
    });
});

$(document).on('change','#change_reservation_status',function(){
    var thisObj = $(this);
    showLoader();
    var url = changeBookingStatusUrl;
    var data = {
        'reservation_id':$(this).data('reservationId'),
        'status':$(this).val()
    };
    $.ajax({
        url: url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'POST',
        data: data,
        success: function(response) {
            if (response.status == 'success') {
                if(thisObj.val() == 2){
                    thisObj.parent().text('Cancelled');
                }else if(thisObj.val()== 3){
                    thisObj.parent().text('Completed');
                }
                
                showAlert('success',response.message);
            }else{
                showAlert('error',response.message);
            }
            hideLoader();
        }
    });
});
//end of restaurant booking tab start

//restaurant setting tab start
$(document).on('change','.days_open',function(){
    var thisObj = $(this);
    showLoader();
    var url = changeDaysOpenSettingsUrl;
    var data = {
        'day':$(this).val(),
        'is_open':$(this).prop('checked')
    };
    
    $.ajax({
        url: url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'POST',
        data: data,
        success: function(response) {
            if (response.status == 'success') {                
                showAlert('success',response.message);
            }else{
                showAlert('error',response.message);
            }
            hideLoader();
        }
    });
});
//end of restaurant setting tab start

function fetchBookingList(){
    showLoader();
    var url = getBookingListUrl;
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            if (response.status == 'success') {
                $(".content_left_list").html(response.data.html);
                hideLoader(); 
            }
        }
    });
    
}

function activeTab(){
    var tabId = null;
    $('.list-group-item-action').each(function(i, obj) {
        if($(this).hasClass("active")){
            tabId = $(this).attr("id");
        }
    });

    return tabId;
}

function reservationBookingTabList(){
    var url = reservationListFilterByDateUrl;
    var data = {
        'filter_type': 'all'
    };
    showLoader();
    $.ajax({
        url: url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: 'POST',
        data: data,
        success: function(response) {
            if (response.status == 'success') {
                // var myTable = $('#floormanagement').DataTable();
                // myTable.clear().rows.add(response.data.html).draw();
                // myTable.row.add($(response.data.html)).draw();
                // $('#floormanagement').DataTable().destroy();

                $("#reservation_list_div").html(response.data.html);
                // $("#floormanagement").ajax.reload();
                //$('#floormanagement').DataTable().draw();
                // var table = $('#floormanagement').Datatable();
                // table.draw();
            }else{
                showAlert('error',response.message);
            }
            hideLoader();
        }
    });
}

//for reference only need to delete

$(".action_button123").on("click", function () {
    var tabId = $(this).find("a").attr("id");
    // alert(tabId);
    if (
        jQuery.inArray(tabId, [
            "view-booking-list",
            "restaurant-booking-list",
            "setting-list",
        ]) !== -1
    ) {
        $(".customer_booking_list").addClass("hide").removeClass("show");
        $("#main_content_view")
            .addClass("col-md-12 col-lg-12")
            .removeClass("col-md-12 col-lg-8");
        if (tabId == "view-booking-list") {
            // get current record
            var dt_Today = new Date();
            var d = dt_Today.getDate(),
                m = dt_Today.getMonth() + 1,
                y = dt_Today.getFullYear();
            if (m < 10) m = "0" + m.toString();
            if (d < 10) d = "0" + d.toString();
            currentDate = y + "-" + m + "-" + d;
            $("#search_booking").trigger("change", [currentDate, tabId]);
        }
    } else {
        $(".customer_booking_list").addClass("show").removeClass("hide");
        $("#main_content_view")
            .addClass("col-md-12 col-lg-8")
            .removeClass("col-md-12 col-lg-12");

        if (jQuery.inArray(tabId, ["reservation_addnew"]) !== -1) {
            $("#reservation-list").addClass("active");
            $("#reservation_addnew").removeClass("active");
            $("#restaurant-booking-list").removeClass("active");
        }
    }

    if (jQuery.inArray(tabId, ["template-list"]) !== -1) {
        $(".header_bllb")
            .html("Email/SMS Template List")
            .css("font-weight", "Bold");
        $(".content_left_list").html(
            ' <li class="list-group-item"> <a href="javascript:void(0)" class="textcolor"> Admin notification suject (Pending Booking) </a> (Email)</li> <li class="list-group-item"> <a href="javascript:void(0)" class="textcolor"> Admin notification Email (Pending Booking) </a> (SMS)</li>'
        );
        $(".search_button").attr("placeholder", "Search Template.");
    } else if (
        jQuery.inArray(tabId, ["reservation-list", "payments-list"]) !== -1
    ) {
        $(".header_bllb")
            .html("Reservation Booking List")
            .css("font-weight", "Bold");

        if (tabId == "payments-list") {
            $("#payment_status").text("...guests (Payment Pending)");
            $("#payment_location").text("...");
            $("#payment_party").text("...");
            $("#payment_name").text("...");
            $("#payment_mobile").text("...");
            $("#payment_msg").text("...");
            $("#payment_table").text("...");
            $("#payment_time").text("...");
            $("#total_pay_amt").val("");
            $("#totalAmount").text("...");
            $("#pay_type").val("");
            $("#pay_details_id").val("");
            $("#pay_deposit").attr("disabled", true);
            $("#cancel_button").attr("disabled", true);
            $(".refaund_cancel").html(
                '<button type="button" class="btn btn-danger cancel_button" name="cancel_button" id="cancel_button" style="height: 40px; float: right;" disabled>Cancel</button>'
            );
            $("#payCustomerDetails").html('<tr><td colspan="6"><center style="color: red;"> Reservation Payment is not available.</center></td></tr>');
            
        }
        // else if (tabId == "reservation-list") {
        //     $(".bookingListDetails")
        //         .addClass("editBooking")
        //         .removeClass("payPayment");
        // }

        $(".search_button").attr("placeholder", "Search Booking.");
        $("#getBookingList").attr("alt", tabId);
        $("#search_details").val("");
        $("#getBookingList").trigger("click", [tabId]);
    } else if (jQuery.inArray(tabId, ["restaurant-booking-list"]) !== -1) { 
        // alert();
    }
});
