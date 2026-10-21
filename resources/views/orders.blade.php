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
                        <h4>Tickets Management</h4>
                        @can('table-create')
                            {{-- <a href="{{ route('table-create') }}" id="table-add" data-bs-toggle="modal"
                                data-bs-target="#table-add-modal">Add Order</a> --}}
                        @endcan
                    </div>
                </div>
                @include('layouts.flash-msg')
                @include('layouts.validation-error-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup"></th>
                                    <th scope="rowgroup">Ticket No</th>
                                    <th scope="rowgroup">Referance No</th>
                                    <th scope="rowgroup">Order Referance No</th>
                                    <th scope="rowgroup">Table No.</th>
                                    <th scope="rowgroup">Amount</th>
                                    <th scope="rowgroup">Date</th>
                                    <th scope="rowgroup">Status</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->iteration }}</td>
                                        <td>#{{ $order->ticket_no }}</td>
                                        <td>#{{ $order->reference_number }}</td>
                                        <td>#{{ $order->parentOrder->reference_number }}</td>
                                        <td>{{ $order->parentOrder->tables->pluck('table.table_no')->implode(', ') }}</td>
                                        <td>{{ currencyFormat($order->total_amount) }}</td>
                                        <td>{{ dateFormat($order->created_at) }}</td>
                                        <td>
                                            <span class="badge bg-info text-dark">@if(!empty($order->orderStatus)) {{$order->orderStatus->name}} @else N/A @endif</span>
                                        </td>

                                        <td>
                                            <span class="me-2">
                                                @can('order')
                                                <a aria-hidden="true" href="{{ route('order', ['id' => $order->id]) }}" title="View">
                                                    <img src="{{asset('assets/images/add.png') }}" alt="add">
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

@endsection
@section('js')
    <script>
        $(document).on('click', '#table-add', function(e) {
            $('#table-add-modal').find('.modal-title').text('Add Table');
            var tableAddForm = $('#table-add-modal').find('form');
            var formUrl = '{{ route('table-create') }}';
            tableAddForm.attr('action', formUrl);
            tableAddForm.find('button[type=submit]').text('Add');
            tableAddForm.find('#floor').val('');
            tableAddForm.find('#table_no').val('');
            tableAddForm.find('#seating_capacity').val('');
        });

        $(document).on('click', '#table-edit', function(e) {
            $('#table-add-modal').find('.modal-title').text('Edit Table');
            tableAddForm = $('#table-add-modal').find('form');
            console.log(tableAddForm.attr('action'));
            var id = $(this).data('id');
            var formUrl = '{{ route('table-edit', ':id') }}';
            formUrl = formUrl.replace(':id', id);
            tableAddForm.attr('action', formUrl);
            tableAddForm.find('button[type=submit]').text('Update');

            var url = '{{ route('table', ':id') }}';
            url = url.replace(':id', id);
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                async: false,
                success: function(response) {
                    if (response.status == 'success') {
                        tableAddForm.find('#floor_id').val(response.data.floor_id);
                        tableAddForm.find('#table_no').val(response.data.table_no);
                        tableAddForm.find('#seating_capacity').val(response.data.seating_capacity);
                        tableAddForm.find('#status').val(response.data.status);
                    }

                }
            });
        });

        $(document).on('click', '#deleteTable', function(e) {
            var url = $(this).data('url');
            $('#deleteTableBtn').attr('href',url);
        });
    </script>
    
@endsection
