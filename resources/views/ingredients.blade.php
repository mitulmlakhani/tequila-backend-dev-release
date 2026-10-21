@extends('layouts.master')
@section('title')
    Ingredients
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="main-heading">
                        <h4>Add Ingredient</h4>
                    </div>
                    @include('layouts.flash-msg')
                    <div class="main-content p-3">
                        <form action="{{ route('ingredient-create') }}" method="POST" enctype='multipart/form-data' id="ingredient-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">Ingredient Name</label>
                                <input type="text" placeholder="Ingredient Name" id="name" name="name"
                                    class="form-control" required value="{{ old('name', '') }}">
                                @error('name')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="uom">Unit of Measure </label>
                                <select class="form-select" id="uom" name="uom" required>
                                    <option value="">Choose Unit of Measure</option>
                                    @foreach ($unitOfMeasurements as $unitOfMeasurement)
                                        <option value="{{ $unitOfMeasurement->id }}"
                                            @if(old('uom') == $unitOfMeasurement->id) selected @endif>{{ $unitOfMeasurement->name }}</option>
                                    @endforeach
                                    @error('uom')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="price">Price (per unit)</label>
                                <input type="text" placeholder="Ingredient Price" id="price" name="price"
                                    class="form-control numberInput" required step="0.01" value="{{ old('price', '') }}">
                                @error('price')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
          
                            <div class="mb-3">
                                <label class="form-label" for="buy_quantity">Buy Quantity</label>
                                <input type="text" placeholder="Buy Quantity" id="buy_quantity" name="buy_quantity"
                                    class="form-control numberInput" required step="0.01" value="{{ old('buy_quantity', '') }}">
                                @error('buy_quantity')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="mb-3">
                                <label class="form-label" for="expiry_date">Expiry Date</label>
                                <input type="date" id="expiry_date" name="expiry_date" class="form-control" value="{{ old('expiry_date', '') }}">
                                @error('expiry_date')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div> --}}
                            <div class="mb-3">
                                <label class="form-label" for="status">Status </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>In-active</option>
                                </select>
                                @error('status')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-md-9 col-lg-9">
                    <div class="main-heading">
                        <h4>Ingredient Management</h4>
                    </div>
                    <div class="main-content p-3">
                        <table id="ingredients" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup"></th>
                                    <th scope="rowgroup">Name </th>
                                    <th scope="rowgroup">Price</th>
                                    {{-- <th scope="rowgroup">Expiry Date</th> --}}
                                    <th scope="rowgroup">Buy Quantity</th>
                                    <th scope="rowgroup">Status</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ingredients as $ingredient)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ingredient->name }}</td>
                                        <td>{{ currencyFormat($ingredient->price) }} per {{ $ingredient->unitOfMeasurement->name }}</td>
                                        {{-- <td>{{ dateFormat($ingredient->expiry_date) }}</td> --}}
                                        <td>{{ $ingredient->buy_quantity }} {{ $ingredient->unitOfMeasurement->name }}</td>
                                        <td>
                                            <div class="{{ $ingredient->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $ingredient->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                    class="me-2" alt="{{ $ingredient->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $ingredient->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="me-2">
                                                @can('ingredient-edit')
                                                    <a aria-hidden="true" href="#" id="ingredient-edit"
                                                        data-id="{{ $ingredient->id }}">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <span>
                                                @can('ingredient-delete')
                                                <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteIngredient"
                                                    data-bs-target="#deleteIngredientModal"
                                                    data-url="{{ route('ingredient-delete', ['id' => $ingredient->id]) }}">
                                                    <img src="{{ asset('/assets/images/dustbin.png') }}" alt="dustbin">
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

    <!--Delete Modal Popup Start-->
    <div class="modal fade" id="deleteIngredientModal" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Ingredient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-img">
                        <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                    </div>
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you Sure?</h4>
                        <p class="text-center mt-3">Do you really want to delete these records? This process cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-primary" id="deleteIngredientBtn">Delete</a>
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

        var ingredientCreateUrl = '{{ route('ingredient-create') }}';
        var ingredientUpdateUrl = '{{ route('ingredient-edit', ':id') }}';
        var ingredientDetailUrl = '{{ route('ingredient', ':id') }}';
    </script>

    <script src="{{ asset('assets/js/ingredient.js') }}"></script>
@endsection
