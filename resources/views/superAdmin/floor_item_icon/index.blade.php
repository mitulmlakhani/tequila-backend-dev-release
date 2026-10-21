@extends('layouts.master')
@section('title', 'Item Icons Management')

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid py-4">
        <div class="main-heading mb-4">
            <h4>Item Icons Management</h4>
        </div>
        <div class="row">
            <!-- Add/Edit Payment Method Form -->
            <div class="col-lg-4 col-md-12 mb-4">
                <div class="main-content p-4 bg-white shadow-sm rounded">
                    <form 
                        @if($floorItemIcon ?? null)
                            action="{{ route('floor-item-icons.update', $floorItemIcon->id) }}" 
                        @else
                            action="{{ route('floor-item-icons.store') }}" 
                        @endif
                        id="floor-item-icon-form" 
                        method="POST" 
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" required value="{{ old('name', $floorItemIcon->name ?? '') }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon</label>
                            <input type="file" class="form-control" id="icon" name="icon" placeholder="Icon">
                            @error('icon')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @if($floorItemIcon ?? null)
                                <img class="mt-3" src="{{ asset($floorItemIcon->icon_url) }}" alt="{{ $floorItemIcon->name }}" width="50" height="50">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            @if($floorItemIcon ?? null) Update @else Save @endif</button>
                    </form>
                </div>
            </div>

            <!-- Floor Item Icons Table -->
            <div class="col-lg-8 col-md-12">
                <div class="main-content p-4 bg-white shadow-sm rounded">
                    <table id="floor-item-icons-table" class="table table-striped table-bordered nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($floorItemIcons as $floorItemIcon)
                                <tr>
                                    <td class="d-flex align-items-center">
                                        <img class="img" src="{{ asset($floorItemIcon->icon_url) }}" alt="{{ $floorItemIcon->name }}" height="75">
                                        <span class="ms-2 fs-4 fw-bold">{{ $floorItemIcon->name }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('floor-item-icons.edit', $floorItemIcon->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="{{ route('floor-item-icons.destroy', $floorItemIcon->id) }}" class="btn btn-danger btn-sm">Delete</a>
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
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $('#floor-item-icons-table').DataTable();
    });
</script>
@endsection