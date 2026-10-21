@extends('layouts.master')
@section('title')
    W-4 Form
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12">
                    <div class="main-heading">
                        <h4>Create {{ $user->name }} W-4 Form</h4>
                    </div>
                </div>
                @include('layouts.flash-msg')

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>There were some problems with your input:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="col-12">
                    <div class="main-content p-3">
                        <form action="{{ route('restaurant-payroll-settings-save', ['restaurantId' => $user->restaurant_id]) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Step 1: Filing Status -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Step 1: Filing Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Select Filing Status <span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input @error('filing_status') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="filing_status" 
                                                   id="filing_status_single" 
                                                   value="single"
                                                   {{ old('filing_status') == 'single' ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label" for="filing_status_single">
                                                Single or Married filing separately
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input @error('filing_status') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="filing_status" 
                                                   id="filing_status_married" 
                                                   value="married_joint"
                                                   {{ old('filing_status') == 'married_joint' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="filing_status_married">
                                                Married filing jointly or Qualifying surviving spouse
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input @error('filing_status') is-invalid @enderror" 
                                                   type="radio" 
                                                   name="filing_status" 
                                                   id="filing_status_head" 
                                                   value="head_of_household"
                                                   {{ old('filing_status') == 'head_of_household' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="filing_status_head">
                                                Head of household
                                            </label>
                                        </div>
                                        @error('filing_status')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Multiple Jobs -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Step 2: Multiple Jobs or Spouse Works</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small">Complete this step if you (1) hold more than one job at a time, or (2) are married filing jointly and your spouse also works.</p>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="multiple_jobs" 
                                               id="multiple_jobs" 
                                               value="1"
                                               {{ old('multiple_jobs') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="multiple_jobs">
                                            Check if this applies
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Claim Dependents -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Step 3: Claim Dependents</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="dependents_under_17" class="form-label">Number of qualifying children under age 17</label>
                                            <input type="number" 
                                                   class="form-control @error('dependents_under_17') is-invalid @enderror" 
                                                   id="dependents_under_17" 
                                                   name="dependents_under_17" 
                                                   min="0" 
                                                   value="{{ old('dependents_under_17', 0) }}">
                                            <small class="form-text text-muted">Multiply by $2,000</small>
                                            @error('dependents_under_17')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="dependents_other" class="form-label">Number of other dependents</label>
                                            <input type="number" 
                                                   class="form-control @error('dependents_other') is-invalid @enderror" 
                                                   id="dependents_other" 
                                                   name="dependents_other" 
                                                   min="0" 
                                                   value="{{ old('dependents_other', 0) }}">
                                            <small class="form-text text-muted">Multiply by $500</small>
                                            @error('dependents_other')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="dependents_credit_amount" class="form-label">Total Dependents Credit Amount ($)</label>
                                            <input type="number" 
                                                   class="form-control @error('dependents_credit_amount') is-invalid @enderror" 
                                                   id="dependents_credit_amount" 
                                                   name="dependents_credit_amount" 
                                                   step="0.01" 
                                                   min="0" 
                                                   value="{{ old('dependents_credit_amount', 0) }}">
                                            @error('dependents_credit_amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Other Adjustments -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Step 4: Other Adjustments (Optional)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="other_income" class="form-label">Other Income (not from jobs) ($)</label>
                                            <input type="number" 
                                                   class="form-control @error('other_income') is-invalid @enderror" 
                                                   id="other_income" 
                                                   name="other_income" 
                                                   step="0.01" 
                                                   min="0" 
                                                   value="{{ old('other_income', 0) }}">
                                            <small class="form-text text-muted">Interest, dividends, retirement income, etc.</small>
                                            @error('other_income')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="deductions" class="form-label">Deductions ($)</label>
                                            <input type="number" 
                                                   class="form-control @error('deductions') is-invalid @enderror" 
                                                   id="deductions" 
                                                   name="deductions" 
                                                   step="0.01" 
                                                   min="0" 
                                                   value="{{ old('deductions', 0) }}">
                                            <small class="form-text text-muted">If you expect to claim deductions other than the standard deduction</small>
                                            @error('deductions')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="extra_withholding" class="form-label">Extra Withholding ($)</label>
                                            <input type="number" 
                                                   class="form-control @error('extra_withholding') is-invalid @enderror" 
                                                   id="extra_withholding" 
                                                   name="extra_withholding" 
                                                   step="0.01" 
                                                   min="0" 
                                                   value="{{ old('extra_withholding', 0) }}">
                                            <small class="form-text text-muted">Additional amount per pay period</small>
                                            @error('extra_withholding')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
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
                                            <label for="signed_at" class="form-label">Date Signed <span class="text-danger">*</span></label>
                                            <input type="date" 
                                                   class="form-control @error('signed_at') is-invalid @enderror" 
                                                   id="signed_at" 
                                                   name="signed_at" 
                                                   value="{{ old('signed_at', date('Y-m-d')) }}"
                                                   required>
                                            @error('signed_at')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="effective_from" class="form-label">Effective From <span class="text-danger">*</span></label>
                                            <input type="date" 
                                                   class="form-control @error('effective_from') is-invalid @enderror" 
                                                   id="effective_from" 
                                                   name="effective_from" 
                                                   value="{{ old('effective_from', date('Y-m-d')) }}"
                                                   required>
                                            <small class="form-text text-muted">The date when this W-4 form becomes effective</small>
                                            @error('effective_from')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Scanned W-4 Form Upload -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Upload Scanned W-4 Form</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="w4_document" class="form-label">Scanned W-4 Document</label>
                                            <input type="file" 
                                                   class="form-control @error('w4_document') is-invalid @enderror" 
                                                   id="w4_document" 
                                                   name="w4_document"
                                                   accept=".pdf,.jpg,.jpeg,.png">
                                            <small class="form-text text-muted">Upload a scanned copy of the signed W-4 form (PDF, JPG, or PNG format, max 5MB)</small>
                                            @error('w4_document')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('restaurant-payroll-settings', ['restaurantId' => $user->restaurant_id]) }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save W-4 Form</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    // Auto-calculate dependents credit amount
    $('#dependents_under_17, #dependents_other').on('input', function() {
        const under17 = parseInt($('#dependents_under_17').val() || 0);
        const other = parseInt($('#dependents_other').val() || 0);
        const total = (under17 * 2000) + (other * 500);
        $('#dependents_credit_amount').val(total.toFixed(2));
    });
</script>
@endsection
