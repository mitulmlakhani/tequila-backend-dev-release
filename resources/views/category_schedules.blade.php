@extends('layouts.master')
@section('title')
    Category Schedules
@endsection

@section('content')

        <style>
        .select2-container {
            position: relative !important;
            top: 0 !important;
            left: 0 !important;
        }
        </style>


            <!--Main Section Start-->
            <div class="wrapper home-section" id="full-width">
                <div class="container-fluid p-4">
                    <div class="row">
                        <div class="col-xl-4 col-lg-6">
                            <div class="main-heading">
                                <h4>Add Category Schedule</h4>
                            </div>
                            @include('layouts.flash-msg')
                            <div class="main-content p-3">
                                <form action="{{ route('category-schedule-create') }}" method="POST" enctype='multipart/form-data'>
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="title">Title</label>
                                        <input type="text" placeholder="Title" id="title" name="title"
                                                class="form-control max30Length" required value="{{ old('title', '') }}">
                                        @error('title')
                                            <div class="validation-error text-danger">{{ $message }}</div>
                                            @enderror
                                    </div>

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

                                    <div class="mb-3">
                                        <div class="row pt-3">
                                            <label class="form-label">Schedule</label>
                                            
                                            <div class="col-12 mt-3">
                                                <div class="d-flex align-items-center justify-content-left gap-1">
                                                    <label style="min-width: 40px;" class="form-check-label"><strong>All</strong></label>
                                                    <input style="min-width: 25px; min-height: 25px;" class="form-check-input mx-2" type="checkbox" id="all_days">
                                                    <input type="time" value="" class="form-control" id="schedule_from_time">
                                                    <input type="time" value="" class="form-control" id="schedule_to_time">
                                                </div>
                                            </div>

                                            @foreach (["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"] as $day)

                                                <div class="col-12 mt-3">
                                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                                        <label style="min-width: 40px;" class="form-check-label" for="{{ $day }}"><strong>{{ ucwords(substr($day, 0, 3)) }}</strong></label>
                                                        <input style="min-width: 25px; min-height: 25px;" class="form-check-input days_checkbox mx-2" name="days[]" type="checkbox" id="{{ $day }}" value="{{ $day }}">
                                                        <input type="time" value="{{ $openTime }}" class="form-control from-date-field" name="schedule[{{ $day }}][from_time]" id="{{ $day }}_from_time">
                                                        <input type="time" value="{{ $closeTime }}" class="form-control to-date-field" name="schedule[{{ $day }}][to_time]" id="{{ $day }}_to_time">
                                                    </div>
                                                </div>

                                            @endforeach

                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="categories">Categories</label>
                                        <select class="form-select checkbox-select" data-containerId="categories-dd-container" id="categories" name="categories[]" multiple>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                        <div id="categories-dd-container" class="dd-container"></div>
                                        @error('categories')
                                            <div class="text-danger validation-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <button id="category-add" type="reset" class="btn btn-primary">Cancel</button>
                                </form>
                            </div>
                        </div>
                        <div class="col">
                            <div class="main-content p-3">
                                <table id="category_management" class="display nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th scope="rowgroup">#</th>
                                            <th scope="rowgroup">Title</th>
                                            <th scope="rowgroup">Time</th>
                                            <th scope="rowgroup">Status</th>
                                            <th scope="rowgroup">Categories</th>
                                            <th scope="rowgroup">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="category_rows">
                                        @foreach ($schedules as $schedule)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucwords($schedule['title']) }}</td>
                                                
                                                <td>
                                                    @foreach ($schedule['schedule'] ?: [] as $day => $sch)
                                                        <span><strong style="min-width: 90px;">{{ ucwords($day) }}: </strong>{{ $sch['from'] }} - {{ $sch['to']}} </span><br />
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <div class="{{ $schedule['status'] ? 'reserved' : 'pending' }}">
                                                        <img src="{{ $schedule['status'] ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}"
                                                            class="me-2" alt="{{ $schedule['status'] ? 'reserved' : 'pending' }}">
                                                        <span>{{ $schedule['status'] ? 'Active' : 'In-active' }}</span>
                                                    </div>
                                                </td>
                                                <td class="d-flex flex-wrap gap-1">
                                                    @php echo implode(" ", array_map(function ($i) {
                                                            return '<h6 class="badge badge-sm bg-secondary">' . $i . '</h6>';
                                                        }, $schedule['categories']))
                                                    @endphp
                                                </td>
                                                <td>
                                                    <span class="me-2">
                                                        @can('category-schedule-edit')
                                                            <a aria-hidden="true" href="#" id="category-edit"
                                                                data-bs-toggle="modal" data-id="{{ $schedule['id'] }}"
                                                                data-bs-target="#category-add-modal" data-bs-whatever="@mdo">
                                                                <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                            </a>
                                                        @endcan
                                                    </span>
                                                    <span>
                                                        @can('category-schedule-delete')
                                                            <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteCategory"
                                                                data-bs-target="#deleteCategoryModal"
                                                                data-url="{{ route('category-schedule-delete', ['id' => $schedule['id']]) }}">
                                                                <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
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
            <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteRoleLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteRoleLabel">Delete Category Schedule</h5>
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
                            <a href="#" class="btn btn-primary" id="deleteCategoryBtn">Delete</a>
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

        var categoryCreateUrl = '{{ route('category-schedule-create') }}';
        var categoryUpdateUrl = '{{ route('category-schedule-edit', ':id') }}';
        var categoryDetailUrl = '{{ route('category-schedule', ':id') }}';
    </script>

    <script src="{{ asset('assets/js/category-schedule.js') }}"></script>    

    <script>
        $(document).ready(function () {
            window.initCheckboxSelect();
        })
    </script>
@endsection

