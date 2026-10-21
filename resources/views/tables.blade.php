@extends('layouts.master')
@section('title')
Elements
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>Element Management</h4>
                        @can('table-create')
                            <!-- <a href="{{ route('table-create') }}" id="table-add" data-bs-toggle="modal"
                                data-bs-target="#table-add-modal">Add Element</a> -->
                        @endcan
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table id="category_management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup"></th>
                                    <th scope="rowgroup">Name</th>
                                    <th scope="rowgroup">Floor</th>
                                    <th scope="rowgroup">Seating Capacity</th>
                                    <th scope="rowgroup">Shape</th>
                                    <th scope="rowgroup">Status</th>
                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tables as $table)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $table->table_no }}
                                        </td>
                                        <td>
                                            {{ $table->floor->name }}
                                        </td>
                                        <td>{{ $table->seating_capacity }}</td>
                                        <td>{{ ucwords($table->shape) }}</td>
                                        <td>
                                            <div class="{{ $table->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $table->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                    class="me-2" alt="{{ $table->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $table->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="me-2">
                                                @can('table-edit')
                                                    <a aria-hidden="true" href="#" id="table-edit" data-bs-toggle="modal"
                                                        data-id="{{ $table->id }}" data-bs-target="#table-add-modal">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <span>
                                                @can('table-delete')
                                                <span>
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteTable"
                                                        data-bs-target="#deleteTableModal"
                                                        data-url="{{ route('table-delete', ['id' => $table->id]) }}">
                                                        <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
                                                    </a>
                                                </span>
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

    <!--Modal Popup Add start-->
    <div class="modal fade" id="table-add-modal" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleLabel">Add Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('table-create') }}" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="floor">Floor</label>
                                <select class="form-select" id="floor_id" name="floor_id" required>
                                    <option value="">Select floor</option>
                                    @foreach ($floors as $floor)
                                        <option value="{{ $floor->id }}" @if(old('floor_id') == $floor->id) selected @endif>{{ $floor->name }}</option>
                                    @endforeach
                                </select>
                                @error('floor_id')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="type">Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    @foreach(floorElementType() as $key=>$elementType)
                                        <option value="{{$key}}" @if(old('type') == $key) selected @endif>{{$elementType}}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="table_no">Name</label>
                                <input type="text" placeholder="Table No." id="table_no" name="table_no"
                                    class="form-control max30Length" required value="{{ old('table_no') }}">
                                @error('table_no')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6" id="tableTypeContainer">
                                <label class="form-label" for="table_type">Table Type</label>
                                <select class="form-select" id="table_type" name="table_type" required>
                                    <option value="regular" @if(old('table_type', 'regular') == "regular") selected @endif>Regular</option>
                                    <option value="togo" @if(old('table_type', 'regular') == "togo") selected @endif>To Go</option>
                                </select>
                                @error('table_type')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="shape">Shape</label>
                                <select class="form-select" id="shape" name="shape" required>
                                    <option value="">Select Shape</option>
                                    <option value="circle" @if(old('shape') == "circle") selected @endif>Circle</option>
                                    <option value="rectangle" @if(old('shape') == "rectangle") selected @endif>Rectangle</option>
                                    <option value="square" @if(old('shape') == "square") selected @endif>Square</option>
                                    <option value="bar" @if(old('shape') == "bar") selected @endif>Bar</option>
                                    <option value="triangle" @if(old('shape') == "triangle") selected @endif>Triangle</option>
                                </select>
                                @error('shape')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6 hide">
                                <label class="form-label" for="seating_capacity">Seating Capacity </label>
                                <input type="number" placeholder="Seating Capacity" id="seating_capacity"
                                    name="seating_capacity" class="form-control" value="{{ old('seating_capacity') }}">
                                <input type="hidden" name="seating_capacity" value="0">
                                @error('seating_capacity')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="status">Status </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1" {{ old("status") == 1 ? "selected":"" }}>Active</option>
                                    <option value="0" {{ old("status") == 0 ? "selected":"" }}>In-active</option>
                                </select>
                                @error('status')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="image">Image </label>
                                <input type="file" id="image" name="image" class="form-control">
                                @error('image')
                                    <div class="validation-error text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label for="bg_color" class="form-label">Background Color</label>
                                <input type="color" class="form-control form-control-color" name="bg_color" id="bg_color" value="#563d7c" title="Choose color">
                                @error('bg_color')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-5 col-lg-6">
                                <img src="" class="list-image" id="edit_image">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Modal Popup Add end-->

    <!--Delete Modal Popup Start-->
    <div class="modal fade" id="deleteTableModal" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Table</h5>
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
                    <a href="#" class="btn btn-primary" id="deleteTableBtn">Delete</a>
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

        var tableCreateUrl = '{{ route('table-create') }}';
        var tableUpdateUrl = '{{ route('table-edit', ':id') }}';
        var tableDetailUrl = '{{ route('table', ':id') }}';
    </script>

    <script src="{{ asset('assets/js/table.js') }}"></script>    
@endsection
