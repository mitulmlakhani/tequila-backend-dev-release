@extends('layouts.master')
@section('title', 'Menu Settings')

@section('content')

<div class="wrapper home-section">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="main-heading">
                    <h4>Menu Settings</h4>
                </div>
            </div>
            @include('layouts.flash-msg')

            <div class="col-8">
                <div class="main-content p-3">
                    <form action="{{ route('website.menu.settings.store') }}" method="POST">
                        @csrf
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th style="text-align: center;">
                                        Today's Special 
                                        <button type="button" class="btn btn-sm btn-warning" id="uncheck-todays-special">Uncheck All</button>
                                    </th>
                                    <th style="text-align: center;">
                                        Featured 
                                        <button type="button" class="btn btn-sm btn-warning" id="uncheck-featured">Uncheck All</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menuItems as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->category->category_name ?? 'N/A' }}</td> 
                                        <td class="text-center">
                                            <input type="checkbox" class="todays-special-checkbox" name="todays_special[]" value="{{ $item->id }}" 
                                                {{ in_array($item->id, $settings->todays_special ?? []) ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="featured-checkbox" name="featured_menus[]" value="{{ $item->id }}" 
                                                {{ in_array($item->id, $settings->featured_menus ?? []) ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('uncheck-todays-special').addEventListener('click', function() {
        document.querySelectorAll('.todays-special-checkbox').forEach(checkbox => checkbox.checked = false);
    });

    document.getElementById('uncheck-featured').addEventListener('click', function() {
        document.querySelectorAll('.featured-checkbox').forEach(checkbox => checkbox.checked = false);
    });
</script>

@endsection
