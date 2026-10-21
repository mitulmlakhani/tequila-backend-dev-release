@extends('layouts.master')

@section('title') Dashboard @endsection

@section('content')

@include('layouts.flash-msg')

<!--Main Section Start-->
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="main-heading">
                    <h4>Welcome To Dashboard</h4>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4">
                <div class="card p-4 mb-4">
                    <h5>Total Payments</h5>
                    <p>${{ number_format($totalPayments, 2) }}</p>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-4">
                <div class="card p-4 mb-4">
                    <h5>Payments This Month</h5>
                    <p>${{ number_format($currentMonthPayments, 2) }}</p>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-4">
                <div class="card p-4 mb-4">
                    <h5>Total Transactions</h5>
                    <p>{{ $totalTransactions }}</p>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-4">
                <div class="card p-4 mb-4">
                    <h5>Average Payment</h5>
                    <p>${{ number_format($averagePayment, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Main Section End-->

@endsection