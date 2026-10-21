@php
$reportNotificationEmails = \App\Models\ReportNotification::where('restaurant_id', auth()->user()->restaurant_id)
    ->pluck('email')
    ->toArray();

$formats = $formats ?? ['pdf', 'excel'];
@endphp

<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="emailReport" tabindex="-1" role="dialog"
    aria-labelledby="emailReportLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailReportLabel">Email Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="reportMailForm" action="{{ $action }}" method="get">
                    <span id="reportMailFormMsg" class="mb-3"></span>

                    @yield('additionalFields')

                    @if(count($reportNotificationEmails) ?: [])
                    @foreach ($reportNotificationEmails as $email)
                        <div class="form-check my-2">
                            <input style="height: 20px; width: 20px;" class="form-check-input mt-1 email_field"
                                type="checkbox" name="emails[]" value="{{ $email }}" id="email_{{ $loop->index }}">
                            <label class="form-check-label ms-2 fs-5" for="email_{{ $loop->index }}">{{ $email }}</label>
                        </div>
                    @endforeach
                    @else
                    <span class="fs-5">No emails configured. Set up <a href="{{ route('settings.report-notifications') }}">here</a>.</span>
                    @endif

                    <div class="my-4">
                        @foreach ($formats as $format)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input mt-1" type="radio" name="file_format" id="{{ $format }}_radio" checked
                                    value="{{ $format }}">
                                <label class="form-check-label fs-5" for="{{ $format }}_radio">{{ $format }}</label>
                            </div>
                        @endforeach
                    </div>

                    <button id="reportMailFormBtn" class="btn btn-primary mt-1" type="submit" disabled>Send</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $("#reportMailForm").on("submit", function (e) {
            e.preventDefault();

            $("#reportMailFormBtn").attr('disabled', true);

            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: form.attr('method'),
                url: actionUrl,
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    $("#reportMailFormMsg").text(response.message).addClass(response.success ? 'text-success' : 'text-danger');
                    $("#reportMailFormBtn").attr('disabled', false);

                    if (response.success) {
                        setTimeout(() => {
                            form[0].reset();

                            $("#emailReport").modal("hide");
                            $("#reportMailFormMsg").text('');
                        }, 2000);
                    }
                },
                error: function (xhr) {
                    $("#reportMailFormBtn").attr('disabled', false);
                }
            });
        });

        $(".email_field").on("change", function () {
            if ($("#reportMailForm").find('input[name="emails[]"]:checked').length > 0) {
                $("#reportMailFormBtn").attr('disabled', false);
            } else {
                $("#reportMailFormBtn").attr('disabled', true);
            }
        });

    });
</script>