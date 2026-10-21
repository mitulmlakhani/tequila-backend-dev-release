<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<body style="width:100%; display: flex; align-items: center; justify-content: center; zoom: 2;"> 

    <div style="height: 80vh;">
        @php 
            $html = view('restaurant.Report.pdf.employee_summary', compact('user', 'report'))->render();
        @endphp

        <div style="width:100%;">
            <iframe onload="resizeIframe(this);" srcdoc="{{ $html }}" style="width:100%; height:100%; border:none;"></iframe>
        </div>

    </div>

</body>


<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<script>
function resizeIframe(iframe) {
    try {
        iframe.style.height = (iframe.contentWindow.document.body.scrollHeight + 50) + 'px';
        iframe.style.width = iframe.contentWindow.document.body.scrollWidth + 'px';
    } catch(e) {}
}
</script>


</html>