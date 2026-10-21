{{-- <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/dataTables.responsive.min.js') }}"></script> --}}
<script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>

<script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dataTables.select.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dataTables.rowReorder.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2.min.js') }}"></script>
{{-- <script src="https://editor.datatables.net/extensions/Editor/js/dataTables.editor.min.js"></script> --}}
<script src="{{ asset('datatable-plugin/js/dataTables.editor.min.js') }}"></script>

<!-- Summernote JS -->
<script src="{{ asset('editor/summernote-lite.min.js') }}"></script> 
<script src="{{ asset('js/horizontalNavBar.js') }}"></script>
<script src="{{ asset('assets/js/all.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<!-- Forms Validations Plugin -->
<script src="{{ asset('assets/js/plugins/jquery.validate.min.js') }}"></script>
<!-- sweet alert js -->
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/js/custom2.js') }}"></script>
<script src="{{ asset('assets/js/tata.js') }}"></script>
<!--  Notifications Plugin    -->
<script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('assets/toastr/toastr.min.js') }}"></script>
<!-- Forms Validations -->
<script src="{{ asset('js/validation.js') }}"></script>
<script src="{{ asset('js/comman.js') }}"></script>
<script src="{{ asset('js/notifications.js') }}"></script>
<script src="{{ asset('js/action.js') }}"></script>

<!-- Keyboard -->
<script src="{{ asset('assets/js/plugins/simple-keyboard.js') }}"></script>
<script src="{{ asset('assets/js/keyboard.js') }}"></script>

<!-- Selects -->
<script src="{{ asset('assets/js/selects.js') }}"></script>

<!-- Jquery UI Draggable -->
<script src="{{ asset('assets/js/jquery-ui.js') }}"></script>

@if (\Session::has('redirect') == true)
    @if (Session::has('success'))
        <script>
            var jsonMsg = <?php echo json_encode(Session::get('success')); ?>;
            $("#sweetAlert").trigger("click", [jsonMsg, 'check']);
        </script>
    @endif
    @if (Session::has('error'))
        <script>
            var jsonMsg = <?php echo json_encode(Session::get('error')); ?>;
            $("#sweetAlert").trigger("click", [jsonMsg, 'error']);
        </script>
    @endif
@else
    <script>
        function showAlert(type, message) {
            if (type == 'success') {
                tata.success('Success', message)
            } else if (type == 'error') {
                tata.error('Error', message)
            } else if (type == 'info') {
                tata.info('Message', message)
            } else {
                tata.info(type, message)
            }
        }
    </script>
@endif

<script>
    $(document).ready(function(){
  $(".cstm-list").click(function(){
    $(".cstm-sub-menu").toggleClass("open");
    $(".cstm-up-down").toggleClass("rotate");
});
});
</script>

<!-- keyboard draggable script -->

<script>
    $(document).ready(function () {
        $('#keyboardSection').draggable({
            cursor: "crosshair"
        });
    });
</script>