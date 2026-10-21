@extends('layouts.master')
@section('title')
    Employees & Permissions
@endsection

@section('css')
<style>
    :root {
        --rp-bg: #f8fafc;
        --rp-panel: #ffffff;
        --rp-line: #e2e8f0;
        --rp-text: #1e293b;
        --rp-muted: #64748b;
        --rp-primary: #0980B2;
        --rp-primary-dark: #075f84;
    }

    .rp-container {
        padding: 18px 22px 85px;
        color: var(--rp-text);
    }

    /* Top Row */
    .rp-toprow {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .rp-title-area h1 {
        font-size: 25px;
        font-weight: 700;
        margin: 0;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .rp-title-area .badge {
        font-size: 13.5px !important;
        padding: 5px 12px !important;
        border-radius: 7px;
    }

    .rp-subtitle {
        font-size: 14.5px;
        color: var(--rp-muted);
        margin-top: 4px;
    }

    .rp-topright {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .rp-search-top {
        position: relative;
        min-width: 250px;
    }

    .rp-search-top input {
        width: 100%;
        padding: 9px 12px 9px 38px;
        border: 1px solid var(--rp-line);
        border-radius: 8px;
        background: #fff;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .rp-search-top input:focus {
        border-color: var(--rp-primary);
        outline: none;
        box-shadow: 0 0 0 2.5px rgba(9, 128, 178, 0.15);
    }

    .rp-search-top i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--rp-muted);
        font-size: 18px;
    }

    .rp-btn-action {
        background: var(--rp-primary);
        color: #fff;
        border: 0;
        border-radius: 8px;
        padding: 9px 15px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .rp-btn-action:hover {
        background: var(--rp-primary-dark);
        color: #fff;
    }

    .rp-btn-outline {
        background: #fff;
        color: #334155;
        border: 1px solid var(--rp-line);
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .rp-btn-outline:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .btn-role-templates {
        background: transparent;
        color: var(--rp-primary);
        border: 1.5px solid var(--rp-primary);
        border-radius: 9px;
        padding: 9px 15px;
        font-weight: 600;
        font-size: 13.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .btn-role-templates:hover,
    .btn-role-templates:focus {
        background: var(--rp-primary);
        color: #fff !important;
        border-color: var(--rp-primary);
        box-shadow: 0 2px 6px rgba(9, 128, 178, 0.25);
    }

    .btn-role-templates:hover i,
    .btn-role-templates:focus i {
        color: #fff !important;
    }

    /* Role Cards */
    .rolecards-container {
        margin-bottom: 14px;
    }

    .rolecards {
        display: flex;
        gap: 9px;
        overflow-x: auto;
        padding: 4px 4px 8px 4px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .rolecards::-webkit-scrollbar {
        height: 6px;
    }

    .rolecards::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .rolecard {
        background: #fff;
        border: 1.5px solid var(--rp-line);
        border-radius: 11px;
        padding: 10px 12px;
        text-align: center;
        cursor: pointer;
        min-width: 110px;
        flex: 1 0 auto;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        user-select: none;
    }

    .rolecard:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1;
    }

    .rolecard.active {
        outline: none;
        border-color: var(--rp-primary);
        background: #f0f9ff;
        box-shadow: 0 0 0 1.5px var(--rp-primary), 0 4px 14px rgba(9, 128, 178, 0.16);
    }

    .roleicon {
        font-size: 25px;
        line-height: 1;
        display: block;
        margin-bottom: 5px;
    }

    .rolename {
        font-weight: 700;
        font-size: 14px;
        color: #1e293b;
        white-space: nowrap;
    }

    .rolecount {
        display: inline-block;
        font-size: 11.5px;
        border-radius: 999px;
        padding: 2.5px 8px;
        margin-top: 4.5px;
        background: #f1f5f9;
        font-weight: 600;
        color: #475569;
    }

    .grantcount {
        font-size: 10.5px;
        color: #64748b;
        margin-top: 3.5px;
        font-weight: 500;
    }

    /* Filters Bar */
    .rp-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        background: #fff;
        border: 1px solid var(--rp-line);
        border-radius: 11px;
        padding: 11px 16px;
        margin-bottom: 12px;
    }

    .rp-filters select,
    .rp-filters input[type="text"] {
        padding: 8px 12px;
        border: 1px solid var(--rp-line);
        border-radius: 7px;
        background: #fff;
        font-size: 13.5px;
        color: #334155;
    }

    .rp-switchrow {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 13.5px;
        color: #334155;
        white-space: nowrap;
        margin-left: auto;
    }

    .rp-custom-switch {
        position: relative;
        display: inline-block;
        width: 41px;
        height: 23px;
    }

    .rp-custom-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .rp-switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .2s;
        border-radius: 20px;
    }

    .rp-switch-slider:before {
        position: absolute;
        content: "";
        height: 17px;
        width: 17px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .2s;
        border-radius: 50%;
    }

    .rp-custom-switch input:checked + .rp-switch-slider {
        background-color: var(--rp-primary);
    }

    .rp-custom-switch input:checked + .rp-switch-slider:before {
        transform: translateX(18px);
    }

    /* Hint & Legend Bar */
    .rp-hintbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #eef6ff;
        border: 1px solid #cfe5ff;
        color: #1e40af;
        border-radius: 10px;
        padding: 9px 15px;
        font-size: 13.5px;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 9px;
    }

    .rp-legend {
        display: flex;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
    }

    .rp-dot {
        width: 11.5px;
        height: 11.5px;
        border-radius: 3.5px;
        display: inline-block;
        margin-right: 4.5px;
        vertical-align: middle;
    }

    .rp-dot.cust {
        background: #dbeafe;
        border: 1px solid #93c5fd;
    }

    /* Table Container */
    .tablewrap {
        background: #fff;
        border: 1px solid var(--rp-line);
        border-radius: 11px;
        overflow: auto;
        height: calc(100vh - 300px);
        min-height: 420px;
        position: relative;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    table.rp-table {
        border-collapse: collapse;
        width: max-content;
        min-width: 100%;
    }

    .rp-table th,
    .rp-table td {
        border-bottom: 1px solid #edf1f5;
        border-right: 1px solid #edf1f5;
        padding: 8.5px 12px;
        text-align: center;
        font-size: 13.5px;
        white-space: nowrap;
        vertical-align: middle;
    }

    /* Sticky Headers */
    .rp-table thead tr.header-group-row th {
        position: sticky;
        top: 0;
        background: #f1f5f9;
        z-index: 5;
        border-bottom: 1px solid #cbd5e1;
        box-sizing: border-box;
    }

    /* Permission Group Headers (Tier 1) */
    .rp-table thead tr.header-group-row th.perm-group-th {
        padding: 6px 3px !important;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        text-align: center;
        background: #f1f5f9;
        border-right: 2px solid #cbd5e1;
        cursor: pointer;
        transition: background 0.15s;
        box-sizing: border-box !important;
        vertical-align: middle;
    }

    .rp-table thead tr.header-group-row th.perm-group-th:hover {
        background: #e2e8f0;
    }

    .rp-table thead tr.header-group-row th.perm-group-th.single-col-group,
    .rp-table thead tr.header-group-row th.perm-group-th[colspan="1"] {
        width: 85px !important;
        min-width: 85px !important;
        max-width: 85px !important;
        padding: 5px 2px !important;
    }

    .perm-group-header-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 2px;
        line-height: 1.15;
        text-align: center;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .perm-group-header-content .group-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 12px;
        letter-spacing: 0.01em;
        word-break: break-word;
        overflow-wrap: anywhere;
        white-space: normal;
        text-align: center;
        max-width: 100%;
        line-height: 1.2;
    }

    .rp-table thead tr.header-perm-row th {
        position: sticky;
        top: 36px;
        background: #f8fafc;
        z-index: 4;
        border-bottom: 2px solid #cbd5e1;
        box-sizing: border-box;
    }

    /* Sticky First Column: Employee & Role (Strict 250px on all rows) */
    .rp-table thead th.sticky-col-header,
    .rp-table tbody td:first-child {
        position: sticky;
        left: 0;
        width: 250px !important;
        min-width: 250px !important;
        max-width: 250px !important;
        box-sizing: border-box !important;
        text-align: left;
        border-right: 2px solid #cbd5e1 !important;
        box-shadow: 3px 0 5px -2px rgba(0,0,0,0.08);
    }

    .rp-table thead th.sticky-col-header {
        z-index: 12 !important;
        background: #f8fafc !important;
        vertical-align: middle;
        padding: 6px 12px !important;
    }

    .rp-table thead th.sticky-col-row1 {
        top: 0;
        border-bottom: 1px solid #edf1f5 !important;
        font-weight: 700;
        font-size: 13px;
        color: #0f172a;
    }

    .rp-table thead th.sticky-col-row2 {
        top: 48px;
        border-bottom: 2px solid #cbd5e1 !important;
        font-size: 11px;
        color: #64748b;
    }

    .rp-table tbody td:first-child {
        z-index: 3;
        background: #fff !important;
        padding: 8.5px 12px !important;
    }

    /* Sticky Actions Column */
    .rp-table thead th.sticky-actions-header,
    .rp-table tbody td.actions-col {
        position: sticky;
        right: 0;
        width: 105px !important;
        min-width: 105px !important;
        max-width: 105px !important;
        box-sizing: border-box !important;
        border-left: 2px solid #cbd5e1 !important;
        box-shadow: -3px 0 5px -2px rgba(0,0,0,0.08);
        text-align: center;
    }

    .rp-table thead th.sticky-actions-header {
        z-index: 12 !important;
        background: #f8fafc !important;
        vertical-align: middle;
    }

    .rp-table thead th.sticky-actions-row1 {
        top: 0;
        border-bottom: 1px solid #edf1f5 !important;
        font-weight: 700;
        font-size: 13px;
        color: #0f172a;
    }

    .rp-table thead th.sticky-actions-row2 {
        top: 48px;
        border-bottom: 2px solid #cbd5e1 !important;
    }

    .rp-table tbody td.actions-col {
        z-index: 3;
        background: #fff !important;
    }

    /* Group Boundary Divider */
    .group-end {
        border-right: 2px solid #cbd5e1 !important;
    }

    .rolepills-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        align-items: center;
    }

    /* Permission Column Headers - Identical Uniform Width */
    .permhead {
        width: 85px !important;
        min-width: 85px !important;
        max-width: 85px !important;
        box-sizing: border-box !important;
        white-space: normal;
        line-height: 1.2;
        font-size: 11px;
        padding: 6px 3px !important;
        cursor: pointer;
        transition: background 0.15s;
        text-align: center;
        vertical-align: middle;
    }

    .permhead:hover {
        background: #e2e8f0;
    }

    .perm-name-section {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 79px;
        max-width: 79px;
        margin: 0 auto;
    }

    .perm-display-name {
        font-weight: 600;
        color: #334155;
        font-size: 12px;
        line-height: 1.25;
        white-space: normal;
        word-break: normal;
        overflow-wrap: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-align: center;
        width: 79px;
        max-width: 79px;
        height: 2.5em;
    }

    .rp-table td.perm-td {
        width: 85px !important;
        min-width: 85px !important;
        max-width: 85px !important;
        box-sizing: border-box !important;
        padding: 7px 3px !important;
        text-align: center;
    }

    .perm-badge-group {
        display: block;
        font-size: 10px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .perm-name-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
    }

    .perm-display-name {
        font-weight: 600;
        color: #1e293b;
        width: 100%;
        line-height: 1.25;
    }

    .perm-guard-badge {
        display: inline-block;
        font-size: 9.5px;
        font-weight: 700;
        padding: 1.5px 7px;
        border-radius: 999px;
        letter-spacing: 0.02em;
        line-height: 1.2;
    }

    .perm-guard-badge.tag-pos {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .perm-guard-badge.tag-backoffice {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    /* Employee Row Styling */
    .emp-row:hover td {
        background-color: #f8fafc;
    }

    .emp-row.is-modified td:first-child {
        border-left: 3.5px solid var(--rp-primary);
    }

    .emp-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #0f172a;
        color: #fff;
        display: inline-grid;
        place-items: center;
        font-weight: 700;
        font-size: 12.5px;
        flex-shrink: 0;
    }

    .emp-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .emp-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
    }

    .emp-name {
        font-weight: 600;
        color: #0f172a;
        font-size: 13.5px;
        line-height: 1.25;
    }

    .modified-pill {
        display: inline-block;
        font-size: 9.5px;
        background: #dbeafe;
        color: #1e40af;
        border-radius: 4px;
        padding: 1px 5px;
        margin-left: 6px;
        font-weight: 600;
    }

    .rolepill {
        display: inline-block;
        border-radius: 999px;
        padding: 2px 7.5px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
        line-height: 1.35;
    }

    /* Checkboxes */
    .chk-perm {
        width: 19.5px;
        height: 19.5px;
        accent-color: var(--rp-primary);
        cursor: pointer;
        margin: 0;
        vertical-align: middle;
    }

    /* Cell Modifiers */
    .customized-cell {
        background-color: #f0f7ff !important;
        position: relative;
    }

    .customized-cell:after {
        content: "";
        position: absolute;
        top: 3.5px;
        right: 3.5px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: var(--rp-primary);
    }

    /* Action Buttons */
    .btn-reset-row {
        border: 1px solid var(--rp-line);
        background: #fff;
        color: #475569;
        border-radius: 7px;
        padding: 5px 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-reset-row:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    /* Save Bar */
    .savebar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border: 1px solid var(--rp-line);
        border-radius: 11px;
        padding: 11px 18px;
        margin-top: 12px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        flex-wrap: wrap;
        gap: 14px;
    }

    .savebar-actions {
        display: flex;
        gap: 11px;
        align-items: center;
    }

    .btn-save-primary {
        background: var(--rp-primary);
        color: #fff;
        border: 1px solid var(--rp-primary);
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: background 0.2s;
    }

    .btn-save-primary:hover {
        background: var(--rp-primary-dark);
        color: #fff;
    }

    .btn-save-primary.disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-reset-all {
        border: 1px solid var(--rp-line);
        background: #fff;
        color: #475569;
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.2s;
    }

    .btn-reset-all:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Empty state */
    .rp-empty-state {
        padding: 42px 20px;
        text-align: center;
        color: var(--rp-muted);
        font-size: 14.5px;
    }
    .rp-empty-state i {
        font-size: 45px;
        margin-bottom: 11px;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="rp-container">
        <!-- Top Heading Row -->
        <div class="rp-toprow">
            <div class="rp-title-area">
                <h1>
                    <span>Employees &amp; Permissions</span>
                    <span class="badge bg-primary fs-6" style="background-color: #0980B2 !important;">{{ count($formattedEmployees) }} users</span>
                </h1>
                <div class="rp-subtitle">
                    Manage direct employee access with role-synchronized defaults and customized permissions.
                </div>
            </div>
            <div class="rp-topright">
                <div class="rp-search-top">
                    <i class="bx bx-search"></i>
                    <input type="text" id="search-employee" placeholder="Search employees by name..." />
                </div>
                <button type="button" class="btn-role-templates" data-bs-toggle="modal" data-bs-target="#roleDefaultsModal">
                    <i class="bx bx-shield-quarter"></i> Role Templates
                </button>
                <a href="{{ route('role-list') }}" class="rp-btn-action">
                    <i class="bx bx-plus"></i> Add Role
                </a>
            </div>
        </div>

        @include('layouts.flash-msg')
        @include('layouts.validation-error-msg')

        <!-- Role Filter Cards Row -->
        <div class="rolecards-container">
            <div class="rolecards">
                <div class="rolecard active" data-role-slug="all">
                    <div class="roleicon">🗂️</div>
                    <div class="rolename">All Roles</div>
                    <div class="rolecount">{{ count($formattedEmployees) }} users</div>
                    <div class="grantcount">{{ $totalPermissionsCount }} perms</div>
                </div>

                @foreach ($formattedRoles as $role)
                    <div class="rolecard" data-role-slug="{{ $role['slug'] }}" data-role-id="{{ $role['id'] }}">
                        <div class="roleicon" style="color: {{ $role['color'] }}">{{ $role['icon'] }}</div>
                        <div class="rolename">{{ $role['name'] }}</div>
                        <div class="rolecount" style="color: {{ $role['color'] }}; background: {{ $role['bg'] }}; border: 1px solid {{ $role['border'] }}33;">
                            {{ $role['user_count'] }} users
                        </div>
                        <div class="grantcount">{{ $role['grant_count'] }} granted</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Filter & Toolbar Controls -->
        <div class="rp-filters">
            <div>
                <select id="filter-role" class="form-select" style="min-width: 155px;">
                    <option value="all">All Roles</option>
                    @foreach ($formattedRoles as $role)
                        <option value="{{ $role['slug'] }}">{{ $role['name'] }} ({{ $role['user_count'] }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select id="filter-guard" class="form-select" style="min-width: 175px;">
                    <option value="all">All Modules (POS & Back-office)</option>
                    <option value="api">POS</option>
                    <option value="web">Back-office</option>
                </select>
            </div>

            <div>
                <select id="filter-category" class="form-select" style="min-width: 210px;">
                    <option value="all" data-category="all" data-guard="all">All Categories ({{ count($categories) }})</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat['key'] }}"
                                data-category="{{ $cat['group_name'] }}"
                                data-guard="{{ $cat['guard_name'] }}">
                            {{ $cat['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="rp-switchrow">
                <label class="rp-custom-switch">
                    <input type="checkbox" id="toggle-modified-only">
                    <span class="rp-switch-slider"></span>
                </label>
                <span>Show Customized Only</span>
            </div>
        </div>

        <!-- Hint & Legend Bar -->
        <div class="rp-hintbar">
            <div>
                <span>Showing <b id="visible-emp-count">{{ count($formattedEmployees) }}</b> employees across <b id="visible-perm-count">{{ count($formattedPermissions) }}</b> permissions.</span>
                <span class="ms-2 text-muted">Direct permissions are active for authorization.</span>
            </div>
            <div class="rp-legend">
                <span><span class="rp-dot cust"></span> Customized</span>
            </div>
        </div>

        <!-- Main Matrix Table -->
        <div class="tablewrap" id="permission-table-container">
            <table class="rp-table">
                <thead>
                    <!-- Row 1: Permission Group Headers -->
                    <tr class="header-group-row">
                        <th class="sticky-col-header sticky-col-row1">Employee &amp; Role ↕</th>
                        @foreach ($permissionGroups as $group)
                            @php
                                $permCount = count($group['permissions']);
                            @endphp
                            <th colspan="{{ $permCount }}"
                                class="perm-group-th group-end {{ $permCount === 1 ? 'single-col-group' : '' }}"
                                data-category="{{ $group['group_name'] }}"
                                data-guard="{{ $group['guard_name'] }}"
                                title="Click to toggle all {{ $group['group_name'] }} permissions for visible employees">
                                <div class="perm-group-header-content">
                                    <span class="group-title">{{ $group['group_name'] }}</span>
                                    <span class="perm-guard-badge {{ $group['guard_name'] === 'api' ? 'tag-pos' : 'tag-backoffice' }}">
                                        {{ $group['guard_label'] }}
                                    </span>
                                </div>
                            </th>
                        @endforeach
                        <th class="actions-col sticky-actions-header sticky-actions-row1">Actions</th>
                    </tr>

                    <!-- Row 2: Individual Permission Column Headers -->
                    <tr class="header-perm-row">
                        <th class="sticky-col-header sticky-col-row2">
                            <span class="text-muted" style="font-size: 10.5px; font-weight: 500;">Role &amp; Direct Access</span>
                        </th>
                        @foreach ($formattedPermissions as $perm)
                            @php
                                $isApi = strtolower($perm['guard_name'] ?? '') === 'api';
                                $guardLabel = $perm['guard_label'] ?? ($isApi ? 'POS' : 'Back-office');
                            @endphp
                            <th class="permhead perm-th btn-toggle-col {{ !empty($perm['is_last_in_group']) ? 'group-end' : '' }}"
                                data-category="{{ $perm['group_name'] }}"
                                data-guard="{{ $perm['guard_name'] }}"
                                data-perm-id="{{ $perm['id'] }}"
                                title="Click to toggle {{ $perm['display_name'] }} for all visible employees">
                                <div class="perm-name-section" title="{{ $perm['display_name'] }} ({{ $guardLabel }})">
                                    <div class="perm-display-name">{{ $perm['display_name'] }}</div>
                                </div>
                            </th>
                        @endforeach
                        <th class="actions-col sticky-actions-header sticky-actions-row2"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($formattedEmployees as $emp)
                        <tr class="emp-row @foreach($emp['role_slugs'] as $s) r-{{ $s }} @endforeach {{ $emp['is_modified'] ? 'is-modified' : '' }}"
                            data-user-id="{{ $emp['id'] }}"
                            data-user-name="{{ $emp['name'] }}"
                            data-user-roles="{{ implode(' ', $emp['roles_list']) }}"
                            data-role-ids="{{ implode(',', $emp['role_ids']) }}">
                            
                            <!-- Merged Column: Employee & Role -->
                            <td>
                                <div class="emp-info">
                                    <div class="emp-avatar">{{ $emp['initials'] }}</div>
                                    <div class="emp-details">
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="emp-name">{{ $emp['name'] }}</span>
                                            <span class="modified-pill {{ $emp['is_modified'] ? '' : 'd-none' }}">Customized</span>
                                        </div>
                                        <div class="rolepills-wrap mt-1">
                                            @forelse ($emp['roles_info'] as $rInfo)
                                                <span class="rolepill" style="background: {{ $rInfo['bg'] }}; color: {{ $rInfo['color'] }}; border: 1px solid {{ $rInfo['border'] }}44;" title="{{ $rInfo['name'] }}">
                                                    {{ $rInfo['name'] }}
                                                </span>
                                            @empty
                                                <span class="rolepill text-muted" style="background: #f1f5f9; color: #64748b;">
                                                    No Role
                                                </span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Permission Checkbox Columns -->
                            @foreach ($formattedPermissions as $perm)
                                @php
                                    $hasPerm = in_array($perm['id'], $emp['direct_permissions']);
                                    $inRoleDefault = in_array($perm['id'], $emp['role_default_ids']);
                                    $isCustomCell = ($hasPerm !== $inRoleDefault);
                                    
                                    $cellClass = 'perm-td' . ($isCustomCell ? ' customized-cell' : '');
                                @endphp
                                <td class="{{ $cellClass }} {{ !empty($perm['is_last_in_group']) ? 'group-end' : '' }}"
                                    data-category="{{ $perm['group_name'] }}"
                                    data-guard="{{ $perm['guard_name'] }}"
                                    data-perm-id="{{ $perm['id'] }}"
                                    title="{{ $perm['display_name'] }} ({{ $perm['group_name'] }})">
                                    <input type="checkbox"
                                           class="chk-perm"
                                           name="permissions[{{ $emp['id'] }}][]"
                                           value="{{ $perm['id'] }}"
                                           {{ $hasPerm ? 'checked' : '' }}>
                                </td>
                            @endforeach

                            <!-- Actions Column: Reset Button -->
                            <td class="actions-col">
                                <button type="button" class="btn-reset-row" title="Reset this employee to role defaults">
                                    Reset
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-state-row">
                            <td colspan="{{ count($formattedPermissions) + 2 }}" class="rp-empty-state">
                                <i class="bx bx-user-x"></i>
                                <h5>No employees found</h5>
                                <p>No employee records match the selected role or search criteria.</p>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="empty-state-row" style="display: none;">
                        <td colspan="{{ count($formattedPermissions) + 2 }}" class="rp-empty-state">
                            <i class="bx bx-search-alt"></i>
                            <h5>No matching employees</h5>
                            <p>Try clearing your search query or choosing another role filter.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bottom Sticky Save & Reset Bar -->
        <div class="savebar">
            <div>
                <span id="unsaved-badge" class="badge bg-warning text-dark d-none px-2 py-1">
                    0 employees modified
                </span>
                <span class="text-muted ms-2" style="font-size: 13.5px;" id="save-hint">Direct permissions synchronize automatically to POS.</span>
            </div>
            <div class="savebar-actions">
                <button type="button" class="btn-reset-all" id="btn-reset-defaults">
                    <i class="bx bx-reset"></i> Reset to Role Defaults
                </button>
                <button type="button" class="btn-save-primary disabled" id="btn-save-changes" disabled>
                    <i class="bx bx-check-circle"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Role Default Templates Configuration -->
<div class="modal fade" id="roleDefaultsModal" tabindex="-1" aria-labelledby="roleDefaultsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 12px; border: 1px solid var(--rp-line); box-shadow: 0 10px 30px rgba(0,0,0,0.12);">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid var(--rp-line); padding: 16px 22px;">
                <div>
                    <h5 class="modal-title fw-bold" id="roleDefaultsModalLabel" style="color: #0f172a; margin: 0; font-size: 17px;">
                        <i class="bx bx-shield-quarter me-1" style="color: var(--rp-primary);"></i> Role Permission Templates
                    </h5>
                    <small class="text-muted" style="font-size: 12.5px;">Configure default permissions inherited when a role is assigned to employees.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 22px;">
                {!! Form::open(['route' => 'role-permission.store', 'method' => 'POST', 'id' => 'update-role-template-form']) !!}
                <input type="hidden" name="sync_existing_users" id="modal-sync-existing-input" value="0">
                <div class="row g-3 align-items-end mb-4 pb-3 border-bottom">
                    <div class="col-md-5">
                        <label class="form-label fw-bold" style="font-size: 13.5px;">Select Role Template:</label>
                        <select name="roles" id="modal-select-role" class="form-select" required style="border-radius: 8px; font-size: 13.5px;">
                            <option value="">-- Choose Role --</option>
                            @foreach ($formattedRoles as $r)
                                <option value="{{ $r['name'] }}">{{ $r['icon'] }} {{ $r['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-7 text-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm d-none" id="modal-apply-defaults-btn" style="border-radius: 8px; font-weight: 600;">
                            <i class="bx bx-import me-1"></i> Apply System Defaults
                        </button>
                    </div>
                </div>

                <div id="modal-loading" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted" style="font-size: 13.5px;">Loading role permissions...</p>
                </div>

                <div id="role-modal-checkboxes">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #0980B2; font-size: 15px;">
                                <i class="bx bx-desktop"></i> Back-office Permissions
                            </h6>
                            @foreach ($formattedPermissions->where('guard_name', 'web')->groupBy('group_name') as $group => $perms)
                                <div class="card mb-3 shadow-none border" style="border-radius: 9px; overflow: hidden;">
                                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                        <b class="text-dark" style="font-size: 13px;">{{ $group }}</b>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none select-group-toggle" style="font-size: 11.5px; color: var(--rp-primary);">Select All</button>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-2">
                                            @foreach ($perms as $p)
                                                <div class="col-md-6">
                                                    <div class="form-check" style="font-size: 12.5px;">
                                                        <input class="form-check-input modal-perm-chk" type="checkbox" name="permission[]" value="{{ $p['id'] }}" id="modal_perm_{{ $p['id'] }}">
                                                        <label class="form-check-label text-truncate ms-1" for="modal_perm_{{ $p['id'] }}" title="{{ $p['display_name'] }}" style="cursor: pointer;">
                                                            {{ $p['display_name'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="col-lg-6">
                            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #059669; font-size: 15px;">
                                <i class="bx bx-mobile-alt"></i> POS Permissions
                            </h6>
                            @foreach ($formattedPermissions->where('guard_name', 'api')->groupBy('group_name') as $group => $perms)
                                <div class="card mb-3 shadow-none border" style="border-radius: 9px; overflow: hidden;">
                                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                        <b class="text-dark" style="font-size: 13px;">{{ $group }}</b>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none select-group-toggle" style="font-size: 11.5px; color: #059669;">Select All</button>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-2">
                                            @foreach ($perms as $p)
                                                <div class="col-md-6">
                                                    <div class="form-check" style="font-size: 12.5px;">
                                                        <input class="form-check-input modal-perm-chk" type="checkbox" name="permission[]" value="{{ $p['id'] }}" id="modal_perm_{{ $p['id'] }}">
                                                        <label class="form-check-label text-truncate ms-1" for="modal_perm_{{ $p['id'] }}" title="{{ $p['display_name'] }}" style="cursor: pointer;">
                                                            {{ $p['display_name'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-0 pb-0 mt-3" style="border-top: 1px solid var(--rp-line); padding-top: 15px;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="border-radius: 7px; padding: 7px 15px;">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-save-role-template" style="border-radius: 7px; padding: 7px 16px; background-color: var(--rp-primary); border-color: var(--rp-primary); font-weight: 600;">
                        <i class="bx bx-save me-1"></i> Save Role Template
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    window.loadRolePermissionUrl = '{{ route('load-role-permission') }}';
    window.loadDefaultPermissionUrl = '{{ route('load-default-permissions') }}';
    window.saveUserPermissionsUrl = '{{ route('role-permission.save-users') }}';
    window.resetUserPermissionUrl = '{{ route('role-permission.reset-user') }}';
    window.resetAllUsersPermissionUrl = '{{ route('role-permission.reset-all') }}';
    window.roleDefaultPermissionsMap = @json($roleDefaultsMap);
</script>
<script src="{{ asset('assets/js/role-permission.js') }}"></script>
@endsection
