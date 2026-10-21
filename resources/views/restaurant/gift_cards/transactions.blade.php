@extends('layouts.master')
@section('title', 'Gift Card Transactions')

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="main-heading">
                    <h4>Transactions for Gift Card: {{ $giftCard->card_number }}</h4>
                </div>
                @include('layouts.flash-msg')
                <div class="main-content p-3">
                    <div class="table-responsive">
                        <table id="transactions" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Transaction ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Processed By</th>
                                    <th>Transaction Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->transaction_id }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->transaction_type == 'add' ? 'success' : 'danger' }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($transaction->amount, 2) }}</td>
                                    <td>{{ $transaction->description ?? 'N/A' }}</td>
                                    <td>{{ $transaction->createdBy->name ?? 'Admin' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d, M Y H:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 d-flex justify-content-end">
                        {{ $transactions->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
