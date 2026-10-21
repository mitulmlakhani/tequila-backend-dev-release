@php
    $format = $format ?? 'm-d-Y H:i';
    $time_enable = $time_enable ?? true;
@endphp

<script>
    var time_enable = Number("{{ $time_enable ? 1 : 0 }}");
</script>

@php
    if ($time_enable !== false) {
        $restaurant = getCurrentRestaurant();
        $restaurant_open_time = \Carbon\Carbon::parse($restaurant->open_time ?: "05:00 AM");
        $restaurant_close_time = \Carbon\Carbon::parse($restaurant->close_time ?: "12:59 PM");
    }
@endphp

@if($time_enable !== false)
    <script>
        var filter_start_time = {
            hour: "{{ $restaurant_open_time->hour }}".toString().padStart(2, "0"),
            minutes: "{{ $restaurant_open_time->minute }}".toString().padStart(2, "0"),
        }

        var filter_end_time = {
            hour: "{{ $restaurant_close_time->hour }}".toString().padStart(2, "0"),
            minutes: "{{ $restaurant_close_time->minute }}".toString().padStart(2, "0"),
        }
    </script>
@endif

<div class="row g-2 align-items-end">
    <!-- FROM Date -->
    <div class="{{ $time_enable ? 'col-md-2' : 'col-md-3' }}">
        <label for="start_date_date" class="form-label">From Date</label>
        <input type="text" name="start_date_date" id="start_date_date" class="form-control datepicker dta"
            value="{{ old('start_date_date', \Carbon\Carbon::createFromFormat($format, $start_date)->format('m-d-Y')) }}">
    </div>

    @if($time_enable !== false)
        <!-- FROM Time - Hour -->
        <div class="col-md-1">
            <label class="form-label">Hour</label>
            <select id="start_hour" class="form-select dta">
                @for ($i = 1; $i <= 12; $i++)
                    @php $val = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                    <option value="{{ $val }}" {{ \Carbon\Carbon::createFromFormat($format, $start_date)->format('h') == $val ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                @endfor
            </select>
        </div>

        <!-- FROM Time - Minute -->
        <div class="col-md-1">
            <label class="form-label">Minute</label>
            <select id="start_minute" class="form-select dta">
                @for ($i = 0; $i < 60; $i++)
                    @php $val = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                    <option value="{{ $val }}" {{ \Carbon\Carbon::createFromFormat($format, $start_date)->format('i') == $val ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                @endfor
            </select>
        </div>

        <!-- FROM Time - AM/PM -->
        <div class="col-md-1">
            <label class="form-label">AM/PM</label>
            @php $start_ampm = \Carbon\Carbon::createFromFormat($format, $start_date)->format('A'); @endphp
            <select id="start_ampm" class="form-select dta">
                <option value="AM" {{ $start_ampm == 'AM' ? 'selected' : '' }}>AM</option>
                <option value="PM" {{ $start_ampm == 'PM' ? 'selected' : '' }}>PM</option>
            </select>
        </div>
    @endif

    <!-- TO Date -->
    <div class="{{ $time_enable ? 'col-md-2' : 'col-md-3' }}">
        <label for="end_date_date" class="form-label">To Date</label>
        <input type="text" name="end_date_date" id="end_date_date" class="form-control datepicker dta"
            value="{{ old('end_date_date', \Carbon\Carbon::createFromFormat($format, $end_date)->format('m-d-Y')) }}">
    </div>

    @if($time_enable !== false)
        <!-- TO Time - Hour -->
        <div class="col-md-1">
            <label class="form-label">Hour</label>
            <select id="end_hour" class="form-select dta">
                @for ($i = 1; $i <= 12; $i++)
                    @php $val = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                    <option value="{{ $val }}" {{ \Carbon\Carbon::createFromFormat($format, $end_date)->format('h') == $val ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                @endfor
            </select>
        </div>

        <!-- TO Time - Minute -->
        <div class="col-md-1">
            <label class="form-label">Minute</label>
            <select id="end_minute" class="form-select dta">
                @for ($i = 0; $i < 60; $i++)
                    @php $val = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                    <option value="{{ $val }}" {{ \Carbon\Carbon::createFromFormat($format, $end_date)->format('i') == $val ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                @endfor
            </select>
        </div>

        <!-- TO Time - AM/PM -->
        <div class="col-md-1">
            <label class="form-label">AM/PM</label>
            @php $end_ampm = \Carbon\Carbon::createFromFormat($format, $end_date)->format('A'); @endphp
            <select id="end_ampm" class="form-select dta">
                <option value="AM" {{ $end_ampm == 'AM' ? 'selected' : '' }}>AM</option>
                <option value="PM" {{ $end_ampm == 'PM' ? 'selected' : '' }}>PM</option>
            </select>
        </div>
    @endif

    {!! $child ?? '' !!}


    <input type="hidden" name="start_date" id="start_date_final">
    <input type="hidden" name="end_date" id="end_date_final">

    <!-- Submit -->
    <div class="col-md-1">
        <button type="submit" class="btn btn-primary w-100 date-time-filter-submit-btn">Apply</button>
    </div>

    <!-- Quick Filters -->
    <div class="col-12 mt-2">
        <div class="d-flex flex-wrap gap-2">
            @foreach(['today', 'yesterday', 'this_week', 'last_week', 'this_month', 'last_month', 'last_quarter', 'last_6_months'] as $range)
                <button type="button" class="btn btn-outline-secondary btn-sm quick-range"
                    data-range="{{ $range }}">{{ ucwords(str_replace('_', ' ', $range)) }}</button>
            @endforeach
        </div>
    </div>

    <!-- Last 12 Months -->
    <div class="col-12 mt-2">
        <div class="d-flex flex-wrap gap-2">
            @php $current = now(); @endphp
            @for ($i = 0; $i < 12; $i++)
                @php
                    $month = $current->copy()->subMonths($i);
                    $label = $month->format('F Y');
                    $dataValue = $month->format('Y-m');
                @endphp
                <button type="button" class="btn btn-outline-info btn-sm last-12-months"
                    data-month="{{ $dataValue }}">{{ $label }}</button>
            @endfor
        </div>
    </div>
</div>