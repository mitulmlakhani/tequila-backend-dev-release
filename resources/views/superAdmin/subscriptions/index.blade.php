@extends('layouts.master')
@section('title')
    Restaurant Subscriptions
@endsection
@section('content')
                    <!--Main Section Start-->
                    <div class="wrapper home-section" id="full-width">
                        <div class="container-fluid p-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="main-heading">
                                        <h4>Restaurant Subscriptions</h4>
                                    </div>
                                </div>
                                @include('layouts.flash-msg')
                                <div class="col-12">
                                    <div class="main-content p-3">
                                        @if (in_array($currentStatus, ['active', 'suspended']))
                                            <form action="{{ route('subscription-update', ['restaurantId' => $restaurantId, 'subscriptionId' => $subscription->id]) }}" method="POST">
                                        @else
                                            <form action="{{ route('subscription-subscribe', ['restaurantId' => $restaurantId]) }}" method="POST">
                                        @endif
                                                @csrf
                                                <div class="row">
                                                <div class="col-12">
                                                    <div class="container py-4">
                                                        <div class="card shadow-sm border-0">
                                                            <div class="card-header bg-primary text-white py-3">
                                                                <h5 class="mb-0 fw-bold">Select Your Plan Features</h5>
                                                            </div>

                                                            <div class="card-body bg-white border-bottom">
                                                                <div class="row g-3">
                                                                    <div class="col-md-4">
                                                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Current Plan Status</small>
                                                                        <p class="h6 mb-0 fw-bold text-{{ $currentStatus == "active" ? "success" : "danger" }}">{{ ucwords($currentStatus) }}</p>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Renewal Date</small>
                                                                        @php 
                                                                        $className = "";
    if ($subscription) {
        $nBilling = \Carbon\Carbon::parse($subscription?->next_billing_date);
        if ($nBilling->isPast()) {
            $className = "text-danger";
        } else if ($nBilling->diffInDays() <= 7) {
            $className = "text-warning";
        } else {
            $className = "text-success";
        }
    }
                                                                        @endphp
                                                                        <p class="h6 mb-0 {{ $className }}">{{ $subscription?->next_billing_date ? $nBilling->format('M d, Y') : 'N/A' }}</p>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Current Monthly Billing</small>
                                                                        <p class="h6 mb-0 text-primary fw-bold">{{ $subscription?->amount ? '$' . $subscription?->amount : 'N/A' }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="card-body p-0">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover align-middle mb-0">
                                                                        <thead class="table-light">
                                                                            <tr>
                                                                                <th class="ps-4" style="width: 80px;">Status</th>
                                                                                <th>Plan Name</th>
                                                                                <th>Base Price</th>
                                                                                <th>Qty</th>
                                                                                <th class="pe-4" style="width: 200px;">Custom Price</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($posFeatures as $posFeature)
                                                                                <tr>
                                                                                    <td class="ps-4">
                                                                                        <div class="form-check d-flex justify-content-center">
                                                                                            <input class="form-check-input border-secondary custom_input_checkbox" 
                                                                                                type="checkbox" 
                                                                                                name="pos_features[{{ $posFeature->id }}]" 
                                                                                                value="{{ $posFeature->id }}" 
                                                                                                {{ $posFeature->is_default ? 'checked disabled' : '' }}
                                                                                                {{ $posFeature->is_selected ? 'checked' : '' }}>
                                                                                        </div>
                                                                                        @if ($posFeature->is_selected)
                                                                                            <input type="hidden" name="pos_features[{{ $posFeature->id }}]" value="{{ $posFeature->id }}">
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="fw-semibold text-dark">{{ $posFeature->title }}</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span class="badge rounded-pill bg-light text-dark border">${{ number_format($posFeature->amount, 2) }}</span>
                                                                                    </td>
                                                                                    <td>
                                                                                        @if($posFeature->qty_base_pricing)
                                                                                        <input style="width: 50px;" type="number" value="{{ $posFeature->qty }}" class="qty_input" name="pos_feature_qty[{{ $posFeature->id }}]" />
                                                                                        @endif
                                                                                    </td>
                                                                                    <td class="pe-4">
                                                                                        <div class="input-group input-group-sm">
                                                                                            <span class="input-group-text bg-white">$</span>
                                                                                            <input type="text" step="0.01" class="form-control custom_input" 
                                                                                                name="pos_feature_price[{{ $posFeature->id }}]"
                                                                                                value="{{ $posFeature->amount }}">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                        <tfoot class="table-group-divider">
                                                                            <tr class="table-active">
                                                                                <td colspan="4" class="text-end fw-bold py-3">Total Amount:</td>
                                                                                <td class="pe-4">
                                                                                    <div class="input-group">
                                                                                        @php
                                                                                        $totalAmount = $posFeatures->where('is_selected', 1)->sum(function ($feature) {
                                                                                            return $feature->amount * max(1, $feature->qty);
                                                                                        });
                                                                                        @endphp
                                                                                        <span class="input-group-text bg-dark text-white">$</span>
                                                                                        <input readonly type="text" class="form-control fw-bold bg-white" 
                                                                                            id="total_amount" name="total_amount" 
                                                                                            value="{{ number_format($totalAmount, 2, '.', '') }}">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                            <div class="card-body border-top bg-light">
                                                                <div class="row g-4">
                                                                    <div class="col-md-7">
                                                                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Billing Information</h6>
                                                                        <div class="row g-2">
                                                                            <div class="col-3">
                                                                                <label for="">First Name</label>
                                                                                <input type="text" class="form-control" value="{{ (json_decode($subscription?->billing_info, true))['first_name'] ?? "" }}" name="first_name" placeholder="First Name" required {{ $activeStatus ? 'disabled' : '' }} />
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <label for="">Last Name</label>
                                                                                <input type="text" class="form-control" value="{{ (json_decode($subscription?->billing_info, true))['last_name'] ?? "" }}" name="last_name" placeholder="Last Name" required {{ $activeStatus ? 'disabled' : '' }} />
                                                                            </div>
                                                                            <div class="col-6">
                                                                                <label for="company">Company Name</label>
                                                                                <input type="text" class="form-control" value="{{ (json_decode($subscription?->billing_info, true))['company'] ?? "" }}" name="company" placeholder="Company Name" {{ $activeStatus ? 'disabled' : '' }} />
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-5">
                                                                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Payment Details</h6>
                                                                        <div class="row g-2">
                                                                            <div class="col-7">
                                                                                <label for="card_number">Card Number</label>
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control" name="card_number" placeholder="Card Number" {{ $activeStatus ? '' : 'required' }} />
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <label for="">MM</label>
                                                                                <select name="card_expiry_month" id="card_expiry_month" class="form-select" {{ $activeStatus ? '' : 'required' }}>
                                                                                    <option value="">MM</option>
                                                                                    @for ($i = 1; $i <= 12; $i++)
                                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                                    @endfor
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <label for="">YYYY</label>
                                                                                <select name="card_expiry_year" id="card_expiry_year" class="form-select" {{ $activeStatus ? '' : 'required' }}>
                                                                                    <option value="">YYYY</option>
                                                                                    @for ($i = date('Y'); $i <= date('Y') + 25; $i++)
                                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                                    @endfor
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="card-footer bg-white py-3 text-end">
                                                                @if($subscription)
                                                                    <input type="hidden" name="subscription_id" value="{{ $subscription?->i ?? null }}">
                                                                    @if($currentStatus == 'active')
                                                                        <a href="{{ route('subscription-cancel', ['restaurantId' => $restaurantId, 'subscriptionId' => $subscription?->id ?? null]) }}" class="btn btn-danger px-5 fw-bold shadow-sm"
                                                                            onclick="return confirm('Are you sure? You want to cancel subscription.')" >
                                                                            Cancel Subscription
                                                                        </a>
                                                                    @endif

                                                                    @if(in_array($currentStatus, ["active", "suspended"]))
                                                                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                                                            Update Subscription
                                                                        </button>
                                                                    @endif

                                                                    @if(!in_array($currentStatus, ["active", "suspended"]))
                                                                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                                                            Re-Subscribe Now
                                                                        </button>
                                                                    @endif
                                                                @else
                                                                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                                                        Subscribe Now
                                                                    </button>
                                                                @endif
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
@endsection


@section('js')
    <script>
        $(document).ready(function() {

            function calculateTotal() {
                let totalAmount = 0;

                $('.custom_input_checkbox:checked').each(function() {
                    let row = $(this).closest('tr');

                    let price = parseFloat(row.find('.custom_input').val()) || 0;
                    let qty = parseFloat(row.find('.qty_input').val()) || 1;

                    totalAmount += price * qty;
                });

                $('#total_amount').val(totalAmount.toFixed(2));
            }

            $(document).on(
                'change input',
                '.custom_input_checkbox, .custom_input, .qty_input',
                calculateTotal
            );

            calculateTotal();
        });
    </script>
@endsection