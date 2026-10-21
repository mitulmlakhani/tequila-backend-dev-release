@extends('layouts.master')
@section('title', 'Gallery Management')

@section('content')

<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="main-heading">
                    <h4>Gallery Management</h4>
                </div>
            </div>
            @include('layouts.flash-msg')

            <div class="col-12">
                <div class="main-content p-3">
                    <form action="{{ route('website.gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="section">
                            <h5>Upload New Images (Max: 5)</h5>
                            <div class="mb-3">
                                <label for="images" class="form-label">Choose Images</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="main-content p-3">
                    <h5>Existing Gallery Images</h5>
                    <div class="row">
                        @foreach($galleries as $gallery)
                            <div class="col-md-3">
                                <div class="card">
                                <img src="{{ asset($gallery->image) }}" class="card-img-top" alt="Gallery Image">

                                    <div class="card-body text-center">
                                        <form action="{{ route('website.gallery.destroy', $gallery->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($galleries->isEmpty())
                        <p class="text-center mt-3">No images uploaded yet.</p>
                    @endif
                </div>
            </div>

            <div class="col-12 mt-4">
                <a href="{{ route('website.gallery.trash') }}" class="btn btn-danger">View Trashed Images</a>
            </div>
        </div>
    </div>
</div>

@endsection
