$(document).ready(function () {

    // === 1. PASSCODE CHECK ===
    function checkPasscode() {
        const passcode = $('#passcode').val();
        const userId = $('#formMethod').val() === 'PATCH' ? $('#user-form').data('user-id') : null;

        if (!passcode.trim()) return;

        $.post('/restaurant/check-passcode', {
            passcode,
            user_id: userId,
            _token: $('input[name="_token"]').val()
        }, function (res) {
            if (!res.isUnique) {
                $('#passcode').addClass('is-invalid');
                $('#passcodeError').text(`Already used by ${res.user.name}`);
            } else {
                $('#passcode').removeClass('is-invalid');
                $('#passcodeError').text('');
            }
        });
    }

    $('#passcode').on('blur', checkPasscode);

    // === 2. SHOW LOGIN NUMBER ===
    $(document).on('click', '.passcode', function () {
        const $this = $(this);
        const real = $this.data('passcode');
        $this.text(real);
        setTimeout(() => $this.text('****'), 2000);
    });

    // === 3. EDIT USER ===
    $(document).on('click', '#user-edit', function () {
        const userId = $(this).data('id');

        $("#users-list").find('tr').each(function (i, tr) {
            this.style.removeProperty('background-color');
        });

        var usersRow = $(this).closest('tr');

        $.get(`/restaurant/users/${userId}`, function (res) {
            if (res.status === 'success') {

                usersRow.css('background-color', '#ffe');

                const user = res.data;
                const roles = res.roles;
                const selectedRoleIds = res.userRoles;
                const reportNotificationSchedules = res.reportNotificationSchedules;

                // Inside $('#user-edit') click callback
                const $roleSelect = $('#role').empty().append(`<option value="">Select Role</option>`);

                roles.forEach(r => {
                    const selected = selectedRoleIds.includes(r.id) ? "selected" : "";
                    $roleSelect.append(`<option value="${r.id}" ${selected}>${r.name}</option>`);
                });
                $roleSelect.focus();
                $("#payroll_user_name").text(user.name);
                $('#formTitle').text('Edit User');
                $('#user-form').attr('action', `/restaurant/users/${userId}/update`);
                $('#formMethod').val('PATCH');
                $('#user-form').data('user-id', userId);

                if (!$('input[name="_method"]').length) {
                    $('#user-form').append(`<input type="hidden" name="_method" value="PATCH">`);
                }

                $('#name').val(user.name);
                $('#email').val(user.email);
                $('#password').val('');
                $('#mobile').val(user.mobile);
                $('#passcode').val(user.passcode);
                $('#status').val(user.status);
                $("#card-swipe-input").val(user.card_id);
                $("#card_id").val(user.card_id);
                $('#ssn_number').val(user.ssn_number);
                $('#report_notification_email').val(user.report_notification_email);

                $('#join_date').val(user.join_date);
                $('#terminate_date').val(user.terminate_date);

                $("#imagePreview").attr("src", user.image_url);
                $('.btn-primary').text('Update');

                (reportNotificationSchedules || []).map(function (notificationSchedule) {
                    $("#" + notificationSchedule.report + "_report").prop("checked", true);
                    $("#" + notificationSchedule.report + "_frequency").val(notificationSchedule.schedule);
                });

                $('html, body').animate({
                    scrollTop: $("#user-form").offset().top - 100
                }, 300);
            }
        });
    });

    // Payroll
    function openPayrollModal(id) {
        let url = getPayrollUrl.replace(":id", id);
        let saveUrl = savePayrollUrl.replace(":id", id);
        let userPayrollForm = $("#user-payroll-form");

        userPayrollForm.prop("action", saveUrl);

        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                if (response.status == "success") {
                    let payrollData = response.data;
                    let payrollFields = $("#payroll_fields");

                    let formContent = (payrollData.roles || []).map(
                        function (role) {
                            return `
                                <div class="row">
                                    <div class="mb-3 col-2 d-flex align-items-center">
                                            <label class="form-label fw-bold" for="name">${
                                                role.name
                                            } Role</label>
                                    </div>

                                    <div class="mb-3 col">
                                            <label class="form-label" for="name">Payroll Amount</label>
                                        <input oninput="validateInput(this)" type="text" placeholder="Payroll Amount" name="roles[${
                                            role.id
                                        }][payroll_amount]"
                                            class="form-control" required value="${
                                                payrollData?.payrolls[role.id]
                                                    ?.payroll_amount || ""
                                            }">
                                    </div>

                                                <div class="mb-3 col">
                                    <label class="form-label" for="name">OverTime Amount</label>
                                        <input oninput="validateInput(this)" type="text" placeholder="OverTime Amount" name="roles[${
                                            role.id
                                        }][overtime_amount]"
                                            class="form-control" required value="${
                                                payrollData?.payrolls[role.id]
                                                    ?.overtime_amount || ""
                                            }">
                                    </div>

                                                                    <div class="mb-3 col">
                                    <label class="form-label" for="name">OverTime Hours After</label>
                                        <input oninput="validateInput(this)" type="text" placeholder="OverTime Hours After" name="roles[${
                                            role.id
                                        }][overtime_hours_after]"
                                            class="form-control" required value="${
                                                payrollData?.payrolls[role.id]
                                                    ?.overtime_hours_after || ""
                                            }">
                                    </div>
                                </div>
                                <hr />
                            `;
                        }
                    );

                    payrollFields.html(formContent);

                    $("#payroll_user_name").text(response.data.user.name);

                    $("#user-payroll-modal").modal("show");
                }
            },
        });
    }

    $(document).on('click', '#user-payroll', function(e) {
        let id = $(this).data('id');

        openPayrollModal(id);
    });

    // === 5. DELETE USER ===
    $(document).on('click', '#deleteUser', function () {
        const url = $(this).data('url');
        $('#deleteUserBtn').attr('href', url);
    });

    // === 6. HANDLE USER FORM SUBMIT ===
    $(document).on('submit', '#user-form', function (e) {
        e.preventDefault();

        const form = $(this);
        const formData = new FormData(this);
        const actionUrl = form.attr('action');

        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.success) {
                    showAlert('success', res.message);
                    if (res.userId) {
                        sessionStorage.setItem(
                            'showPayrollForRoleId',
                            res.userId
                        );
                    }
                    location.reload();
                } else {
                    showAlert('error', res.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let msg = Object.values(errors).flat().join('\n');
                    alert(msg); // You can enhance this with inline error rendering
                } else {
                    alert('An unexpected error occurred');
                }
            }
        });
    });

    $(document).on('click', '#resetUserForm', function () {
        $('#formTitle').text('Add User');
        $('#user-form').attr('action', userCreateUrl);
        $('#formMethod').val('POST');
        $('#method-override').val('POST');
        $('#user-form').removeData('user-id');

        $('#role').val('');
        $('#name').val('');
        $('#email').val('');
        $('#password').val('');
        $('#mobile').val('');
        $('#passcode').val('');
        $('#status').val('1');

        $('.btn-primary').text('Add');
        $('.invalid-feedback').text('');
        $('#passcode').removeClass('is-invalid');
    });
    
    // if(sessionStorage.getItem("showPayrollForRoleId")) {
    //     let userId = sessionStorage.getItem("showPayrollForRoleId");

    //     if (userId) {
    //         openPayrollModal(userId);
    //     }
    //     sessionStorage.removeItem("showPayrollForRoleId");
    // } 
});
