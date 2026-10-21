var base_url = window.location.origin;
var pathArray = window.location.pathname.split("/");


/**
 * Message sweet alert.
 */
$("#sweetAlert").on("click", function (event, message, action) {
    if (action === undefined || message === undefined) {
        return false;
    }
    switch (action) {
        case "bell":
            color = 0;
            break;
        case "cloud":
            color = 2;
            break;
        case "check":
            color = 2;
            break;
        case "error":
            color = 4;
            break;
        default:
            color = 1;
    }
    notifyAction.showNotification(color, message, action);
});

//fetch data to role
$(".editForm").on("click", function (event, actions, check) {
    if(actions === undefined) {       
        $recordId = $(this).attr("id");
    } else {        
        $recordId = actions;        
    }   

    var validator = $("#roleActionForm").validate();
    validator.resetForm();
    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/adminRoles/" + $recordId;
    var data = "actionType=edit&recordId=" + $recordId;
    fetch_action($uri, data, "post", function (data) {  
        $("#record_id").val(data.id);      
        if(check === undefined) {
            $("#add_role_name").val(data.name);
        } else {
            $("#add_role_name").val(check);
        }  
        $("#add_status").val(data.status);
        $("#submit_button").html("Update");
        $(".btnLoaderName").html("Update");
        $("#addRoleLabel").html("Update Role");
        console.log(data);
        // $("#roleActionForm").attr(
        //     "action",
        //     base_url + "/adminRoles/" + $recordId + "/update"
        // );
        $("#roleActionForm").attr(
            "action",
            base_url+"/"+pathArray[1]+"/"+$recordId
        );
        $("#patch").html('<input type="hidden" name="_method" value="PATCH">');
        $("#loading").removeClass("showImg").addClass("hideImg");
        $("#addRole").modal("show");
    });
});

//fetch data to Tax class
$(".editTaxForm").on("click", function (event, actions, check) {
   
    // return false;
    if(actions === undefined) {       
        $recordId = $(this).attr("id");
    } else {        
        $recordId = actions;        
    }   
    // return false;
    var validator = $("#actionForm").validate();
    validator.resetForm();
    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url+"/"+pathArray[1]+"/"+ $recordId + "/edit";
    var data = "actionType=edit&recordId=" + $recordId;

    fetch_action($uri, data, "get", function (data) {
        $("#tax_sessions_id").val(data.tax_sessions_id);
        $("#record_id").val(data.id);
        if(check === undefined) {
            $("#tax_name").val(data.tax_name);
        } else {
            $("#tax_name").val(check);
        }        
        $("#sessionsId").val(data.tax_sessions_id);
        $("#status").val(data.status);
        $("#show_session").html(data.session);
        $("#submit_button").html("Update");
        $(".btnLoaderName").html("Update");
        $("#viewPopBoxLabel").html("Update Tax Class");
        console.log(data);
        $("#actionForm").attr(
            "action",
            base_url+"/"+pathArray[1]+"/"+$recordId
        );
        // $("#actionForm").attr("method", "PATCH");
        $("#patch").html('<input type="hidden" name="_method" value="PATCH">');
        $("#loading").removeClass("showImg").addClass("hideImg");
        $("#viewPopBox").modal("show");
    });
});

//fetch data to Tax class
$(".editExpenseForm").on("click", function (event, actions, check) {
    if(actions === undefined) {       
        $recordId = $(this).attr("id");
    } else {        
        $recordId = actions;        
    }
    var validator = $("#actionForm").validate();
    validator.resetForm();
    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url+"/"+pathArray[1]+"/"+$recordId+"/edit";
    var data = "actionType=edit&recordId=" + $recordId;

    fetch_action($uri, data, "get", function (data) {        
        if(check === undefined) {
            $("#expense_types").val(data.expense_type);
        } else {
            $("#expense_types").val(check);
        } 
        $("#record_id").val(data.id);
        $("#status").val(data.status);
        $("#submit_button").html("Update");
        $(".btnLoaderName").html("Update");
        $("#viewPopBoxLabel").html("Update Expense Type");
        console.log(data);
        $("#actionForm").attr(
            "action",
            base_url+"/"+pathArray[1]+"/"+ $recordId
        );
        // $("#actionForm").attr("method", "PATCH");
        $("#patch").html('<input type="hidden" name="_method" value="PATCH">');
        $("#loading").removeClass("showImg").addClass("hideImg");
        $("#viewPopBox").modal("show");
    });
});

//fetch data to expenses
$(document).on("click",".editExpenseTypeForm", function () {
    $recordId = $(this).attr("id");
    var validator = $("#actionForm").validate();
    validator.resetForm();
    $("#loading").removeClass("hideImg").addClass("showImg");
   
    $uri = base_url+"/"+pathArray[1]+"/" + $recordId + "/edit";
    var data = "actionType=edit&recordId=" + $recordId;
 
    fetch_action($uri, data, "get", function (data) {
        $("#expenses_type_id").val(data.expenses_type_id);
        $("#expense_category").val(data.expense_category);
        $("#expense_amount").val(data.expense_amount);
        $("#expense_description").val(data.expense_description);
        $("#status").val(data.status);
        $("#submit_button").html("Update");
        $(".btnLoaderName").html("Update");
        $("#viewPopBoxLabel").html("Update Expenses");
        console.log(data);
        $("#actionForm").attr(
            "action",
            base_url+"/"+pathArray[1]+"/"+ $recordId
        );
        // $("#actionForm").attr("method", "PATCH");
        $("#patch").html('<input type="hidden" name="_method" value="PATCH">');
        $("#loading").removeClass("showImg").addClass("hideImg");
        $("#viewPopBox").modal("show");
    });
});
//fetch data to Tax percentage
$(".editTaxPercentage").on("click", function (event,actions,sub_tax_name) {
    if(actions === undefined) {       
        $recordId = $(this).attr("id");
    } else {        
        $recordId = actions;        
    } 
    var validator = $("#actionForm").validate();
    validator.resetForm();
    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url+"/"+pathArray[1]+"/"+pathArray[2]+"/"+ $recordId + "/edit";
    var data = "actionType=edit&recordId=" + $recordId;

    fetch_action($uri, data, "get", function (data) {
        console.log(typeof data === "object");
        if (typeof data === "object") {
            $("#tax_sessions_id").val(data.tax_sessions_id);
            $("#tax_master_id").val(data.tax_master_id);
            $("#record_id").val(data.id);            
            if(sub_tax_name === undefined) {
                $("#sub_tax_name").val(data.sub_tax_name);
            } else {
                $("#sub_tax_name").val(sub_tax_name);
            }  
            $("#tax_percent").val(data.tax_percent);
            $("#status").val(data.status);
            $("#show_session").html(data.session);
            $("#submit_button").html("Update");
            $(".btnLoaderName").html("Update");
            $("#viewPopBoxLabel").html("Update Tax Class Category");
            console.log(data);
            $("#actionForm").attr(
                "action",
                base_url+"/"+pathArray[1]+"/"+pathArray[2]+"/"+ $recordId
            );
            // $("#actionForm").attr("method", "put"); 
            $("#patch").html('<input type="hidden" name="_method" value="PATCH">');        

            $("#viewPopBox").modal("show");
            $("#loading").removeClass("showImg").addClass("hideImg");
        } else {
            $("#loading").removeClass("showImg").addClass("hideImg");
            msg_alert(
                "",
                "Something went wrong. Please contact Tequilas support. ",
                "error"
            );
            return false;
        }
        
        
    });
});

// show tax percentage
$(".editTax").on("click", function (event, actions, tax_name) {
    if(actions === undefined) {
        $recordId = $(this).attr("id");
    } else {
        $recordId = actions;
    }

    var validator = $("#actionForm").validate();
    validator.resetForm();
    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/" + pathArray[1] + "/" + $recordId + "/edit";
    var data = "actionType=edit&recordId=" + $recordId;

    fetch_action($uri, data, "get", function (data) {
        if (typeof data === "object") {
            if (data.category_id != null) {
                // Split the category_id string into an array
                var categoryArray = data.category_id.split(",");

                // Set the selected values for the category select
                $('#category_id').val(categoryArray);

                // Refresh the selectpicker to reflect changes in the UI
                $('#category_id').selectpicker('refresh');
            }

            $("#record_id").val(data.id);
            if (tax_name === undefined) {
                $("#tax_name").val(data.tax_name);
            } else {
                $("#tax_name").val(tax_name);
            }
            $("#tax_percent").val(data.tax_percent);
            $("#status").val(data.status);
            $("#submit_button").html("Update");
            $(".btnLoaderName").html("Update");
            $("#viewPopBoxLabel").html("Update Tax Code Name");
            $("#actionForm").attr(
                "action",
                base_url + "/" + pathArray[1] + "/" + $recordId
            );
            $("#patch").html('<input type="hidden" name="_method" value="PATCH">');

            // Show the modal
            $("#viewPopBox").modal("show");
            $("#loading").removeClass("showImg").addClass("hideImg");
        } else {
            $("#loading").removeClass("showImg").addClass("hideImg");
            msg_alert(
                "",
                "Something went wrong. Please contact Tequilas support.",
                "error"
            );
            return false;
        }
    });
});

/**
 * fetch data to Tax Session
 */
$(".getSessionRecord").on("click", function () {
    $recordId = $(this).attr("id");
    var validator = $("#actionFormOne").validate();
    validator.resetForm();
    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/session/" + $recordId + "/edit";
    var data = "actionType=edit&recordId=" + $recordId;

    fetch_action($uri, data, "post", function (data) {
        console.log(data);
        $("#start_date").val(data.start_date);
        $("#end_date").val(data.end_date);
        $("#status").val(data.status);
        $("#submit_button").html("Update");
        $(".btnLoaderName").html("Update");
        $("#addSessionLabel").html("Update Tax Session");
        $("#actionFormOne").attr(
            "action",
            base_url + "/session/" + $recordId + "/update"
        );
        $("#patch").css("display", "block");
        $("#loading").removeClass("showImg").addClass("hideImg");
        $("#addSession").modal("show");
    });
});

$("#addRoleAction").on("click", function () {
    $("#submit_button").html("Add");
    $(".btnLoaderName").html("Add");
    $("#addRoleLabel").html("Add Role");
    $("#roleActionForm")[0].reset();
    var validator = $("#roleActionForm").validate();
    validator.resetForm();
    $("#roleActionForm input,select").css("color", "black");
    $("#addRole").modal("show");
});

$(".showActionPop").on("click", function () {
    const $popBoxId = $(this).attr("alt");
    const $htmlcontent = $(this).html();
    $("#submit_button").html("Add");
    $(".btnLoaderName").html("Add");
    $("#viewPopBoxLabel").html($htmlcontent);
    if ($popBoxId == "addSession") {
        var validator = $("#actionFormOne").validate();
        validator.resetForm();
        $("#actionFormOne input,select").css("color", "black");
        $("#actionFormOne")[0].reset();
    } else if ($popBoxId == "viewPopBox") {
        var validator = $("#actionForm").validate();
        validator.resetForm();
        $("#actionForm input,select").css("color", "black");
        $("#actionForm")[0].reset();
    }
    $("#show_session").html($("#sessionYear").html());
    $("#" + $popBoxId).modal("show");
});

/**
 * Show the loader when click on the submit button
 */
$(".submit_button").on("click", function () {
    var form_id = $(this).closest("form").attr("id");
    if ($("#" + form_id).valid()) {
        $("#button_loader").css("display", "block");
        $("#submit_button").css("display", "none");
    } else if (!$("#" + form_id).valid()) {
        $("#button_loader").css("display", "none");
        $("#submit_button").css("display", "block");
        return false;
    }
    $("#" + form_id).submit();
});


/*
 * Get the role permission according to user roles.
 */
$("#rolePermission").on("change", function (event, role_id) {
    if(role_id === undefined) {
        var roleId = $(this).val();
    } else {
        var roleId = role_id;
    }
    
    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/super-admin/search/permission";
    var data = "actionType=getRole&roleId=" + roleId;
    // $('#loading').removeClass('hideImg').addClass('showImg');
    fetch_action($uri, data, "POST", function (Rest) {
        if (Rest == -101 || Rest == -1) {
            msg_alert(
                "",
                "Something went wrong. Please contact Tequilas support. ",
                "error"
            );
            return false;
        } else if( typeof( Rest ) === 'object' ) {
            $("#permisison_list").html(Rest.permissions);
            $("#loading").removeClass("showImg").addClass("hideImg");
        }
    });
});

/*
 * Checkbox checked and unchecked if click on all checkbox.
 * @param {pemissionId} id // Get the all checked boex Id when click on checkbox
 * @param {isChecked} true/false // check box true or false
 */
$(document).on("click", ".allPermission", function () {
    var pemissionId = $(this).attr("id").split("-");
    let isChecked = $(this).prop("checked");
    if (isChecked == true) {
        $(".permissionCheck_" + pemissionId[1]).prop("checked", true);
    } else if (isChecked == false) {
        $(".permissionCheck_" + pemissionId[1]).prop("checked", false);
    }
});

/*
 * Checkbox checked and unchecked if click on all checkbox.
 *When the button is clicked, get the checked values.
 * @param {pemissionId} id // Get the all checked boex Id when click on checkbox
 * @param {isChecked} true/false // check box true or false
 */
$("#assignPermission").on("click", function () {

    let roleId = $("#rolePermission").val();
    let updateTime = $(".assignedRolePermission").attr('id');
    $("#loading").removeClass("hideImg").addClass("showImg");
    // var pemissionId = $(this).attr('id').split('-');

    // Get all the checkboxes
    const checkboxes = $(".permission:checked");

    // Create an empty array to store the checked values
    const checkedValues = [];
    // Loop through all the checkboxes and check if they are checked
    checkboxes.each(function (index, checkbox) {
        if (checkbox.checked) {
            // If the checkbox is checked, push its value to the checkedValues array
            checkedValues.push(checkbox.value);
        }
    });
    if ((roleId && checkedValues.length > 0 && updateTime == 0) || updateTime > 0) {
        $uri = base_url + "/super-admin/permission/assign";
        var data =
            "actionType=assignPermission&roleId=" +
            roleId +
            "&permission=" + checkedValues;
        fetch_action(
            $uri,
            data,
            "POST",
            function (Rest) {
                console.log(Rest);
                if (Rest == -101) {
                    msg_alert("", "Role assign not successfully.. ", "error");
                    $("#loading").removeClass("showImg").addClass("hideImg");
                    return false;
                } else if (Rest == 200) {
                    $("#rolePermission").trigger("change", [roleId]);
                    msg_alert("", "Role assigned successfully. ", "success");
                    $("#loading").removeClass("showImg").addClass("hideImg");
                    return true;
                } else {
                    msg_alert(
                        "",
                        "Something went wrong. Please contact Tequilas support. ",
                        "error"
                    );
                    return false;
                }
            },
            "html"
        );
    } else {
        $("#loading").removeClass("showImg").addClass("hideImg");
        msg_alert("", "Please select role and permission. ", "error");
        return false;
    }
});


/*
 * active default tax.
 */
$(".defaultAction").on("click", function () {
    let recordId = $(this).attr('id');
    $recordArr = $(this).attr('id').split("#");
    
    if($recordArr[2] === '1') {
        if(!confirm("Are you sure you want to remove default tax?")) {
            return false;
        }
    } else if($recordArr[2] === '0') {
        if(!confirm("Are you sure you want to change default tax?")) {
            return false;
        }
    }

   
    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/default/tax";
    var data = "actionType=defaultTax&recordID=" + recordId;
    fetch_action($uri, data, "POST", function (Rest) {
        console.log(Rest);
       // return false;
        if (Rest != -11 && Rest < 0) {
            msg_alert(
                "",
                "Something went wrong. Please contact Tequilas support. ",
                "error"
            );
            return false;
        } else { 
            if($recordArr[2] === '0') {
                if (Rest != -11) { // if first time select the default tax then not effect this.
                    $(".activeIcon-"+Rest).attr("id", Rest+"#"+$recordArr[1]+"#"+0);
                    $(".changeImgActive_"+Rest).attr('src', base_url + "/images/pic_bulboff.gif");
                    $(".changeImgActive_"+Rest).attr("id", "active_icon_"+Rest+"#"+$recordArr[1]+"#"+0);
                    $(".changeImgActive_"+Rest).attr('title', "Undefault Tax");  
                }            
                

                $(".activeIcon-"+$recordArr[0]).attr("id", $recordArr[0]+"#"+$recordArr[1]+"#"+1);
                $(".changeImgActive_"+$recordArr[0]).attr('src', base_url + "/images/pic_bulbon.gif");
                $(".changeImgActive_"+$recordArr[0]).attr("id", "active_icon_"+$recordArr[0]+"#"+$recordArr[1]+"#"+1);
                $(".changeImgActive_"+$recordArr[0]).attr('title', "Default Tax");
                msg_alert(
                    "",
                    "Your default tax has been selected successfully.",
                    "success"
                );
            } else if($recordArr[2] === '1') {
                $(".activeIcon-"+Rest).attr("id", Rest+"#"+$recordArr[1]+"#"+0);
                $(".changeImgActive_"+Rest).attr('src', base_url + "/images/pic_bulboff.gif");
                $(".changeImgActive_"+Rest).attr("id", "active_icon_"+Rest+"#"+$recordArr[1]+"#"+0);
                $(".changeImgActive_"+Rest).attr('title', "Undefault Tax"); 
                msg_alert(
                    "",
                    "Your default tax has been removed successfully.",
                    "error"
                );
            }  
           
        }
        $("#loading").removeClass("showImg").addClass("hideImg");
    },"html");
});

/*
 * Session Active and Inactive with switch button.
 */
$(".session_button").on("click", function () {
    let recordId = $(this).attr('id');
    $recordArr = $(this).attr('id').split("#");
    
    if($recordArr[1] === '1') {
        if(!confirm("Are you sure you want to remove default session?")) {
            return false;
        }
    } else if($recordArr[1] === '0') {
        if(!confirm("Are you sure you want to change default session?")) {
            return false;
        }
    }
   
    $("#loading").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/default/session";
    var data = "actionType=defaultSession&recordID=" + recordId;
    fetch_action($uri, data, "POST", function (Rest) {
        console.log(Rest);
       // return false;
        if (Rest != -11 && Rest < 0) {
            msg_alert(
                "",
                "Something went wrong. Please contact Tequilas support. ",
                "error"
            );
            return false;
        } else { 
            if($recordArr[1] === '0') {
                if (Rest != -11) { // if first time select the default session then not effect this.
                    $(".sessionIcon-"+Rest).attr("id", Rest+"#"+0);
                    $(".changeImgActive_"+Rest).attr('src', base_url + "/images/pic_bulboff.gif");
                    $(".changeImgActive_"+Rest).attr("id", "session_icon_"+Rest+"#"+0);
                    $(".changeImgActive_"+Rest).attr('title', "Select Session"); 
                    
                    $(".statusImg_"+Rest).attr('src', base_url + "/assets/images/pending.png");
                    $(".span_"+Rest).html("Inactive");
                    $("#statusID_"+Rest).removeClass("reserved").addClass("pending");
                }            
                

                $(".sessionIcon-"+$recordArr[0]).attr("id", $recordArr[0]+"#"+1); 
                $(".changeImgActive_"+$recordArr[0]).attr('src', base_url + "/images/pic_bulbon.gif");
                $(".changeImgActive_"+$recordArr[0]).attr("id", "session_icon_"+$recordArr[0]+"#"+1);
                $(".changeImgActive_"+$recordArr[0]).attr('title', "Unselect Session");

                $(".statusImg_"+$recordArr[0]).attr('src', base_url + "/assets/images/reserved.png");
                $(".span_"+$recordArr[0]).html("Active");
                $("#statusID_"+$recordArr[0]).removeClass("pending").addClass("reserved");



                msg_alert(
                    "",
                    "Your default Session has been selected successfully.",
                    "success"
                );
            } else if($recordArr[1] === '1') {
                $(".sessionIcon-"+Rest).attr("id", Rest+"#"+0);
                $(".changeImgActive_"+Rest).attr('src', base_url + "/images/pic_bulboff.gif");
                $(".changeImgActive_"+Rest).attr("id", "session_icon_"+Rest+"#"+0);
                $(".changeImgActive_"+Rest).attr('title', "Unselect Session");
                $(".statusImg_"+Rest).attr('src', base_url + "/assets/images/pending.png");
                $(".span_"+Rest).html("Inactive");
                $("#statusID_"+Rest).removeClass("reserved").addClass("pending");

                msg_alert(
                    "",
                    "Your default session has been removed successfully.",
                    "error"
                );
            }        
            
            $("#loading").removeClass("showImg").addClass("hideImg");
           
        }
    },"html");
});


/*
 * Session Active and Inactive with switch button.
 */
$(".session_date").on("change", function () {
    let startDate = $("#start_date").val();
    let endDate =   $("#end_date").val();

    if(startDate && !endDate) {
        return false;
    }

    if(startDate > endDate)  {
        $("#end_date").val('');
        $('#submit_button').attr("disabled",'disabled');
        msg_alert("","Please enter the end date larger than the start date.","error");
        return false;
    } else {
        $('#submit_button').attr("disabled", false);
    }
    
    $("#loading2").removeClass("hideImg").addClass("showImg");
    $uri = base_url + "/session/dateCheck";
    var data = "actionType=checkDate&startDate=" + startDate+"&endDate=" + endDate;
    fetch_action($uri, data, "POST", function (Rest) {
        console.log(Rest);
        if(Rest == 1) {
            $('#submit_button').attr("disabled",'disabled');
            // $("#start_date").val('');
            $("#end_date").val('')
            msg_alert("","This session already exist. please try to another year session.","error");
            $("#loading2").removeClass("showImg").addClass("hideImg");
            return false;
        } else if(Rest == -101) {
            $('#submit_button').attr("disabled", false);
            msg_alert("","Something went wrong. Please contact Tequilas support.","error");
            $("#loading2").removeClass("showImg").addClass("hideImg");
            return false;
        } 
        $('#submit_button').attr("disabled", false);
       $("#loading2").removeClass("showImg").addClass("hideImg");
    });
});
// This code always bottom.
if(typeof isValidError !== 'undefined' && isValidError == true)  {      
    switch(pathArray[1]) {
        case 'tax':     
            var editrecordId = $("#record_id").val(); 
            var tax_name = $("#tax_name").val();  
            if(editrecordId !="") {                                
                $(".editTaxForm").trigger( "click", [editrecordId, tax_name]);
            } else {
                $(function() {
                    $("#show_session").html($("#sessionYear").html());
                    showPopBox();           
                }); 
            }            
            
            break;
        case 'percent':                 
            var subTtax_name = $("#sub_tax_name").val();             
            var editrecordId = $("#record_id").val();  
            var tax_master_id = $("#tax_master_id").val(); 
            $("#collapseExample"+tax_master_id).addClass("collapse show");
            if(editrecordId !="") {                 
                $(".editTaxPercentage").trigger( "click", [editrecordId, subTtax_name]);
            } else {                  
                $(function() {
                    $("#show_session").html($("#sessionYear").html());
                    showPopBox();           
                });
            } 
            break;
        case 'tax-manage':                 
            var tax_name = $("#tax_name").val();             
            var editrecordId = $("#record_id").val(); 
            if(editrecordId !="") {                 
                $(".editTax").trigger( "click", [editrecordId, tax_name]);
            } else {                  
                showPopBox();
            } 
            break;
        case 'expense-types':                 
            var expense_types = $("#expense_types").val();             
            var editrecordId = $("#record_id").val(); 
            if(editrecordId !="") { 
                $(".editExpenseForm").trigger( "click", [editrecordId, expense_types]);
            } else {                             
                showPopBox();
            }  
            break;
        case 'adminRoles':                 
            var add_role_name = $("#add_role_name").val();             
            var editrecordId = $("#record_id").val(); 
            if(editrecordId !="") { 
                $(".editForm").trigger( "click", [editrecordId, add_role_name]);
            } else {                             
                showPopBox('add');
            }  
        break;
        default:
            msg_alert(
                "",
                "Something went wrong. Please contact Tequilas support. ",
                "error"
            );

    }    
}

function showPopBox($action = "") {
    if($action == 'add') {
        $(function() {
            $('#addRole').modal('show');           
        });
    } else {
        $(function() {
            $('#viewPopBox').modal('show');           
        });
    }
   
}

$("#closeButton").on("click", function() {
    location.reload();
});

$(document).on("click",".collapseexample", function() {

    // var arrowUpDown = $(this).attr('id');
    // var ariaExp = $(this).attr('aria-expanded');
    var arrowCheck = $(document).find(this).hasClass("fa-arrow-up");
    if(arrowCheck == true) {
        $(this).addClass('fa-arrow-down').removeClass('fa-arrow-up');
    } else {
        $(this).addClass('fa-arrow-up').removeClass('fa-arrow-down');
       
    }
    
});

/**
 * Write the logic to tip percentage.
 */
$(document).on("blur",".tip-percentage", function() {

    var attId = $(this).attr('name');
    var vals = $(this).val();
    var $recordId = $("#record_id").val();
    var tipData = attId +"="+ vals;
    
    $("#loading").removeClass("hideImg").addClass("showImg");
    if($recordId) {        
        $method = "PATCH";
        $uri = base_url + "/" + pathArray[1]+ "/" + pathArray[2] + "/" + $recordId;
    } else {
        $method = "POST";
        $uri = base_url + "/" + pathArray[1] + "/" + pathArray[2];
    }

    var data = "actionType="+attId+"&" + tipData;
    fetch_action($uri, data, $method, function (Rest) {
        console.log(Rest);
        if(Rest.statusCode == 200 && Rest.status == "success" && !$recordId) {
            $("#record_id").val(Rest.recordId);
        }
        msg_alert("", Rest.message, Rest.status);
        $("#loading").removeClass("showImg").addClass("hideImg");       
    });
    
});

