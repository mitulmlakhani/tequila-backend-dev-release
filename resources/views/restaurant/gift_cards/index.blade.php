@extends('layouts.master')
@section('title', 'Gift Cards')

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="main-heading">
                    <h4>Gift Card Management</h4>
                </div>
                @include('layouts.flash-msg')
                <div class="main-content p-3">
                    <div class="table-responsive">
                        <div class="row mb-3">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <form method="GET" action="{{ route('gift-cards.index') }}" class="d-flex">
                                    <input type="text" name="search" class="form-control me-2" placeholder="Search by Card Number or Balance" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </form>
                            </div>
                        </div>

                        <!-- Add Sortable Table Headers -->
                        <table id="giftCards" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th><a href="{{ route('gift-cards.index', array_merge(request()->all(), ['sortField' => 'id', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">#</a></th>
                                    <th><a href="{{ route('gift-cards.index', array_merge(request()->all(), ['sortField' => 'card_number', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">Card Number</a></th>
                                    <th><a href="{{ route('gift-cards.index', array_merge(request()->all(), ['sortField' => 'current_balance', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">Balance</a></th>
                                    <th><a href="{{ route('gift-cards.index', array_merge(request()->all(), ['sortField' => 'expiration_date', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">Expiry Date</a></th>
                                    <th><a href="{{ route('gift-cards.index', array_merge(request()->all(), ['sortField' => 'status', 'sortDirection' => request('sortDirection') === 'asc' ? 'desc' : 'asc'])) }}">Status</a></th>
                                    <th>Created By</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $giftCard)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @can('gift-cards.transactions')
                                            <a href="{{ route('gift-cards.transactions', ['id' => $giftCard->id]) }}">
                                                {{ $giftCard->card_number }}
                                            </a>
                                        @else
                                            {{ $giftCard->card_number }}
                                        @endcan
                                    </td>
                                    <td>${{ number_format($giftCard->current_balance, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($giftCard->expiration_date)->format('d, M Y') }}</td>
                                    <td>{{ ucfirst($giftCard->status) }}</td>
                                    <td>{{ $giftCard->createdBy->name ?? 'Admin' }}</td>
                                    <td class="text-center">
                                        <!-- <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#updateBalanceModal" data-id="{{ $giftCard->id }}" data-balance="{{ $giftCard->current_balance }}">Add/Subtract</button> -->
                                         @can('gift-cards.update-balance')
                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#updateBalanceModal"
                                                data-id="{{ $giftCard->id }}"
                                                data-balance="{{ $giftCard->current_balance }}">
                                                Add/Subtract
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="mt-3 d-flex justify-content-end">
                        {{ $data->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Update Balance -->
<div class="modal fade" id="updateBalanceModal" tabindex="-1" aria-labelledby="updateBalanceLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <form method="POST" action="{{ route('gift-cards.update-balance', ':id') }}" id="updateBalanceForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateBalanceLabel">Update Gift Card Balance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="gift_card_id" id="giftCardId">
                    <div class="mb-3">
                        <label class="form-label" for="amount">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" required min="0.01" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="transactionType">Transaction Type</label>
                        <select name="transaction_type" id="transactionType" class="form-select" required>
                            <option value="add">Add</option>
                            <option value="deduct">Subtract</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Balance</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script>
    $('#updateBalanceModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var giftCardId = button.data('id');
        var actionUrl = "{{ route('gift-cards.update-balance', ':id') }}".replace(':id', giftCardId);

        $('#updateBalanceForm').attr('action', actionUrl);
        $('#giftCardId').val(giftCardId);
    });


    // Toggle Active/Inactive Status
    $('.clickable-status').on('click', function() {
        var giftCardId = $(this).data('id');
        var currentStatus = $(this).data('status');
        var newStatus = currentStatus === 'active' ? 'inactive' : 'active';

        $.ajax({
            url: "{{ route('gift-cards.toggle-status') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                gift_card_id: giftCardId,
                status: newStatus
            },
            success: function(response) {
                location.reload(); // Reload the page to reflect changes
            },
            error: function(error) {
                alert('Failed to update the status.');
            }
        });
    });
</script>
@endsection
