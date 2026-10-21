@extends('layouts.master')
@section('title', 'Customer Reviews')

@section('content')

<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="main-heading">
                    <h4>Customer Reviews</h4>
                </div>
            </div>
            @include('layouts.flash-msg')

            <div class="col-12">
                <div class="main-content p-3">
                    <div class="table-responsive">
                        <table id="customer-reviews-table" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th>Rating</th>
                                    <th>Review Text</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .dataTables_length, .dataTables_filter {
        margin-bottom: 15px;
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        var table = $('#customer-reviews-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('customer-reviews.index') }}",
                data: function(d) {
                    // additional params if needed
                }
            },
            columns: [
                { data: 'sno', name: 'sno', orderable: false, searchable: false },
                { data: 'customer_name', name: 'customer_name', orderable: false },
                { data: 'rating', name: 'rating' },
                { data: 'review_text', name: 'review_text' },
                { data: 'created_at', name: 'created_at' }
            ],
            order: [[ 4, "desc" ]],
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10
        });
    });
</script>
@endsection
