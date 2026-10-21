@extends('layouts.master')
@section('title')
    Create Invoice
@endsection

@section('css')
    <link rel="stylesheet" href={{ asset('assets/css/plugins/flatpickr.min.css') }}>
    <style>
        .form-check-input {
            width: 25px !important;
            height: 25px !important;
            padding-bottom: 10px !important;
            margin-right: 10px !important;
        }

        .form-check-label {
            font-size: 18px !important;
            vertical-align: middle !important;
        }

        .item_total_row {
            padding-right: 10px;
        }
    </style>
@endsection

@php
    $selectedIngredients = array_keys(old('items', []));
    $totalAmount = 0;
@endphp

@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('vendor-invoice-update', ['invoiceId' => request()->route('invoiceId')]) }}">
                        @csrf

                        @if (request()->route('id'))
                            <input type="hidden" name="vendor" value="{{ request()->route('id') }}" />
                        @endif

                        <div class="main-heading">
                            <h4>Vendor Management</h4>
                            <button type="submit" class="btn btn-primary" id="save-btn">Save</button>
                        </div>

                        <div class="main-content p-3">

                            <div class="row mb-5">
                                <div class="col-4">
                                    <label for="vendor" class="form-label">Vendor Name</label>
                                    <select class="form-select" name="vendor" id="vendor"
                                        {{ request()->route('id') ? 'disabled' : '' }}>
                                        @foreach ($vendors ?? [] as $vendor)
                                            <option value="{{ $vendor->id }}"
                                                {{ old('vendor', request()->route('id')) == $vendor->id ? 'selected' : '' }}>
                                                {{ ucwords($vendor->name) }}</option>
                                        @endforeach
                                    </select>

                                    @error('vendor')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-4">
                                    <label for="invoice_number" class="form-label">Invoice Number</label>
                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                        value="{{ old('invoice_number', $vendorInvoice->invoice_number) }}">
                                    @error('invoice_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-4">
                                    <label for="invoice_date" class="form-label">Invoice Date</label>
                                    <input type="text" class="form-control" id="invoice_date" name="invoice_date"
                                        value="{{ old('invoice_date', $vendorInvoice->invoice_date ?: date('Y-m-d')) }}"
                                        required>
                                    @error('invoice_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="40%">Ingredient</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Price</th>
                                                <th class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="invoice_items">
                                            @foreach (old('items', $vendorInvoice->items) as $id => $item)
                                                <tr class="ingredient-item mb-3" id="ingredient-item-{{ $id }}">
                                                    <input type="hidden" name="items[{{ $id }}][name]"
                                                        value="{{ $item['name'] }}" />
                                                    <input type="hidden" name="items[{{ $id }}][uom]"
                                                        value="{{ $item['uom'] }}" />
                                                    <input type="hidden" name="items[{{ $id }}][id]"
                                                        value="{{ $item['id'] }}" />
                                                    <td class="fw-bold" style="vertical-align: middle;" width="40%">
                                                        {{ $item['name'] }}
                                                    <td class="text-center d-flex justify-content-center align-items-center"
                                                        style="padding-right:10%">
                                                        <strong>{{ $item['uom'] }}</strong>
                                                        <input type="text" inputmode="decimal"
                                                            class="form-control item-qty ms-2"
                                                            id="quantity-{{ $id }}"
                                                            name="items[{{ $id }}][quantity]"
                                                            value="{{ $item['quantity'] }}" required>
                                                        @error("items.$id.quantity")
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="number" step="any" class="form-control item-price"
                                                            id="price-{{ $id }}"
                                                            name="items[{{ $id }}][price]"
                                                            value="{{ $item['price'] }}" required>
                                                        @error("items.$id.price")
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="text-end" style="width: 15%">
                                                        <strong class="item_total_row">
                                                            @php
                                                                $totalAmount +=
                                                                    (float) $item['quantity'] *
                                                                    ((float) $item['price']);
                                                            @endphp
                                                            {{ number_format((float) $item['quantity'] * (float) $item['price'], 2, '.', ',') }}
                                                        </strong>
                                                    </td>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td style="padding-right: 10px;" colspan="4" class="text-end">
                                                    <span>Total: </span>
                                                    <span class="fw-bold"
                                                        id="total-amount">{{ number_format($totalAmount, 2, '.', ',') }}</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--Main Section End-->
    @endsection

    @section('js')
        <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
        <script>
            $(document).ready(function() {

                function updateTotal() {
                    var total = 0;

                    $('.ingredient-item').each(function() {
                        var qty = parseFloat($(this).find('.item-qty').val()) || 0;
                        var price = parseFloat($(this).find('.item-price').val()) || 0;

                        if (qty > 0 && price > 0) {
                            var itemTotal = qty * price;
                            total += itemTotal;

                            $(this).find('.item_total_row').text(itemTotal.toFixed(2));
                        }
                    });

                    $('#total-amount').text(total.toFixed(2));
                }

                $(document).on('change input', '.item-price, .item-qty', function() {
                    updateTotal();
                });
            });
        </script>

        <script>
            flatpickr("#invoice_date", {
                dateFormat: "Y-m-d",
            });
        </script>
    @endsection
