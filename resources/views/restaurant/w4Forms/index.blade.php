@extends('layouts.master')
@section('title', 'W-4 Forms')
@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="main-heading d-flex justify-content-between align-items-center">
                    <h4>W-4 Forms - {{ $user->name }}</h4>
                    <div>
                        <a href="{{ route('user-w4-form-create', ['id' => $user->id]) }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Add New W-4 Form
                        </a>
                        <a href="{{ route('user-list') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to Users
                        </a>
                    </div>
                </div>
            </div>
            @include('layouts.flash-msg')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>There were some problems:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="col-12">
                <div class="main-content p-3">
                    @if($w4Forms->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Effective From</th>
                                    <th>Filing Status</th>
                                    <th>Dependents</th>
                                    <th>Extra Withholding</th>
                                    <th>Signed Date</th>
                                    <th>Status</th>
                                    <th>Document</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($w4Forms as $form)
                                    <tr>
                                        <td>
                                            <strong>{{ $form->effective_from->format('M d, Y') }}</strong>
                                            @if($form->status)
                                                <span class="badge bg-success ms-2">Current</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($form->filing_status == 'single')
                                                Single/Married Filing Separately
                                            @elseif($form->filing_status == 'married_joint')
                                                Married Filing Jointly
                                            @else
                                                Head of Household
                                            @endif
                                        </td>
                                        <td>
                                            @if($form->dependents_under_17 > 0 || $form->dependents_other > 0)
                                                <div>
                                                    @if($form->dependents_under_17 > 0)
                                                        {{ $form->dependents_under_17 }} under 17<br>
                                                    @endif
                                                    @if($form->dependents_other > 0)
                                                        {{ $form->dependents_other }} other
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td>${{ number_format($form->extra_withholding, 2) }}</td>
                                        <td>{{ $form->signed_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="{{ $form->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $form->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}" alt="">
                                                <span>{{ $form->status ? 'Active' : 'Inactive' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($form->w4_payload && isset($form->w4_payload['document_path']))
                                                <a href="{{ asset($form->w4_payload['document_path']) }}" target="_blank" class="btn btn-sm btn-outline-info me-1" title="View">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                <a href="{{ route('user-w4-form-download', ['id' => $user->id, 'formId' => $form->id]) }}" class="btn btn-sm btn-outline-primary" title="Download">
                                                    <i class="bx bx-download"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('user-w4-form-show', ['id' => $user->id, 'formId' => $form->id]) }}" 
                                               class="btn btn-sm btn-info me-1" title="View Details">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('user-w4-form-toggle-status', ['id' => $user->id, 'formId' => $form->id]) }}" 
                                               class="btn btn-sm {{ $form->status ? 'btn-warning' : 'btn-success' }}" 
                                               title="{{ $form->status ? 'Deactivate' : 'Activate' }}"
                                               onclick="return confirm('Are you sure you want to {{ $form->status ? 'deactivate' : 'activate' }} this W-4 form?');">
                                                <i class="bx {{ $form->status ? 'bx-x-circle' : 'bx-check-circle' }}"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $w4Forms->links('pagination::bootstrap-4') }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle"></i> No W-4 forms found for this employee.
                            <a href="{{ route('user-w4-form-create', ['id' => $user->id]) }}">Create the first one</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
