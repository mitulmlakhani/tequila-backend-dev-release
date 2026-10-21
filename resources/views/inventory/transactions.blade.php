@extends('layouts.master')
@section('title')
    Inventory Transactions
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Transactions for {{ $item->name }}</h4>
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">Date</th>
                                    <th scope="rowgroup">Mode</th>
                                    <th scope="rowgroup">Quantity</th>
                                    <th scope="rowgroup">Created By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('m-d-Y h:i A') }}</td>
                                        <td>{{ $transaction->type ? "Add" : "Subtract" }}</td>
                                        <td>{{ $transaction->quantity }}</td>
                                        <td>{{ $transaction->createdBy->name }}</td>
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
@endsection