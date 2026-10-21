@php
    $uuid = Illuminate\Support\Str::uuid()->toString();
@endphp

<div class="card emp_sec mb-3">
    <div class="d-flex align-items-center gap-3 card-body" data-pool="{{ $poolId }}">
        <select class="form-select" name="pools[{{ $poolId }}][employees][{{ $uuid }}][employee_id]"
            required>
            <option value="">Select Employee</option>
            @foreach ($employees as $employee)
                <option {{ ($empData['employee_id'] ?? null) == $employee->id ? 'selected' : '' }}
                    value="{{ $employee->id }}">
                    {{ $employee->name }}</option>
            @endforeach
        </select>

        <input type="number" class="form-control"
            name="pools[{{ $poolId }}][employees][{{ $uuid }}][percentage]"
            placeholder="Pool Percentage (10%)" value="{{ $empData['percentage'] ?? '' }}" required />

        @if ($first ?? null)
            <a href="javascript:void(0)" class="badge badge-sm" style="visibility: hidden;">
                <img src="/assets/images/dustbin.png" alt="dustbin">
            </a>
        @else
            <a href="javascript:void(0)" class="badge badge-sm remove_emp_btn">
                <img src="/assets/images/dustbin.png" alt="dustbin">
            </a>
        @endif
    </div>
</div>
