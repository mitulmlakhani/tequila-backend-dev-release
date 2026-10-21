@extends('layouts.master')
@section('title')
    Vendor Invoice Management
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Vendor Invoice Management</h4>
                        @can('vendor-invoice-create')
                            <a href="{{ route('vendor-invoice-new') }}">Add New Invoice</a>
                        @endcanany
                    </div>
                </div>
                @include('layouts.flash-msg')
                @can('vendor-invoice-read')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table id="vendor_invoice_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">Vendor</th>
                                    <th scope="rowgroup">Company</th>
                                    <th scope="rowgroup">Invoice Number</th>
                                    <th scope="rowgroup">Invoice Date</th>
                                    <th scope="rowgroup">Total Amount</th>
                                    <th scope="rowgroup">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($vendorInvoices as $vendorInvoice)
                                    <tr>
                                        <td>{{ $vendorInvoice->vendor->name ?? 'No vendor' }}</td>
                                        <td>{{ $vendorInvoice->vendor->company_name ?? 'No company' }}</td>
                                        <td>{{ $vendorInvoice->invoice_number }}</td>
                                        <td>{{ $vendorInvoice->invoice_date }}</td>
                                        <td>{{ $vendorInvoice->total_amount }}</td>
                                        <td>
                                            @can('vendor-invoice-edit')
                                                <a
                                                    href="{{ route('vendor-invoice-edit', ['invoiceId' => $vendorInvoice->id]) }}">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
    <!--Main Section End-->
@endsection
