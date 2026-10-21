@extends('layouts.master')
@section('title')
    {{ trans('lang.expense') }} {{ trans('lang.type') }}
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>{{ trans('lang.add') }} {{ trans('lang.expense') }} {{ trans('lang.type') }}</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('expense-type-create') }}" method="POST" id="expense-type-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="expense_type_name">{{ trans('lang.expense') }}
                                    {{ trans('lang.type') }} {{ trans('lang.name') }}</label>
                                <input type="text" placeholder="{{ trans('lang.expense') }} {{ trans('lang.type') }} {{ trans('lang.name') }}"
                                    id="expense_type_name" name="expense_type" class="form-control max30Length" required
                                    value="{{ old('expense_type', '') }}">
                                @error('expense_type')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="status">{{ trans('lang.status') }}</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">{{ trans('lang.save') ?? 'Save' }}</button>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-content table-container p-3">
                        <button class="btn btn-primary mt-2 mb-4" id="expense-type-add">{{ trans('lang.add') }}</button>
                        <table data-page-length="-1" id="expense_type" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">#</th>
                                    <th scope="rowgroup">{{ trans('lang.expense') }} {{ trans('lang.type') }}
                                        {{ trans('lang.name') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.createAt') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.status') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="expense_type_rows">
                                @if (isset($dataList) && count($dataList) > 0)
                                    @foreach ($dataList as $kys => $vls)
                                        <tr>
                                            <td>{{ $kys + 1 }}</td>
                                            <td>{{ $vls->expense_type }}</td>
                                            <td>{{ dateFormat($vls->created_at) }} {{ timeFormat($vls->created_at) }}</td>
                                            <td>
                                                @if ($vls->status == 0)
                                                    <div class="pending">
                                                        <img src="{{ asset('assets/images/pending.png') }}" alt="inactive"
                                                            class="me-2">
                                                        <span>{{ trans('lang.inactive') }}</span>
                                                    </div>
                                                @elseif ($vls->status == 1)
                                                    <div class="reserved">
                                                        <img src="{{ asset('assets/images/reserved.png') }}" alt="active"
                                                            class="me-2">
                                                        <span>{{ trans('lang.active') }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="me-2">
                                                    @can('expense-type-edit')
                                                        <a aria-hidden="true" href="#" class="expense-type-edit"
                                                            data-id="{{ $vls->id }}">
                                                            <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                        </a>
                                                    @endcan
                                                </span>

                                                @can('expense-type-delete')
                                                    <span>
                                                        <a aria-hidden="true" href="#" data-bs-toggle="modal" class="deleteExpenseType"
                                                            data-bs-target="#deleteExpenseTypeModal"
                                                            data-url="{{ route('expense-type-delete', ['id' => $vls->id]) }}">
                                                            <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
                                                        </a>
                                                    </span>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->

    <!--Delete Modal Popup Start-->
    <div class="modal fade" id="deleteExpenseTypeModal" tabindex="-1" aria-labelledby="deleteExpenseTypeLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteExpenseTypeLabel">Delete Expense Type</h5>
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
                    <a href="#" class="btn btn-primary" id="deleteExpenseTypeBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal Popup end-->

@endsection

@section('js')
    <script>
        @if(Session::has('errors'))
            var isValidationError = true;
        @else
            var isValidationError = false;
        @endif

        var expenseTypeCreateUrl = '{{ route('expense-type-create') }}';
        var expenseTypeUpdateUrl = '{{ route('expense-type-edit', ':id') }}';
        var expenseTypeDetailUrl = '{{ route('expense-type', ':id') }}';
    </script>

    <script src="{{ asset('assets/js/expense-type.js') }}"></script>
@endsection