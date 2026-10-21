<!--Modal Popup PayRoll start-->
<div class="modal fade" id="user-payroll-modal" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleLabel"><span id="payroll_user_name"></span> User Payrolls</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype='multipart/form-data' id="user-payroll-form">
            @csrf
            <div class="modal-body">
                <div class="row" id="payroll_fields">
                    
                </div>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!--Modal Popup PayRoll end-->