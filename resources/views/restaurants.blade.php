@extends('layouts.master')
@section('title')
    Restaurants
@endsection
@section('css')
    <style>
        .bx {
            font-size: 22px;
            position: relative;
            top: 5px; 
            left:5px
        }
    </style>
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Restaurant Management</h4>
                        <a href="{{ route('restaurant-create') }}">Add Restaurant</a>
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table data-page-length="-1" id="item_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">#</th>
                                    <th scope="rowgroup">Name</th>
                                    <th scope="rowgroup">Email </th>
                                    <th scope="rowgroup">Phone</th>
                                    <th scope="rowgroup">Created by </th>
                                    <th scope="rowgroup">Status</th>
                                    <th scope="rowgroup"> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($restaurants as $restaurant)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $restaurant->name }}</td>
                                        <td>{{ $restaurant->email }}</td>
                                        <td>{{ $restaurant->phone }}</td>
                                        <td>{{ $restaurant->createdBy->name ?? "" }}</td>
                                        <td>
                                            <div class="{{ $restaurant->status == "Active" ? 'reserved' : 'pending' }}">
                                                <img src="{{ $restaurant->status == "Active" ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                    class="me-2" alt="{{ $restaurant->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $restaurant->status == "Active" ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="me-2">
                                                <a aria-hidden="true"
                                                    href="{{ route('restaurant-edit', ['restaurantId' => $restaurant->id]) }}" data-bs-toggle="tooltip" title="Edit">
                                                    <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                </a>
                                            </span>
                                            <span>
                                                <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleterestaurant"
                                                    data-bs-target="#deleterestaurantModal"
                                                    data-url="{{ route('restaurant-delete', ['restaurantId' => $restaurant->id]) }}" data-bs-toggle="tooltip" title="Delete">
                                                    <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
                                                </a>
                                            </span>
                                            <span>
                                                <a href="{{ route('restaurant-branches', ['restaurantId' => $restaurant->id]) }}" data-bs-toggle="tooltip" title="Locations"><i class="bx bx-store"></i></a>
                                            </span>
                                            <span class="ms-2">
                                                <a href="{{ route('restaurant-payroll-settings', ['restaurantId' => $restaurant->id]) }}" data-bs-toggle="tooltip" title="Payroll">
                                                    <img src="{{ asset('assets/images/payroll.png') }}" alt="Payroll">
                                                </a>
                                            </span>
                                            <span class="ms-2">
                                                <a href="{{ route('subscription-list', ['restaurantId' => $restaurant->id]) }}" data-bs-toggle="tooltip" title="Subscription">
                                                    <img src="{{ asset('assets/images/subscription.png') }}" alt="subscription">
                                            </span>
                                            <span>
                                                <a href="{{ route('sa-device-status', ['restaurantId' => $restaurant->id]) }}" data-bs-toggle="tooltip" title="Device Status">
                                                    <i class="bx bx-desktop"></i>
                                                </a>
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
    <div class="modal fade" id="deleterestaurantModal" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Restaurant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-img">
                        <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                    </div>
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you Sure?</h4>
                        <p class="text-center mt-3">Do you really want to delete ? <br>This process cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-primary" id="deleteRestaurantBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('click', '#deleterestaurant', function(e) {
            var url = $(this).data('url');
            $('#deleteRestaurantBtn').attr('href',url);
        });
    </script>
@endsection
