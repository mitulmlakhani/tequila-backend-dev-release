@extends('layouts.master')
@section('title')
    @if (empty($item))
        Add
    @else
        Edit
    @endif Item
@endsection
@section('css')
    <style>
        .dropdown-toggle {
            border: 1px solid #ced4da;
        }

        .dropdown-toggle:hover {
            border: 1px solid #ced4da;
        }
    </style>
@endsection
@section('content')
    <!--Main Section Start-->
    @php
        $isEdit = empty($item) ? false : true;
    @endphp
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>
                            @if (empty($item))Add
                            @else
                                Edit @endif Item
                        </h4>
                        <a href="{{ URL::previous() }}">Back</a>
                    </div>
                </div>
                @include('layouts.flash-msg')
                {{-- @include('layouts.validation-error-msg') --}}
                <form method="POST" enctype='multipart/form-data' id="item_add">
                    @csrf
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="main-content p-3">
                            <div class="row">
                                <div class="col-12 col-md-4 col-lg-4">
                                    <label class="form-label" for="item-category">Category Name </label>
                                    <select class="form-select" name="item_category" id="" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @if(old('item_category', !empty($item) ? $item->category_id : '') == $category->id) selected @endif>
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('item_category')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4 col-lg-4">
                                    <label class="form-label" for="name">Item Name</label>
                                    <input type="text" placeholder="Item Name" id="name" name="name"
                                        class="form-control" value="{{old('name', !empty($item) ? $item->name : '')}}" required>
                                    @error('name')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-3 col-lg-3">
                                    <label class="form-label" for="image">Item Image </label>
                                    <input type="file" id="image" name="image" class="form-control">
                                    @error('image')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-3 col-lg-1">
                                    @if (!empty($item))<img
                                            src="{{ $item->image_url }}" class="list-image"> @endif
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 col-md-4 col-lg-4">
                                    <label class="form-label" for="time ">Preparation Time (In Mins)</label>
                                    <input type="number" placeholder="Preparation Time" id="time" name="time"
                                        class="form-control" value="{{old('time', !empty($item) ? $item->time : '')}}" min="0">
                                    @error('time')
                                         <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4 col-lg-4">
                                    <label class="form-label" for="uom  ">Unit of Measure </label>
                                    <select class="form-select" id="uom" name="uom">
                                        <option value="">Select Unit of Measure</option>
                                        @foreach ($unitOfMeasurements as $unitOfMeasurement)
                                            <option value="{{ $unitOfMeasurement->id }}"
                                                @if(old('uom', !empty($item) ? $item->uom : '') == $unitOfMeasurement->id) selected @endif>
                                                {{ $unitOfMeasurement->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('uom')
                                         <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4 col-lg-4">
                                    <label class="form-label" for="status">Status </label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="1" @if (!empty($item) && $item->status) selected @endif>Active
                                        </option>
                                        <option value="0" @if (!empty($item) && !$item->status) selected @endif>
                                            In-active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 col-md-8 col-lg-8">
                                    <label class="form-label" for="decription">Description</label>
                                    <textarea type="text" rows="5" placeholder="Description" id="description"
                                        name="description"class="form-control">{{old('description', !empty($item) ? $item->description : '')}}</textarea>
                                    @error('description')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-4 col-lg-4 price_quantity" @if (!empty($item) && $item->is_variant) style="display:none" @endif>
                                    <div class="col-12 col-md-12 col-lg-12">
                                        <label class="form-label" for="price ">Price</label>
                                        <input type="text" placeholder="Price" id="price" name="price"
                                            step="0.01" class="form-control numberInput"
                                            value="{{old('price', (!empty($item) && !$item->is_variant) ? $item->defaultVariant()->price : '')}}"
                                            @if ((!empty($item) && !$item->is_variant) || empty($item)) required @endif min="0">
                                        @error('price')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-12 col-lg-12 mt-4">
                                        <label class="form-label" for="quantity ">Quantity</label>
                                        <input type="number" placeholder="Quantity" id="quantity" name="quantity"
                                            class="form-control"
                                            value="{{old('quantity', (!empty($item) && !$item->is_variant) ? $item->defaultVariant()->quantity : '')}}"
                                            @if ((!empty($item) && !$item->is_variant) || empty($item)) required @endif min="0">
                                    </div>
                                    @error('quantity')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <div class="col-12 col-md-12 col-lg-12 mt-3 d-flex justify-content-between align-items-center">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" role="button" id="showfilds"
                                        name="is_variant" onclick="showFields()"
                                        @if (!empty($item) && $item->is_variant) checked @endif>
                                    <label class="form-check-label" for="showfilds"><b>Is Variant </b></label>
                                </div>
                                <button type="button" class="btn btn-secondary float-end mb-3 item_variant_add_new_btn"
                                    onclick="myFunctionThree()" id="item_variant_add_new_btn"
                                    style="display:@if (!empty($item) && $item->is_variant) block @else none @endif">Add
                                    New</button>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mt-3" id="item_variant_div"
                                style="display:@if (!empty($item) && $item->is_variant) block @else none @endif">
                                <table class="table table-bordered table-decor">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="80px">Delete</th>
                                            <th scope="col">Variant Name</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Image</th>
                                        </tr>
                                    </thead>
                                    <tbody id="item_variant_table_body">
                                        @if (!empty($item) && $item->is_variant)
                                            @foreach ($item->variants as $variant)
                                                <tr>
                                                    <td><img src="/assets/images/dustbin.png" alt="dustbin"
                                                            onclick="removeFunThree(this)" role="button"></td>
                                                    <td>
                                                        <input type="hidden" name="variant_id[]" value="{{ $variant->id }}">
                                                        <select class="form-select" name="variant_name[]" required>
                                                            <option value="">Select Variant</option>
                                                            @foreach ($variants as $variantType)
                                                                <option value="{{ $variantType->name }}" @if($variant->name == $variantType->name) selected @endif>{{ $variantType->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" placeholder="Quantity"
                                                            name="variant_quantity[]" class="form-control"
                                                            value="{{ $variant->quantity }}" required min="0">
                                                    </td>
                                                    <td>
                                                        <input type="text" placeholder="Price" name="variant_price[]"
                                                            class="form-control numberInput" value="{{ $variant->price }}"
                                                            step="0.01" required min="0">
                                                    </td>
                                                    <td class="d-flex align-items-center">
                                                        <input type="file" name="variant_image[]"
                                                            class="form-control me-1">
                                                        @if (!empty($variant->image))
                                                            <img src="{{ $variant->image_url }}" class="list-image">
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="row mt-4">
                                <div class="col-12 col-md-12 col-lg-12 d-flex align-items-end justify-content-end">
                                    <button type="reset" class="btn btn-secondary me-3">Reset</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if (!empty($item) && false)
                    <div class="col-12 col-md-12 col-lg-12 mt-4">
                        <div class="main-heading">
                            <h4>Ingredients</h4>
                        </div>
                    </div>
                    <form id="ingredient_add" method="POST"
                        action="{{ route('item-ingredients-add', ['itemId' => $item->id]) }}">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="main-content p-3">
                                <div class="row">
                                    <div class="col-12 col-md-4 col-lg-4">
                                        <label class="form-label" for="ingredient_id">Ingredient</label>
                                        <select class="selectpicker w-100" aria-label="Default select example"
                                            data-live-search="true" name="ingredient_id" id="ingredient_id" required>
                                            <option value="">Select Ingredient</option>
                                            @foreach ($item->remainingIngredients() as $ingredient)
                                                <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4">
                                        <label class="form-label" for="ingredient_uom">Unit of Measurement</label>
                                        <select class="selectpicker w-100" aria-label="Default select example"
                                            data-live-search="true" name="ingredient_uom" id="ingredient_uom" required>
                                            <option value="">Select Unit of Measure</option>
                                            @foreach ($unitOfMeasurements as $unitOfMeasurement)
                                                <option value="{{ $unitOfMeasurement->id }}">
                                                    {{ $unitOfMeasurement->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2 col-lg-2">
                                        <label class="form-label" for="ingredient_quantity">Quantity</label>
                                        <input type="number" placeholder="Quantity" name="ingredient_quantity"
                                            id="ingredient_quantity"class="form-control" value="" required>
                                    </div>
                                    <div class="col-12 col-md-2 col-lg-2">
                                        <label class="form-label d-block">&nbsp;</label>
                                        <button type="submit" class="btn btn-secondary  float-end mb-3">Add</button>
                                    </div>
                                </div>

                                <div class="table_scrol mt-4">
                                    <table class="table table-bordered table-decor">
                                        <thead>
                                            <tr>
                                                <th scope="col">Delete</th>
                                                <th scope="col">Ingredient Name</th>
                                                <th scope="col">Ingredient UOM</th>
                                                <th scope="col">Ingredient Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ingredient_table">
                                            @foreach ($item->itemIngredients as $itemIngredient)
                                                <tr>
                                                    <td><img src="/assets/images/dustbin.png" alt="delete"
                                                            role="button"
                                                            onclick="deleteIngredient({{ $itemIngredient->id }},this)">
                                                    </td>
                                                    <td>{{ $itemIngredient->ingredient->name }}</td>
                                                    <td>{{ $itemIngredient->uom->name }}</td>
                                                    <td>{{ $itemIngredient->quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <!--Main Section End-->
@endsection
@section('js')
    <script>
        @if (!empty($item))
            var itemingredientDeleteUrl = '{{ route('item-ingredients-delete', ['itemId' => $item->id]) }}';
        @endif
        var variantDropdown = '<input type="hidden" name="variant_id[]" value="0">';
        variantDropdown += '<select class="form-select" name="variant_name[]" required>';
        variantDropdown += '<option value="">Select Variant</option>';
        @foreach($variants as $variantType)
            variantDropdown += '<option value="{{ $variantType->name }}">{{ $variantType->name }}</option>';
        @endforeach
        variantDropdown += '</select>';
    </script>
    <script src="{{ asset('assets/js/item.js') }}?v={{ filemtime(public_path('assets/js/item.js')) }}"></script>  
@endsection
