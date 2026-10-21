@php
    $pageWidth = config('services.receipt.width_mm', 68);
    $pageHeight = config('services.receipt.default_height', '5000pt');
    $marginX = config('services.receipt.margin_x_mm', 2);
    $bodyWidth = $pageWidth - $marginX * 2;
@endphp

<style>
    @page {
        size: {{ $pageWidth }}mm {{ $pageHeight }};
        margin: {{ $marginX }}mm;
    }

    @font-face {
        font-family: 'Victor Mono';
        font-style: normal;
        font-weight: 400;
        src: url('{{ public_path('fonts/Victor_Mono/static/VictorMono-SemiBold.ttf') }}') format('truetype');
    }

    body {
        width: {{ $bodyWidth }}mm;
        margin: 0;
        box-sizing: border-box;
        font-family: "Victor Mono", monospace;
        font-optical-sizing: auto;
    }

    table {
        width: 100%;
    }

    th,
    td {
        margin: 0;
        padding: 0;
        word-break: break-word;
        font-size: 10pt;
        line-height: 8pt !important;
        vertical-align: bottom;
    }

    .tab-heading {
        font-weight: 500;
    }

    .total-row td {
        border-top: 1px dotted black;
        padding-top: 2pt;
    }

    .icon-cell {
        padding-left: 8px;
        text-indent: -9px;
        white-space: normal;
    }

    .icon-cell::before {
        content: '•';
        font-size: 8pt;
        margin-right: 2pt;
        vertical-align: middle;
    }
</style>
