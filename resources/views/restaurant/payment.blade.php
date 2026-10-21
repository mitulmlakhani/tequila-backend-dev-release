@extends('layouts.master')
@section('content')

<!-- Main Section Start -->
<div class="wrapper home-section">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="main-heading">
                    <h4>Payment Transactions</h4>
                </div>
            </div>

            <div class="col-12">
                <div class="main-content p-3">
                    <table id="payment_transactions" class="table table-striped display nowrap w-100">
                        <thead>
                            <tr>
                                <th scope="rowgroup">Transaction ID</th>
                                <th scope="rowgroup">Order ID</th>
                                <th scope="rowgroup">Payment Method</th>
                                <th scope="rowgroup">Amount</th>
                                <th scope="rowgroup">Status</th>
                                <th scope="rowgroup">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $index => $payment)
                                <tr>
                                    <td>{{ $payment->transaction_id }}</td>
                                    @canany(['order'])
                                        <td>
                                            <a aria-hidden="true" href="{{ route('order', ['id' => $payment->order_id]) }}">
                                                #{{ $payment->order_id }}
                                            </a>
                                        </td>
                                    @else
                                        <td>#{{ $payment->order_id }}</td>
                                    @endcanany
                                    <td>{{ $payment->paymentMethodDetails->name ?? 'N/A' }}</td>
                                    <td>${{ $payment->amount }}</td>
                                    <td class="{{ $payment->status == 'completed' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                        {{ ucfirst($payment->status) }}
                                    </td>
                                    <td>{{ $payment->local_created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3 d-flex justify-content-end">
            {{ $payments->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
<!-- Main Section End -->

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#payment_transactions').DataTable({
            responsive: true,
            "order": [[ 7, "desc" ]]  // Sort by date column by default
        });
    });
</script>
@endsection
