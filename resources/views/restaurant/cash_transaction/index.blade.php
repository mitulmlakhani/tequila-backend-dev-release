@extends('layouts.master')
@section('title', 'Transaction History')

@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12">
                    <div class="main-heading">
                        <h4>Transaction History</h4>
                    </div>
                    <div class="main-content p-3">
                        <div id="transaction-table">
                            @include('partials.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        // Handle pagination clicks
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            fetchTransactions(page);
        });

        function fetchTransactions(page) {
            $.ajax({
                url: "{{ route('cash_transactions.index') }}?page=" + page,
                success: function (data) {
                    $('#transaction-table').html(data);
                }
            });
        }
    });
</script>
@endsection
