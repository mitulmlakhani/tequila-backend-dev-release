@extends('layouts.master')

@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                @include('layouts.flash-msg')
                <div class="col-12">
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Created by</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paymentMethods as $paymentMethod)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $paymentMethod->name }}</td>
                                        <td>{{ $paymentMethod->createdBy->name ?? "" }}</td>
                                        <td>
                                            @if($restaurantPaymentMethods[$paymentMethod->id]['status'] ?? null)
                                                <div class="reserved">
                                                    <img src="{{asset('assets/images/reserved.png')}}" class="me-2" alt="active">
                                                    <span>Active</span>
                                                </div>
                                            @else
                                                <div class="pending">
                                                    <img src="{{asset('assets/images/pending.png')}}" class="me-2" alt="inactive">
                                                    <span>In-active</span>
                                                </div>
                                            @endif
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
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $("#address").on('input', function() {
            $("#address").removeClass('is-invalid');
        });

        // Handle Status Toggle Click
        $(document).on('click', '.toggle-status', function () {
            let rowTr = $(this).closest('tr');
            let paymentMethodType = $(this).data('type');
            let paymentMethodName = $(this).data('name');

            let paymentMethodId = $(this).data('id');
            let newStatus = $(this).data('status');
            let statusText = newStatus === 1 ? 'Enable' : 'Disable';
            let currentButton = $(this);
            let address = $("#address").val();

            Swal.fire({
                title: `Are you sure?`,
                text: `Do you want to ${statusText} this payment method?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${statusText} it!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('manage-payment-method-edit', ':id') }}".replace(':id', paymentMethodId),
                        type: 'PUT',
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: newStatus,
                            address: $("#address").val(),
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire(
                                    'Updated!',
                                    `Payment method has been ${statusText.toLowerCase()}d.`,
                                    'success'
                                );
                                // Update button text, color, and data attribute
                                currentButton
                                    .text(newStatus ? 'Enabled' : 'Disabled')
                                    .toggleClass('btn-success btn-danger')
                                    .data('status', newStatus ? 0 : 1);

                                    window.location.reload();
                                    // rowTr.find('.text-danger').each(function(i, errSpan) {
                                    //     $(errSpan).text("");
                                    // })
                            } else {
                                Swal.fire('Error!', response.error, 'error');
                            }
                        },
                        error: function (e) {
                            if (e?.status == 422) {
                                const errorBag = e?.responseJSON?.errors;
                                (Object.keys(errorBag)).map(function(key) {
                                    rowTr.find('input[name="' + key + '"]')
                                        .next('.text-danger')
                                        .text(errorBag[key][0]);
                                });
                            }
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
