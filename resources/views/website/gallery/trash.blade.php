@extends('layouts.master')
@section('title', 'Trashed Gallery Images')
@section('content')

<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="main-heading">
                    <h4>Trashed Gallery Images</h4>
                </div>
            </div>

            @include('layouts.flash-msg')

            <div class="col-12">
                <div class="main-content p-3">
                    <a href="{{ route('website.gallery') }}" class="btn btn-primary mb-3">Back to Gallery</a>

                    @if($galleries->isEmpty())
                        <p class="text-center text-muted">No trashed images found.</p>
                    @else
                        <div class="row">
                            @foreach ($galleries as $gallery)
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <img src="{{ asset($gallery->image) }}" class="card-img-top" alt="Gallery Image">
                                        <div class="card-body text-center">
                                            <form action="{{ route('website.gallery.restore', $gallery->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                            </form>

                                            <form action="{{ route('website.gallery.forceDelete', $gallery->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure? This will permanently delete the image.')">
                                                    Delete Permanently
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
