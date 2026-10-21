@extends('layouts.master')
@section('title')
    Happy Hours
@endsection

@section('css')
<style>
    .select2-container {
        position: relative !important;
        top: 0 !important;
        left: 0 !important;
    }

    /* ─── Item Picker Container ─────────────────────────────── */
    .hh-item-picker {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    /* Category pill tabs */
    .hh-cat-tabs {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 8px;
        padding: 12px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        scrollbar-width: thin;
        -webkit-overflow-scrolling: touch;
    }

    .hh-cat-tabs::-webkit-scrollbar {
        height: 5px;
    }
    .hh-cat-tabs::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .hh-cat-tabs::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .hh-cat-tab {
        flex-shrink: 0;
        padding: 7px 16px;
        border-radius: 20px;
        border: 1px solid #cbd5e1;
        background: #fff;
        cursor: pointer;
        font-size: 13.5px;
        font-weight: 600;
        color: #475569;
        transition: all 0.18s ease;
        white-space: nowrap;
    }

    .hh-cat-tab:hover {
        border-color: #3b82f6;
        color: #2563eb;
        background: #eff6ff;
    }

    .hh-cat-tab.active {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    }

    /* Select-all & Quick Action row inside picker */
    .hh-picker-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 16px;
        background: #f1f5f9;
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
        font-weight: 600;
        flex-wrap: wrap;
    }

    .hh-picker-toolbar input[type="checkbox"] {
        width: 18px !important;
        height: 18px !important;
        cursor: pointer;
        accent-color: #2563eb;
        margin: 0;
    }

    /* Items grid (Inline sidebar picker) */
    .hh-items-grid {
        max-height: 380px;
        overflow-y: auto;
        padding: 12px;
        display: grid;
        grid-template-columns: 1fr;
        align-content: start;
        grid-auto-rows: max-content;
        gap: 12px;
        background: #f8fafc;
    }

    .hh-items-grid::-webkit-scrollbar { width: 6px; }
    .hh-items-grid::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .hh-items-grid::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    /* ─── Item Card with Price Input ─── */
    .hh-item-card {
        display: flex;
        flex-direction: column;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fff;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .hh-item-card:hover {
        border-color: #64748b;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
        transform: translateY(-1px);
    }

    .hh-item-card.selected {
        border-color: #2563eb;
        background: #fff;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.12);
    }

    .hh-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        background: #f1f5f9;
        border-bottom: 1.5px solid #e2e8f0;
        border-left: 4px solid #64748b;
        cursor: pointer;
        user-select: none;
        transition: all 0.18s ease;
    }

    .hh-item-card.selected .hh-card-header {
        background: #eff6ff;
        border-bottom-color: #dbeafe;
        border-left-color: #2563eb;
    }

    .hh-card-header input[type="checkbox"] {
        flex-shrink: 0;
        width: 19px;
        height: 19px;
        cursor: pointer;
        accent-color: #2563eb;
        margin: 0;
    }

    .hh-item-title-box {
        flex: 1;
        min-width: 0;
    }

    .hh-item-name {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 0.3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .hh-item-card.selected .hh-item-name {
        color: #1e40af;
    }

    .hh-item-reg-price {
        font-size: 12px;
        color: #475569;
        font-weight: 600;
    }

    /* Simple Item Card Body (No variants) */
    .hh-simple-card-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 12px;
        background: #fff;
    }

    .hh-price-label {
        font-size: 12px;
        font-weight: 700;
        color: #2563eb;
        white-space: nowrap;
    }

    .hh-price-group {
        width: 120px;
        flex-shrink: 0;
    }

    .hh-price-group .input-group-text {
        font-size: 12px;
        font-weight: 700;
        padding: 3px 8px;
        background: #f8fafc;
        color: #64748b;
        border-color: #cbd5e1;
    }

    .hh-price-group input {
        font-size: 13px;
        font-weight: 700;
        padding: 3px 8px;
        text-align: right;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    /* Variants sub-list inside item card */
    .hh-variants-container {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 8px 10px;
        background: #fff;
    }

    .hh-variant-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 6px 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 7px;
        transition: all 0.15s ease;
    }

    .hh-variant-row:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .hh-variant-row.selected {
        background: #eff6ff;
        border-color: #bfdbfe;
    }

    .hh-variant-left {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        flex: 1;
        cursor: pointer;
        user-select: none;
    }

    .hh-variant-left input[type="checkbox"] {
        flex-shrink: 0;
        width: 16px;
        height: 16px;
        cursor: pointer;
        accent-color: #2563eb;
        margin: 0;
    }

    .hh-variant-name {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .hh-variant-reg {
        font-size: 11.5px;
        color: #64748b;
        background: #fff;
        padding: 2px 7px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
        font-weight: 500;
    }

    .hh-variant-price-group {
        width: 110px;
        flex-shrink: 0;
    }

    .hh-variant-price-group .input-group-text {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 6px;
        background: #f8fafc;
        color: #64748b;
        border-color: #cbd5e1;
    }

    .hh-variant-price-group input {
        font-size: 12.5px;
        font-weight: 700;
        padding: 2px 6px;
        text-align: right;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .hh-price-error {
        font-size: 11px;
        font-weight: 600;
        color: #dc2626;
        margin-top: 3px;
        text-align: right;
        line-height: 1.2;
    }

    /* ─── Selected Items & Prices Summary Drawer ────────────────────────── */
    .hh-summary-container {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        margin-top: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .hh-summary-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .hh-summary-table-wrap {
        max-height: 240px;
        overflow-y: auto;
    }

    .hh-summary-table-wrap::-webkit-scrollbar { width: 6px; }
    .hh-summary-table-wrap::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    .hh-summary-table {
        width: 100%;
        margin-bottom: 0;
        font-size: 13.5px;
    }

    .hh-summary-table th {
        background: #f1f5f9;
        padding: 9px 12px;
        font-size: 12.5px;
        font-weight: 700;
        color: #475569;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .hh-summary-table td {
        padding: 8px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .hh-summary-empty {
        padding: 24px;
        text-align: center;
        color: #94a3b8;
        font-size: 14px;
    }

    .hh-count-badge {
        font-size: 13px;
        font-weight: 700;
        color: #2563eb;
        background: #eff6ff;
        padding: 4px 12px;
        border-radius: 12px;
        border: 1px solid #bfdbfe;
    }

    /* ─── Modal Items Picker (Full Screen) ───────── */
    .hh-modal-items-grid {
        max-height: calc(100vh - 380px);
        min-height: 320px;
        overflow-y: auto;
        padding: 16px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        align-content: start;
        grid-auto-rows: max-content;
        gap: 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
    }

    .hh-modal-items-grid::-webkit-scrollbar { width: 6px; }
    .hh-modal-items-grid::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .hh-modal-items-grid::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    #hh-modal-summary-wrap {
        max-height: 180px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    /* ─── Table badges ────────────────────────────── */
    .badge-order-type {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    /* ─── Table Items & Prices Column ──────────────── */
    .hh-table-items-container {
        display: flex;
        flex-direction: column;
        gap: 4px;
        max-height: 125px;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 2px;
        white-space: normal;
    }

    .hh-table-items-container::-webkit-scrollbar {
        width: 4px;
    }

    .hh-table-items-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .hh-table-items-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .hh-table-item-chip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 12px;
        transition: all 0.15s ease;
    }

    .hh-table-item-chip:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .hh-chip-name {
        font-weight: 600;
        color: #1e293b;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 12px;
        max-width: 150px;
    }

    .hh-chip-reg-price {
        font-size: 11px;
        color: #94a3b8;
    }

    .hh-chip-price {
        font-weight: 700;
        color: #15803d;
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        border-radius: 4px;
        padding: 1px 6px;
        font-size: 11.5px;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .hh-modal-items-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">

                {{-- ─── LEFT: Form ─────────────────────────────── --}}
                @php
                    $oldEditId     = old('edit_id', '');
                    $isEdit        = !empty($oldEditId);
                    $formAction    = $isEdit ? route('happy-hours-edit', ['id' => $oldEditId]) : route('happy-hours-create');
                    $formTitle     = $isEdit ? 'Edit Happy Hours' : 'Add Happy Hours';
                    $submitBtnText = $isEdit ? 'Update' : 'Save';
                    $oldDays       = old('days', []);
                    $oldOrderTypes = old('order_types', []);
                    $oldSchedule   = old('schedule', []);
                    $oldItems      = old('items', []);
                    $allDaysChecked = is_array($oldDays) && count($oldDays) === 7;
                @endphp
                <div class="col-12 col-md-5 col-lg-5">
                    <div class="main-heading">
                        <h4 id="hh-form-title">{{ $formTitle }}</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ $formAction }}" method="POST" id="hh-form">
                            @csrf
                            <input type="hidden" name="edit_id" id="hh_edit_id" value="{{ $oldEditId }}">

                            {{-- Name --}}
                            <div class="mb-3">
                                <label class="form-label" for="hh_name">Name</label>
                                <input type="text" id="hh_name" name="name" placeholder="e.g. Evening Happy Hour"
                                    class="form-control" required value="{{ old('name', '') }}">
                                @error('name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="mb-3">
                                <label class="form-label" for="hh_is_active">Status</label>
                                <select class="form-select" id="hh_is_active" name="is_active">
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Order Types --}}
                            <div class="mb-3">
                                <label class="form-label">Order Types <span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-3 mt-1">
                                    @foreach ($orderTypes as $typeId => $typeName)
                                        <div class="form-check">
                                            <input class="form-check-input hh-order-type-check" type="checkbox"
                                                name="order_types[]" value="{{ $typeId }}"
                                                id="ot_{{ $typeId }}"
                                                {{ (is_array($oldOrderTypes) && in_array($typeId, $oldOrderTypes)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ot_{{ $typeId }}">
                                                {{ ucwords(str_replace('partner', ' Partner', $typeName)) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('order_types')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Schedule --}}
                            <div class="mb-3">
                                <div class="row pt-2">
                                    <label class="form-label">Schedule</label>

                                    {{-- All days row --}}
                                    <div class="col-12 mt-2">
                                        <div class="d-flex align-items-center justify-content-left gap-1">
                                            <label style="min-width:40px;" class="form-check-label"><strong>All</strong></label>
                                            <input style="min-width:25px;min-height:25px;" class="form-check-input mx-2"
                                                type="checkbox" id="hh_all_days" {{ $allDaysChecked ? 'checked' : '' }}>
                                            <input type="time" value="" class="form-control" id="hh_schedule_from_time">
                                            <input type="time" value="" class="form-control" id="hh_schedule_to_time">
                                        </div>
                                    </div>

                                    @foreach (['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                                        @php
                                            $isDayChecked = is_array($oldDays) && in_array($day, $oldDays);
                                            $dayFrom = $oldSchedule[$day]['from_time'] ?? '';
                                            $dayTo   = $oldSchedule[$day]['to_time'] ?? '';
                                        @endphp
                                        <div class="col-12 mt-3">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <label style="min-width:40px;" class="form-check-label" for="hh_{{ $day }}">
                                                    <strong>{{ ucwords(substr($day,0,3)) }}</strong>
                                                </label>
                                                <input style="min-width:25px;min-height:25px;"
                                                    class="form-check-input hh-days-checkbox mx-2"
                                                    name="days[]" type="checkbox"
                                                    id="hh_{{ $day }}" value="{{ $day }}"
                                                    {{ $isDayChecked ? 'checked' : '' }}>
                                                <input type="time" value="{{ $dayFrom }}" class="form-control hh-from-field"
                                                    name="schedule[{{ $day }}][from_time]"
                                                    id="hh_{{ $day }}_from_time">
                                                <input type="time" value="{{ $dayTo }}" class="form-control hh-to-field"
                                                    name="schedule[{{ $day }}][to_time]"
                                                    id="hh_{{ $day }}_to_time">
                                            </div>
                                        </div>
                                    @endforeach

                                    @error('days')
                                        <div class="col-12">
                                            <div class="text-danger validation-error mt-1">{{ $message }}</div>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- ─── ITEM PICKER & SPECIAL PRICING ─── --}}
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="form-label mb-0" style="font-size: 16px;"><strong>Select Items & Prices</strong></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check mb-0 d-flex align-items-center gap-1 ms-1">
                                            <input type="checkbox" id="hh-select-all-global" class="form-check-input mt-0"
                                                style="width:19px;height:19px;cursor:pointer;">
                                            <label for="hh-select-all-global" class="form-check-label text-primary fw-bold" style="cursor:pointer;user-select:none;font-size:14.5px;">
                                                All Items
                                            </label>
                                        </div>
                                        <span class="hh-count-badge" id="hh-selected-count">0 selected</span>
                                        <button type="button" class="btn btn-outline-primary d-flex align-items-center gap-1 ms-1 py-1 px-2"
                                            style="font-size: 13.5px;"
                                            id="hh-open-modal-btn" data-bs-toggle="modal" data-bs-target="#hhItemsModal">
                                            <i class="bx bx-fullscreen fs-5"></i> Full Screen
                                        </button>
                                    </div>
                                </div>

                                <div class="text-danger validation-error mb-1" id="hh-items-error" style="{{ $errors->has('items') ? '' : 'display:none;' }}">
                                    {{ $errors->first('items') ?: 'Please select at least one item.' }}
                                </div>

                                <div class="hh-item-picker">
                                    {{-- Category Tabs --}}
                                    <div class="hh-cat-tabs" id="hh-cat-tabs">
                                        @foreach ($categories as $category)
                                            @if ($category->items->count() > 0)
                                                <button type="button"
                                                    class="hh-cat-tab"
                                                    data-cat-id="{{ $category->id }}">
                                                    {{ $category->category_name }}
                                                    <span class="ms-1 opacity-75">({{ $category->items->count() }})</span>
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>

                                    {{-- Toolbar inside picker --}}
                                    <div class="hh-picker-toolbar">
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="checkbox" id="hh-select-all-cat" class="form-check-input"
                                                style="width:18px;height:18px;cursor:pointer;">
                                            <label for="hh-select-all-cat" style="cursor:pointer;user-select:none;">
                                                Select all in category
                                            </label>
                                            <span class="text-muted ms-1" id="hh-cat-count" style="font-size:13px;"></span>
                                        </div>
                                        <div class="d-flex align-items-center gap-1 ms-auto">
                                            <span class="text-muted small">Quick % Off:</span>
                                            <input type="number" id="hh-quick-cat-discount" class="form-control form-control-sm text-center"
                                                style="width: 65px;" placeholder="20" min="0" max="100" step="any">
                                            <button type="button" id="hh-apply-cat-discount" class="btn btn-sm btn-outline-primary py-0 px-2">Apply</button>
                                        </div>
                                    </div>

                                    {{-- Items grid (rendered by JS) --}}
                                    <div class="hh-items-grid" id="hh-items-grid">
                                        <div class="hh-empty-cat">Select a category above</div>
                                    </div>
                                </div>

                                {{-- ─── Selected Items & Prices Summary Table ─── --}}
                                <div class="hh-summary-container">
                                    <div class="hh-summary-header">
                                        <span class="fw-bold" style="font-size: 14px;">Selected Items & Special Prices</span>
                                        <button type="button" class="btn btn-link text-danger text-decoration-none p-0 fw-semibold" id="hh-clear-all-btn" style="font-size: 13px;">Clear All</button>
                                    </div>
                                    <div class="hh-summary-table-wrap">
                                        <table class="table hh-summary-table table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Reg Price</th>
                                                    <th style="width: 140px;">Happy Hour Price</th>
                                                    <th style="width: 40px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="hh-summary-tbody">
                                                <tr>
                                                    <td colspan="4" class="hh-summary-empty" id="hh-summary-placeholder">
                                                        No items selected yet. Pick items from categories above and set their special prices.
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Hidden inputs for form submission --}}
                                <div id="hh-items-hidden"></div>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-primary px-4">{{ $submitBtnText }}</button>
                                <button type="button" id="hh-cancel-btn" class="btn btn-outline-secondary px-4">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ─── RIGHT: List table ───────────────────────── --}}
                <div class="col-12 col-md-7 col-lg-7">
                    <div class="main-content p-3">
                        <table id="hh_table" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Days / Time</th>
                                    <th>Order Types</th>
                                    <th>Items & Special Prices</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($happyHours as $hh)
                                    <tr>
                                        <td class="fs-6">{{ $loop->iteration }}</td>
                                        <td><strong class="fs-6">{{ ucwords($hh['name']) }}</strong></td>
                                        <td>
                                            @foreach ($hh['schedule'] ?: [] as $day => $sch)
                                                <span>
                                                    <strong style="min-width:90px;">{{ ucwords($day) }}: </strong>
                                                    {{ $sch['from'] ?? '' }} &ndash; {{ $sch['to'] ?? '' }}
                                                </span><br />
                                            @endforeach
                                        </td>
                                        <td>
                                            @php
                                                $orderTypeLabels = [
                                                    1 => ['label' => 'Dine-In',  'color' => 'primary'],
                                                    2 => ['label' => 'Takeaway', 'color' => 'info'],
                                                    3 => ['label' => 'Online',   'color' => 'success'],
                                                    4 => ['label' => 'Gift Card','color' => 'warning'],
                                                    5 => ['label' => 'Delivery', 'color' => 'danger'],
                                                ];
                                            @endphp
                                            <div class="d-flex flex-wrap gap-1">
                                                @forelse ($hh['order_types'] as $ot)
                                                    @php $meta = $orderTypeLabels[$ot] ?? ['label' => $ot, 'color' => 'secondary']; @endphp
                                                    <span class="badge fs-6 bg-{{ $meta['color'] }} badge-order-type">
                                                        {{ $meta['label'] }}
                                                    </span>
                                                @empty
                                                    <span class="text-muted small">All</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td style="white-space: normal !important; min-width: 220px; max-width: 300px;">
                                            <div class="d-flex align-items-center gap-1 mb-1">
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 11px; font-weight: 700;">
                                                    {{ $hh['item_count'] }} {{ Str::plural('item', $hh['item_count']) }}
                                                </span>
                                            </div>
                                            <div class="hh-table-items-container">
                                                @foreach ($hh['items'] as $item)
                                                    <div class="hh-table-item-chip" title="Regular: ${{ number_format($item['reg_price'], 2) }} | Happy Hour: ${{ number_format($item['price'], 2) }}">
                                                        <span class="hh-chip-name" title="{{ $item['name'] }}">{{ $item['name'] }}</span>
                                                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                                            @if($item['reg_price'] > 0)
                                                                <span class="hh-chip-reg-price text-muted text-decoration-line-through">${{ number_format($item['reg_price'], 2) }}</span>
                                                            @endif
                                                            <span class="hh-chip-price">${{ number_format($item['price'], 2) }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div class="{{ $hh['is_active'] ? 'reserved' : 'pending' }}">
                                                <img src="{{ $hh['is_active'] ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                    class="me-1" alt="{{ $hh['is_active'] ? 'active' : 'inactive' }}"
                                                    style="width:16px;">
                                                <span class="fs-6">{{ $hh['is_active'] ? 'Active' : 'Inactive' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="me-2">
                                                @can('happy_hours.edit')
                                                    <a href="#" class="hh-edit-btn"
                                                        data-id="{{ $hh['id'] }}">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <span>
                                                @can('happy_hours.delete')
                                                    <a href="#" class="hh-delete-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteHHModal"
                                                        data-url="{{ route('happy-hours-delete', ['id' => $hh['id']]) }}">
                                                        <img src="{{ asset('assets/images/dustbin.png') }}" alt="delete">
                                                    </a>
                                                @endcan
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ─── Full-Screen Items Selection Modal ─── --}}
    <div class="modal fade" id="hhItemsModal" tabindex="-1" aria-labelledby="hhItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header py-3 px-4 bg-light border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-food-menu fs-3 text-primary"></i>
                        <h5 class="modal-title mb-0 fw-bold" id="hhItemsModalLabel" style="font-size: 18px;">Select Happy Hour Items & Prices</h5>
                        <span class="hh-count-badge ms-2" id="hh-modal-selected-count">0 selected</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 ms-auto">
                        <div class="input-group" style="min-width: 320px;">
                            <span class="input-group-text bg-white"><i class="bx bx-search fs-5"></i></span>
                            <input type="text" id="hh-modal-search" class="form-control" style="font-size: 15px;" placeholder="Search items in category...">
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-4">
                    {{-- Category Tabs --}}
                    <div class="hh-cat-tabs mb-3" id="hh-modal-cat-tabs">
                        @foreach ($categories as $category)
                            @if ($category->items->count() > 0)
                                <button type="button"
                                    class="hh-cat-tab hh-modal-cat-tab"
                                    data-cat-id="{{ $category->id }}">
                                    {{ $category->category_name }}
                                    <span class="ms-1 opacity-75">({{ $category->items->count() }})</span>
                                </button>
                            @endif
                        @endforeach
                    </div>

                    {{-- Select-all bar & Quick Tools --}}
                    <div class="hh-picker-toolbar rounded mb-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" id="hh-modal-select-all-cat" class="form-check-input"
                                    style="width:20px;height:20px;cursor:pointer;">
                                <label for="hh-modal-select-all-cat" style="cursor:pointer;user-select:none;">
                                    Select all in this category
                                </label>
                                <span class="text-muted ms-2" id="hh-modal-cat-count" style="font-size:14px;"></span>
                            </div>
                            <div class="d-flex align-items-center gap-2 border-start ps-3">
                                <input type="checkbox" id="hh-modal-select-all-global" class="form-check-input"
                                    style="width:20px;height:20px;cursor:pointer;">
                                <label for="hh-modal-select-all-global" class="text-primary fw-bold" style="cursor:pointer;user-select:none;">
                                    Select all items (All categories)
                                </label>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <span class="text-muted small fw-semibold">Quick Discount % for Category:</span>
                            <input type="number" id="hh-modal-cat-discount" class="form-control form-control-sm text-center"
                                style="width: 70px;" placeholder="20" min="0" max="100" step="any">
                            <button type="button" id="hh-modal-apply-cat-discount" class="btn btn-sm btn-primary">Apply</button>
                        </div>
                    </div>

                    {{-- Items grid --}}
                    <div class="hh-modal-items-grid mb-3" id="hh-modal-items-grid">
                        <div class="hh-empty-cat">Select a category above</div>
                    </div>

                    {{-- Selected items summary in modal --}}
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold" style="font-size: 15px;">Selected Items & Prices Overview:</span>
                            <button type="button" class="btn btn-link text-danger text-decoration-none p-0 fw-semibold" id="hh-modal-clear-all" style="font-size: 14px;">Clear All</button>
                        </div>
                        <div id="hh-modal-summary-wrap">
                            <table class="table hh-summary-table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Reg Price</th>
                                        <th style="width: 160px;">Happy Hour Price</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="hh-modal-summary-tbody">
                                    <tr>
                                        <td colspan="4" class="hh-summary-empty" id="hh-modal-summary-placeholder">
                                            No items selected yet
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-3 px-4 bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" style="font-size: 15px;" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary px-5 py-2 fw-semibold" style="font-size: 15px;" data-bs-dismiss="modal" id="hh-modal-apply-btn">Done</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Delete Modal ────────────────────────────── --}}
    <div class="modal fade" id="deleteHHModal" tabindex="-1" aria-labelledby="deleteHHLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteHHLabel">Delete Happy Hours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-img">
                        <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                    </div>
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you Sure?</h4>
                        <p class="text-center mt-3">Do you really want to delete?<br>This process cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-primary" id="deleteHHBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@php
    $hhCategoriesData = $categories->map(function($cat) {
        return [
            'id'    => $cat->id,
            'name'  => $cat->category_name,
            'items' => $cat->items->map(function($i) {
                $hasVars = ($i->is_variant || ($i->variants && $i->variants->count() > 0)) ? 1 : 0;
                return [
                    'id'         => $i->id,
                    'name'       => $i->name,
                    'price'      => floatval($i->price ?? 0),
                    'is_variant' => $hasVars,
                    'variants'   => $i->variants->map(function($v) {
                        return [
                            'id'       => $v->id,
                            'item_id'  => $v->item_id,
                            'name'     => $v->name,
                            'price'    => floatval($v->price ?? 0),
                        ];
                    })->values(),
                ];
            })->values(),
        ];
    })->values();
@endphp

@section('js')
<script>
    // Pass PHP data to JS
    var hhCreateUrl  = '{{ route('happy-hours-create') }}';
    var hhUpdateUrl  = '{{ route('happy-hours-edit', ':id') }}';
    var hhDetailUrl  = '{{ route('happy-hours-show', ':id') }}';

    // All categories + items + variants from PHP → JS
    var hhCategories       = @json($hhCategoriesData);
    var existingHappyHours = @json($happyHours);
    var oldItemsData       = @json($oldItems);
    var oldEditId          = @json($oldEditId);

    @if(Session::has('errors'))
        var isValidationError = true;
    @else
        var isValidationError = false;
    @endif
</script>

<script>
    // DataTable init
    $(document).ready(function () {
        $('#hh_table').DataTable({
            responsive: true,
            scrollX: true,
        });
    });
</script>

<script src="{{ asset('assets/js/happy-hours.js') }}"></script>
@endsection
