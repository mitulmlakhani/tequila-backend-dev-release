@extends('layouts.master') @section('content')
    <style></style>
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">

                <div class="col-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="main-heading">
                            <div class="col-10 col-md-10 col-lg-10">
                                <h4>{{ trans('lang.tip') }} {{ trans('lang.menuIconSettings') }}</h4>
                            </div>
                            <div class="col-2 col-md-2 col-lg-2">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12 mb-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Tip Fees</h5>
                        </div>

                        <form id="tip-form-fees-settings-form" action="{{ route('settings.tip-fees-settings.save') }}" method="post">
                            @csrf
                            <div class="col-md-3 m-3">
                                <div class="border rounded p-3 shadow-sm d-flex justify-content-between align-items-center">
                                    <span class="fw-medium">Tip Fees Paid By Employee</span>
                                    <div class="form-check form-switch d-flex align-items-center gap-2">
                                        <input type="checkbox"
                                            class="form-check-input"
                                            name="tip_fees_paid_by_employee"
                                            id="tip_fees_paid_by_employee"
                                            onchange="document.getElementById('tip-form-fees-settings-form').submit()"
                                            {{ ($settings['tip_fees_paid_by_employee'] ?? null) ? 'checked' : ''}}
                                            value="1">
                                        <label class="form-check-label small text-muted" for="tip_fees_paid_by_employee">
                                            {{ ($settings['tip_fees_paid_by_employee'] ?? null) ? 'Yes' : 'No' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12">
                    <form action="{{ route('settings.tip-settings.save') }}" method="post">
                        @csrf
                        <div class="main-content p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center m-3 justify-content-start">
                                    <div class="form-check me-3">
                                        <input class="form-check-input mt-1" type="radio" name="tip_type" value="tip_pool"
                                            id="tip_pools_radio" role="button" aria-expanded="false"
                                            aria-controls="pool_tips"
                                            {{ old('tip_type', 'tip_pool') == 'tip_pool' ? 'checked' : '' }} />
                                        <label class="form-check-label h4" for="tip_pools_radio">
                                            &nbsp; Pool Tips
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input mt-1" type="radio" name="tip_type"
                                            value="tip_share" id="share_tips_radio" role="button" aria-expanded="false"
                                            {{ old('tip_type') ? 'checked' : '' }}
                                            aria-controls="share_tips_radio" />
                                        <label class="form-check-label h4" for="share_tips_radio">
                                            &nbsp; Share Tips
                                        </label>
                                    </div>
                                </div>

                                @can('tip-settings.save')
                                    <button class="btn btn-primary">Save</button>
                                @endcan
                            </div>

                            <div class="row m-3">
                                <div class="col-12">
                                    <div id="pool_tips"
                                        style="display: {{ old('tip_type', 'tip_pool') == 'tip_pool' ? 'block' : 'none' }};">

                                        <div class="mb-5 mx-auto d-flex align-items-center justify-content-center w-50">
                                            <label for="tip_pool_status" class="col-form-label"><strong style="white-space: nowrap;">Tip Pool Status</strong></label>
                                            <select class="form-select ms-2" name="tip_pool_status" id="tip_pool_status">
                                                <option {{ old('tip_pool_status', $activeTipSetting['tip_pool'] ?? '') != 1 ? 'selected' : '' }} value="0">In-Active</option>
                                                <option {{ old('tip_pool_status', $activeTipSetting['tip_pool'] ?? '') == 1 ? 'selected' : '' }} value="1">Active</option>
                                            </select>
                                        </div>

                                        <div id="pool_wrapper">
                                            @if (count(old('pools', $tipPools ?: [])) > 0)
                                                @foreach (old('pools', $tipPools ?: []) as $poolData)
                                                    <x-tips.pool-section :pool="$poolData" :employees="$employees"
                                                        :first="$loop->first ? 'yes' : 'no'" />
                                                @endforeach
                                            @else
                                                <x-tips.pool-section :employees="$employees" :first="'yes'" />
                                            @endif
                                        </div>

                                        <div class="text-center my-4">
                                            <button class="btn btn-outline-primary btn-lg w-100" id="add_pool_btn"
                                                type="button">
                                                <i class="bi bi-plus-circle me-2"></i>Add New Pool
                                            </button>
                                        </div>
                                    </div>

                                    <div id="share_tips"
                                        style="display: {{ old('tip_type') == 'tip_share' ? 'block' : 'none' }};">
                                        <div class="mb-5 mx-auto d-flex align-items-center justify-content-center w-50">
                                            <label for="tip_share_status" class="col-form-label"><strong style="white-space: nowrap;">Tip Share Status</strong></label>
                                            <select class="form-select ms-2" name="tip_share_status" id="tip_share_status">
                                                <option {{ old('tip_share_status', $activeTipSetting['tip_share'] ?? '') != 1 ? 'selected' : '' }} value="0">In-Active</option>
                                                <option {{ old('tip_share_status', $activeTipSetting['tip_share'] ?? '') == 1 ? 'selected' : '' }} value="1">Active</option>
                                            </select>
                                        </div>

                                        <div class="card card-body">
                                            <div class="card-header">
                                                Tip Share
                                            </div>
                                            <div class="card-body">
                                                The Tip Share option will appear when you clock out.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
    </div>
    <!--Main Section End-->
@endsection

@section('js')
    <script>
        $("input[name='tip_type']").on("change", function() {
            if ($(this).val() === "tip_pool") {
                $("#pool_tips").show().find(':input').prop('disabled', false);
                $("#share_tips").hide().find(':input').prop('disabled', true);
            } else {
                $("#pool_tips").hide().find(':input').prop('disabled', true);
                $("#share_tips").show().find(':input').prop('disabled', false);
            }
        });
    </script>

    <script>
        let empSecTemplate = `{!! str_replace(
            ["\n", "\r"],
            '',
            view('components.tips.pool-employee-section', ['employees' => $employees, 'poolId' => '__POOL__']),
        ) !!}`;

        let poolTemplate = `{!! str_replace(
            ["\n", "\r"],
            '',
            view('components.tips.pool-section', ['employees' => $employees, 'poolId' => '__POOL__']),
        ) !!}`;
    </script>

    <script>
        let poolCount = "{{ count(old('pools', [1])) }}";

        $("#add_pool_btn").on("click", function() {
            poolCount++;

            let html = poolTemplate.replace(/__POOL__/g, poolCount);

            $("#pool_wrapper").append(html);
        });

        $(document).on("click", ".add_emp_btn", function() {
            let poolId = $(this).data("pool");
            let html = empSecTemplate.replace(/__POOL__/g, poolId);

            $("#emp-sec-" + poolId).append(html);
        });

        // remove pool
        $(document).on("click", ".remove_pool", function() {
            $(this).closest(".pool").fadeOut(500, function() {
                $(this).remove();
            });
        });

        // remove emp block
        $(document).on("click", ".remove_emp_btn", function() {
            $(this).closest(".emp_sec").fadeOut(500, function() {
                $(this).remove();
            });
        });
    </script>
@endsection

<script>
    @if (Session::has('errors'))
        var isValidError = true;
    @else
        var isValidError = false;
    @endif
</script>
