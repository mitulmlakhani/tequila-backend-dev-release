@extends('layouts.master')
@section('title')
    Gift Voucher
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4 id="form-heading">{{ old('voucher_id') ? 'Edit Gift Voucher' : 'Add Gift Voucher' }}</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ old('voucher_id') ? route('gift-vouchers.update', ['id' => old('voucher_id')]) : route('gift-vouchers.store') }}" method="POST" id="voucher-form">
                            @csrf
                            <input type="hidden" id="voucher_id" name="voucher_id" value="{{ old('voucher_id', '') }}">
                            
                            <div class="mb-3">
                                <label class="form-label" for="voucher_code">Voucher Code</label>
                                <input type="text" placeholder="Voucher Code" id="voucher_code" name="voucher_code"
                                    class="form-control" required value="{{ old('voucher_code', '') }}">
                                @error('voucher_code')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="amount">Amount</label>
                                <input type="text" placeholder="Amount" id="amount" name="amount"
                                    class="form-control numberInput" required value="{{ old('amount', '') }}">
                                @error('amount')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="expiry_date">Expiry Date</label>
                                <input type="date" id="expiry_date" name="expiry_date" class="form-control" value="{{ old('expiry_date', '') }}">
                                @error('expiry_date')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="status">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="redeemed" {{ old('status') == 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                                </select>
                                @error('status')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            @canany(['gift-vouchers.store', 'gift-vouchers.update'])
                            <button type="submit" id="form-submit" class="btn btn-primary">{{ old('voucher_id') ? 'Update' : 'Save' }}</button>
                            <button type="button" class="btn btn-secondary {{ old('voucher_id') ? '' : 'd-none' }}" id="cancel-edit">Cancel</button>
                            @endcan
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-heading">
                        <h4>Gift Voucher Management</h4>
                    </div>
                    <div class="main-content p-3">
                        <table id="ingredients" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Voucher Code</th>
                                    <th>Amount</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $record)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $record->code }}</td>
                                        <td>${{ $record->amount }}</td>
                                        <td>{{ date('d, M Y', strtotime($record->expiry_date)) }}</td>
                                        <td>
                                            @if ($record->status == 'active')
                                                Active
                                            @elseif ($record->status == 'inactive')
                                                Inactive
                                            @elseif ($record->status == 'redeemed')
                                                Redeemed
                                            @elseif ($record->status == 'expired' || $record->expiry_date < now())
                                                Expired
                                            @endif
                                        </td>
                                        <td>
                                            <span class="me-2">
                                                @can('gift-vouchers.update')
                                                    <a aria-hidden="true" href="#" id="gift-voucher-edit"
                                                        data-id="{{ $record->id }}">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <span>
                                                @can('gift-vouchers.destroy')
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#deleteRecord"
                                                        data-url="{{ route('gift-vouchers.destroy', ['id' => $record->id]) }}">
                                                        <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
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
    <!--Main Section End-->

    <!--Delete Modal Popup Start-->
    <div class="modal fade" id="deleteRecord" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-img">
                        <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                    </div>
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you sure?</h4>
                        <p class="text-center mt-3">Do you really want to delete this record? This process cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteVoucherForm" method="POST" action="">
                        @csrf
                        @method('DELETE') <!-- This is the method override to ensure DELETE request -->
                        <button type="submit" class="btn btn-primary">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal Popup End-->
@endsection

@section('js')
    <script>
        var storeRecordUrl  = '{{ route('gift-vouchers.store') }}';
        var updateRecordUrl = '{{ route('gift-vouchers.update', ':id') }}';
        var showRecordUrl   = '{{ route('gift-voucher', ':id') }}';
    </script>
    
    <script src="{{ asset('assets/js/voucher.js') }}"></script>
@endsection
