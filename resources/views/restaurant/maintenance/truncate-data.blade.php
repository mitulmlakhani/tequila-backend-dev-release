@extends('layouts.master') @section('content')
    <style></style>
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">

                <div class="col-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="main-heading">
                            <div class="col-10 col-md-10 col-lg-10">
                                <h4>Maintenance</h4>
                            </div>
                            <div class="col-2 col-md-2 col-lg-2">

                            </div>
                        </div>

                        @include('layouts.flash-msg')

                        <div class="main-content p-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <form action="{{ route('settings.maintenance.save') }}" method="POST"
                                        enctype='multipart/form-data' id="maintenance-form">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Auto Remove Data Older Than X Days</label>
                                            <input type="number" step="1" name="truncate_past_data_before_days"
                                                id="truncate_past_data_before_days" class="form-control" required
                                                value="{{ old('truncate_past_data_before_days', $truncate_past_data_before_days ?? '') }}">
                                            @error('maintenance_option')
                                                <div class="text-danger validation-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save</button>

                                        @if($truncate_past_data_before_days ?? null)
                                            <a class="btn btn-danger" href="{{ route('settings.maintenance.truncate') }}">Remove Data Older Than {{ $truncate_past_data_before_days }} Days Now</a>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--Main Section End-->
@endsection