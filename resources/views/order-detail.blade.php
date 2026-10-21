@extends('layouts.master')
@section('title')
    Orders
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Order Details</h4>
                        <a href="{{ URL::previous() }}">Back</a>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-8">
                    <div class="order_id d-flex justify-content-between">
                        <h5 class="text-dark mb-3">Order Number <span>#{{ $order->ticket_no }} - {{ $order->reference_number }}</span></h5>
                        <span class="payment_status">Status - @if(!empty($order->orderStatus)) {{$order->orderStatus->name}} @else N/A @endif</span>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-8">
                    <div class="main-content p-3">
                        <table class="table table-striped item_summary">
                            <thead>
                                <tr>
                                    <th class="w-50" scope="rowgroup">Items Summary </th>
                                    <th class="text-end" scope="rowgroup">Qty</th>
                                    <th class="text-end" scope="rowgroup">Price</th>
                                    <th class="text-end" scope="rowgroup">Discount</th>
                                    <th class="text-end" scope="rowgroup">Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $orderItem)
                                    @php
                                        $name = $orderItem->item_name . (!empty($orderItem->item_variant_name) ? ' | ' . $orderItem->item_variant_name : '');
                                        $quantity = $orderItem->quantity;
                                        $price = currencyFormat($orderItem->price);
                                        $discount = currencyFormat($orderItem->discount);
                                        $totalAmount = currencyFormat($orderItem->total_amount);
                                        foreach ($orderItem->orderItemModifiers as $orderItemModifier) {
                                            $name .= '<br><span class="ps-3 fst-italic">'.$orderItemModifier->modifier_name.'</span>';
                                            $quantity .= '<br>'.$orderItemModifier->quantity;
                                            $price .= '<br>'.currencyFormat($orderItemModifier->total_amount);
                                            $totalAmount .= '<br>'.currencyFormat($orderItemModifier->total_amount);
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            {{-- <img src="{{$orderItem->itemVariant->item->image_url}}" class="list-image" alt="order-item"> --}}
                                            {!! $name !!}
                                        </td>
                                        <td class="text-end">{!! $quantity !!}</td>
                                        <td class="text-end">{!! $price !!}</td>
                                        <td class="text-end">{!! $discount !!}</td>
                                        <td class="text-end">{!! $totalAmount !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="main-content p-3 mt-3">
                        <div class="order_id d-flex justify-content-between">
                            <h5 class="text-dark mb-3">Customer and Order Details</h5>
                        </div>
                        <table class="table table-striped customer_details">
                            <tbody>
                                <tr>
                                    <td>Customer Name</td>
                                    <td>
                                        <span class="float-end">
                                            @if (!empty($order->parentOrder->customer_id))
                                                {{ $order->parentOrder->customerDetail->name }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Phone Number/Email</td>
                                    <td><span class="float-end">
                                            @if (!empty($order->parentOrder->customer_id))
                                                {{ $order->parentOrder->customerDetail->email }}
                                            @else
                                                N/A
                                            @endif
                                        </span></td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td><span class="float-end">
                                            @if ($order->parentOrder->order_type == 1)
                                                Dine In
                                            @else
                                                Take Away
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Number of Guests</td>
                                    <td><span class="float-end">
                                            @if (!empty($order->no_of_guest))
                                                {{ $order->no_of_guest }}
                                            @else
                                                N/A
                                            @endif
                                    </span></td>
                                </tr>
                                <tr>
                                    <td>Note</td>
                                    <td><span class="float-end">{{ $order->note }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="main-content p-3 mt-3">
                        <div class="order_id d-flex justify-content-between">
                            <h5 class="text-dark mb-3">Transaction Details</h5>
                        </div>
                        <table class="table table-striped customer_details">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Txn Fees</th>
                                    <th>Total Paid</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->transaction_id }}</td>
                                        <td>{{ dateFormat($payment->created_at) }}</td>
                                        <td>{{ currencyFormat($payment->amount) }}</td>
                                        <td>{{ currencyFormat($payment->transaction_fee) }}</td>
                                        <td>{{ currencyFormat($payment->gross_amount ?: $payment->amount) }}</td>
                                        <td>{{ $payment->paymentMethodDetails->name ?? 'N/A' }}</td>
                                        <td class="{{ $payment->status == 'completed' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                            {{ ucfirst($payment->status) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="main-content p-3">
                        <h5 class="text-dark">Staff Information</h5>
                        <div class="staff_info">
                            @if($order->staff)
                            <div class="staff_img1 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    @if (empty($order->staff->image))
                                        <img class="profile-image" src="{{ asset('assets/images/default-user.jpg') }}" alt="staff">
                                    @else
                                        <img class="profile-image" alt="staff"
                                            src="{{ asset('images/profiles/' . $order->staff->image) }}">
                                    @endif
                                    <p class="mb-0 ps-2 ">{{ $order->staff->name ?? "" }}</p>
                                </div>
                                <div class="active_staff">
                                    <span>{{ $order->staff->userRole($format=true) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="main-content p-3 mt-3">
                        <div class="order_id d-flex justify-content-between">
                            <h5 class="text-dark mb-3">Order Summary</h5>
                        </div>
                        <table class="table table-striped customer_details">
                            <tbody>
                                <tr>
                                    <td>Order Created</td>
                                    <td><span class="float-end">{{ dateFormat($order->created_at) }}</span></td>
                                </tr>
                                <tr>
                                    <td>Order Time</td>
                                    <td><span class="float-end">{{ timeFormat($order->created_at) }}</span></td>
                                </tr>
                                <tr>
                                    <td>Table No</td>
                                    <td><span class="float-end">{{ $order->parentOrder->tables->first()->table->table_no ?? "" }}</span></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td><span class="float-end">@if(!empty($order->orderStatus)) {{$order->orderStatus->name}} @else N/A @endif</span></td>
                                </tr>
                                <tr>
                                    <td>Subtotal</td>
                                    <td><span class="float-end fw-bold">{{ currencyFormat($order->sub_total_amount) }}</span></td>
                                </tr>
                                <tr>
                                    <td>Service Charge</td>
                                    <td><span class="float-end">{{ currencyFormat($order->service_charge) }}</span></td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td><span class="float-end">{{ currencyFormat($order->discount) }}</span></td>
                                </tr>
                                @if(!empty($order->taxes))
                                @php
                                    $taxName = '';
                                    $taxAmount = '';
                                    foreach ($order->taxes as $tax) {
                                        $taxName .= $tax['tax_name'].' <small>('.$tax['percent'].'%)</small><br>';
                                        $taxAmount .= '<span class="float-end">'.currencyFormat($tax['tax_amount']).'</span><br>';
                                    }
                                @endphp
                                <tr>
                                    @if($order->is_tax_exempt)
                                    <td colspan="2"><small class="text-secondary">Tax ({{currencyFormat($order->tax_amount)}}) is exempt on this order.</small></td>
                                    @else
                                    <td>{!! $taxName !!}</td>
                                    <td><span class="float-end">{!! $taxAmount !!}</span></td>
                                    @endif
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="main-content p-3 mt-3">
                        <div class="staff_info">
                            <div class="staff_img d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 ps-3 ">Total</p>
                                </div>
                                <div>
                                    <p class="text-dark mb-0">{{ currencyFormat($order->total_amount) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->
@endsection
@section('js')
@endsection
