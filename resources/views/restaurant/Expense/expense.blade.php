@extends('layouts.master')
@section('title')
    {{ trans('lang.expense') }}
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>{{ trans('lang.add') }} {{ trans('lang.expense') }}</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('expense-create') }}" method="POST" id="expense-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="expenses_type_id">{{ trans('lang.expense') }}
                                    {{ trans('lang.type') }}</label>
                                <select class="form-select" id="expenses_type_id" name="expenses_type_id" required>
                                    <option value="">Select Expense Type</option>
                                    @if(isset($expenseTypes) && count($expenseTypes) > 0)
                                        @foreach($expenseTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('expenses_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->expense_type }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('expenses_type_id')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="expense_category">{{ trans('lang.expense') }}
                                    {{ trans('lang.name') }}</label>
                                <input type="text" placeholder="{{ trans('lang.expense') }} {{ trans('lang.name') }}"
                                    id="expense_category" name="expense_category" class="form-control max30Length" required
                                    value="{{ old('expense_category', '') }}">
                                @error('expense_category')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="expense_amount">{{ trans('lang.amount') }}</label>
                                <input type="number" step="0.01" min="0" placeholder="{{ trans('lang.amount') }}"
                                    id="expense_amount" name="expense_amount" class="form-control" required
                                    value="{{ old('expense_amount', '') }}">
                                @error('expense_amount')
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

                            <div class="mb-3">
                                <label class="form-label" for="expense_description">{{ trans('lang.description') }}</label>
                                <textarea placeholder="{{ trans('lang.description') }}" id="expense_description"
                                    name="expense_description" class="form-control" rows="3">{{ old('expense_description', '') }}</textarea>
                                @error('expense_description')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">{{ trans('lang.save') ?? 'Save' }}</button>
                        </form>
                    </div>
                </div>

                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-content table-container p-3">
                        <button class="btn btn-primary mt-2 mb-4" id="expense-add">{{ trans('lang.add') }}</button>
                        <table data-page-length="-1" id="expense_type" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup">#</th>
                                    <th scope="rowgroup">{{ trans('lang.expense') }} {{ trans('lang.type') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.expense') }} {{ trans('lang.name') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.amount') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.description') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.created_by') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.createAt') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.status') }}</th>
                                    <th scope="rowgroup">{{ trans('lang.action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="expense_rows">
                                @if (isset($dataList) && count($dataList) > 0)
                                    @foreach ($dataList as $kys => $vls)
                                        <tr>
                                            <td>{{ $kys + 1 }}</td>
                                            <td>{{ $vls->expensesType->expense_type ?? '' }}</td>
                                            <td>{{ $vls->expense_category }}</td>
                                            <td>{{ $vls->expense_amount }}</td>
                                            <td>{{ $vls->expense_description }}</td>
                                            <td>{{ $vls->createdBy->name ?? '' }}</td>
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
                                                    @can('expense-edit')
                                                        <a aria-hidden="true" href="#" class="expense-edit"
                                                            data-id="{{ $vls->id }}">
                                                            <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                        </a>
                                                    @endcan
                                                </span>

                                                @can('expense-delete')
                                                    <span>
                                                        <a aria-hidden="true" href="#" data-bs-toggle="modal" class="deleteExpense"
                                                            data-bs-target="#deleteExpenseModal"
                                                            data-url="{{ route('expense-delete', ['id' => $vls->id]) }}">
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
    <div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-labelledby="deleteExpenseLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteExpenseLabel">Delete Expense</h5>
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
                    <a href="#" class="btn btn-primary" id="deleteExpenseBtn">Delete</a>
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

        var expenseCreateUrl = '{{ route('expense-create') }}';
        var expenseUpdateUrl = '{{ route('expense-edit', ':id') }}';
        var expenseDetailUrl = '{{ route('expense', ':id') }}';
    </script>

    <script src="{{ asset('assets/js/expense.js') }}"></script>
@endsection
