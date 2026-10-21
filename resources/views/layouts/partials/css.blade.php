<!-- Bootstrap CSS -->
{{-- <link href="{{ asset('assets/css/responsive.dataTables.min.css') }}" rel="stylesheet"> --}}
{{-- <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet"> --}}
<link href="{{ asset('assets/css/plugins/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/plugins/fixedColumns.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/plugins/buttons.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/plugins/select.dataTables.min.css') }}" rel="stylesheet">
{{-- <link href="https://editor.datatables.net/extensions/Editor/css/editor.dataTables.min.css" rel="stylesheet"> --}}
<link href="{{ asset('datatable-plugin/css/editor.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('datatable-plugin/css/editor.dataTables.min.css') }}" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Eagle+Lake&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/plugins/boxicons.min.css') }}">
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/media.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/bootstrap-select.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/loader_images.css') }}" rel="stylesheet" />
<!-- sweet alert css -->
<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{asset('assets/toastr/toastr.min.css') }}">
  <!-- Summernote CSS -->
<link href="{{asset('editor/summernote-lite.min.css') }}  " rel="stylesheet">
<link href="{{ asset('assets/css/plugins/select2.min.css') }}" rel="stylesheet" />

<link href="{{ asset('assets/css/plugins/simple-keyboard.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/css/keyboard.css') }}" rel="stylesheet" />

<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    .modal-backdrop{
      width: 100% !important;
      height: 100% !important;
    }
    
    .modal-fullscreen{
      width: 100% !important;
    }

    div.DTE_Field div.DTE_Field_Error {
      color: red;
    }

    div.DTE_Inline div.DTE_Inline_Field div.DTE_Field input[type=text] {
      padding: 0px 0px;
      border: 0px solid #fff;
    }

    div.DTE_Inline div.DTE_Inline_Field div.DTE_Field select {
      /* padding: 4px 5px; */
      padding: 0px 0px;
      border: 0px solid #fff;
    }

    /* div.DTE_Inline div.DTE_Inline_Field div.DTE_Field input[type=text]:focus{
      border-color: #ced4da;;
    } */

    div.DTE_Inline div.DTE_Field input.form-control{
      height: auto;
    }

    .DTED_Lightbox_Wrapper{
      /* position: fixed;
      z-index: 9;
      top: 40% !important;
      background-color: white;
      border: 1px solid;
      padding: 10px;
      left: calc(50% - 200px);
      width: 400px; */
    }
/* ----30-05-2024--- */
.cstm-up-down{
  margin-left:25px;
  transform: rotate(0deg) !important;
}
.cstm-up-down.rotate{
  margin-left:25px;
  transform:rotate(180deg) !important;
}
.cstm-sub-menu{
  display: none;
}
.cstm-sub-menu.active{
  border-left: 1px solid #fff;
}
.cstm-sub-menu.open{
  display:block
}
.cstm-sub-menu li.active{
  border-left:1px solid white;
}


</style>
