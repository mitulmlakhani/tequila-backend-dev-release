@extends('layouts.master')
@section('title', 'W-4 Form Details')
@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="main-heading d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">W-4 Form Details - {{ $user->name }}</h4>
                        <span class="badge {{ $w4Form->status ? 'bg-success' : 'bg-secondary' }} mt-2">
                            {{ $w4Form->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <a href="{{ route('user-w4-form-toggle-status', ['id' => $user->id, 'formId' => $w4Form->id]) }}" 
                           class="btn {{ $w4Form->status ? 'btn-warning' : 'btn-success' }}"
                           onclick="return confirm('Are you sure you want to {{ $w4Form->status ? 'deactivate' : 'activate' }} this W-4 form?');">
                            <i class="bx {{ $w4Form->status ? 'bx-x-circle' : 'bx-check-circle' }}"></i>
                            {{ $w4Form->status ? 'Deactivate' : 'Activate' }}
                        </a>
                        <a href="{{ route('user-w4-forms', ['id' => $user->id]) }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to W-4 Forms
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="main-content p-4">
                    <!-- Employee Information -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Employee Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> {{ $user->name }}</p>
                                    <p><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Phone:</strong> {{ $user->mobile ?? 'N/A' }}</p>
                                    <p><strong>SSN:</strong> {{ $user->ssn_number ?: 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Filing Status -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Step 1: Filing Status</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">
                                <strong>
                                    @if($w4Form->filing_status == 'single')
                                        Single or Married filing separately
                                    @elseif($w4Form->filing_status == 'married_joint')
                                        Married filing jointly or Qualifying surviving spouse
                                    @else
                                        Head of household
                                    @endif
                                </strong>
                            </p>
                        </div>
                    </div>

                    <!-- Step 2: Multiple Jobs -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Step 2: Multiple Jobs or Spouse Works</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">
                                @if($w4Form->multiple_jobs)
                                    <i class="bx bx-check-circle text-success"></i> Yes - Employee has multiple jobs or spouse works
                                @else
                                    <i class="bx bx-x-circle text-muted"></i> No - Single job only
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Step 3: Claim Dependents -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Step 3: Claim Dependents</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Qualifying children under 17:</strong></p>
                                    <p class="fs-4 text-primary">{{ $w4Form->dependents_under_17 }}</p>
                                    <small class="text-muted">× $2,000</small>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Other dependents:</strong></p>
                                    <p class="fs-4 text-primary">{{ $w4Form->dependents_other }}</p>
                                    <small class="text-muted">× $500</small>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Total Dependents Credit:</strong></p>
                                    <p class="fs-4 text-success">${{ number_format($w4Form->dependents_credit_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Other Adjustments -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Step 4: Other Adjustments</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Other Income:</strong></p>
                                    <p class="fs-5">${{ number_format($w4Form->other_income, 2) }}</p>
                                    <small class="text-muted">Interest, dividends, retirement income, etc.</small>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Deductions:</strong></p>
                                    <p class="fs-5">${{ number_format($w4Form->deductions, 2) }}</p>
                                    <small class="text-muted">Other than standard deduction</small>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Extra Withholding:</strong></p>
                                    <p class="fs-5">${{ number_format($w4Form->extra_withholding, 2) }}</p>
                                    <small class="text-muted">Additional amount per pay period</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Sign and Date -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Step 5: Sign and Date</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Date Signed:</strong></p>
                                    <p class="fs-5">{{ $w4Form->signed_at->format('F d, Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Effective From:</strong></p>
                                    <p class="fs-5">{{ $w4Form->effective_from->format('F d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Uploaded Document -->
                    @if($w4Form->w4_payload && isset($w4Form->w4_payload['document_path']))
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">Scanned W-4 Document</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>Document Name:</strong> {{ $w4Form->w4_payload['document_name'] ?? 'W-4 Form' }}</p>
                                    <p><strong>Uploaded:</strong> {{ \Carbon\Carbon::parse($w4Form->w4_payload['uploaded_at'])->format('F d, Y g:i A') }}</p>
                                    
                                    @php
                                        $extension = pathinfo($w4Form->w4_payload['document_path'], PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                        <div class="mt-3">
                                            <img src="{{ asset($w4Form->w4_payload['document_path']) }}" 
                                                 alt="W-4 Form" 
                                                 class="img-fluid border" 
                                                 style="max-height: 600px;">
                                        </div>
                                    @elseif(strtolower($extension) == 'pdf')
                                        <div class="mt-3">
                                            <iframe src="{{ asset($w4Form->w4_payload['document_path']) }}" 
                                                    width="100%" 
                                                    height="600px" 
                                                    class="border"></iframe>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <a href="{{ route('user-w4-form-download', ['id' => $user->id, 'formId' => $w4Form->id]) }}" 
                                           class="btn btn-primary">
                                            <i class="bx bx-download"></i> Download Document
                                        </a>
                                        <a href="{{ asset($w4Form->w4_payload['document_path']) }}" 
                                           target="_blank" 
                                           class="btn btn-outline-primary ms-2">
                                            <i class="bx bx-link-external"></i> Open in New Tab
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Form Metadata -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Form Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Form ID:</strong> #{{ $w4Form->id }}</p>
                                    <p><strong>Created:</strong> {{ $w4Form->created_at->format('F d, Y g:i A') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Last Updated:</strong> {{ $w4Form->updated_at->format('F d, Y g:i A') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Status:</strong></p>
                                    <div class="{{ $w4Form->status ? 'reserved' : 'pending' }}">
                                        <img src="{{ $w4Form->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}" alt="">
                                        <span>{{ $w4Form->status ? 'Active' : 'Inactive' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
