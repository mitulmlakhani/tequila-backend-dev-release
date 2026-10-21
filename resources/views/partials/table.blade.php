<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Transaction Type</th>
                <th>Amount</th>
                <th>Cashier</th>
                <th>Recipient / Vendor</th>
                <th>Comment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $index => $transaction)
                <tr>
                    <td>{{ $transactions->firstItem() + $index }}</td>
                    <td>{{ $transaction->transaction_type == 1 ? 'Cash In' : 'Cash Out' }}</td>
                    <td>${{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->cashier->name ?? 'N/A' }}</td>
                    <td>
                        @if ($transaction->cash_out_type == 'tip_out')
                            {{ $transaction->cashOutTo->name ?? 'N/A' }}
                        @elseif ($transaction->cash_out_type == 'vendor')
                            {{ $transaction->vendor_name ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $transaction->comment ?? '-' }}</td>
                    <td>{{ $transaction->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div class="mt-3 d-flex justify-content-end">
    {{ $transactions->links('pagination::bootstrap-4') }}
</div>
