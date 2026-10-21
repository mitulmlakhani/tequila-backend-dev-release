@php
    $uuid = Illuminate\Support\Str::uuid()->toString();
@endphp

<div class="pool card mb-3" id="pool_{{ $uuid }}" data-pool="{{ $uuid }}" style="border-color: #EFEEEE;">

    <div class="card-header d-flex align-items-center gap-3 mb-3 w-100" style="background-color: #EFEEEE;">
        <input type="hidden" name="pools[{{ $uuid }}][pool_pk]" value="{{ $pool['id'] ?? '' }}" />
        <div class="w-100">
            <label class="form-label fw-bold {{ $first ?? 'nope' }}">
                Pool Name
            </label>

            <input type="text" class="form-control" name="pools[{{ $uuid }}][pool_name]"
                placeholder="Pool Name" value="{{ $pool['pool_name'] ?? '' }}" />
        </div>

        <div class="w-100">
            <label class="form-label">
                Percentage %
            </label>
            <input type="number" class="form-control" name="pools[{{ $uuid }}][tip_percentage]"
                placeholder="Percentage" value="{{ $pool['tip_percentage'] ?? '' }}" min="0" max="100"
                step="0.01" />
        </div>

        <div>
            <button type="button" class="btn btn-danger remove_pool mt-4"
                style="min-width: auto; white-space: nowrap; visibility: {{ ($first ?? 'no') == 'no' ? '' : 'hidden' }};">
                Delete Pool
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Employees</h5>
            <button type="button" class="btn btn-primary add_emp_btn" data-pool="{{ $uuid }}">
                Add Employee
            </button>
        </div>

        <div class="emp_container mt-3" data-pool="{{ $uuid }}" id="emp-sec-{{ $uuid }}">
            @if ($pool['employees'] ?? false)
                @foreach ($pool['employees'] as $empId => $empData)
                    <x-tips.pool-employee-section :poolId="$uuid" :employees="$employees" :empId="$empId"
                        :empData="$empData" :first="$loop->first ? 1 : 0" />
                @endforeach
            @else
                <x-tips.pool-employee-section :poolId="$uuid" :employees="$employees" :first="1" />
            @endif
        </div>
    </div>
</div>
