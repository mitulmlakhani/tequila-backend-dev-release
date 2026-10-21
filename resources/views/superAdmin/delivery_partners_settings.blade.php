@extends('layouts.master')
@section('title', 'Admin Settings')
@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="main-heading">
                    <h4>Delivery Partners Settings</h4>
                </div>
            </div>
            @include('layouts.flash-msg')
            {{-- {{ dd($errors) }} --}}
            <div class="col-12 col-md-12 col-lg-12">
                <div class="main-content p-3">
                    <form action="{{ route('admin_settings.ubereats-save') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" class="form-control" name="module" value="delivery_partners">
                        <div class="section">
                            <h5>UberEats Settings</h5>
                            <hr />

                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label" for="ubereats_client_id">UberEats Application ID</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="ubereats_client_id_label" name="ubereats_client_id_label" placeholder="UberEats Application ID Setting Label" value="{{ $settings['ubereats_client_id']['label'] ?? 'UberEats Application ID' }}" />
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="ubereats_client_id" name="ubereats_client_id" placeholder="UberEats Client ID" value="{{ $settings['ubereats_client_id']['value'] ?? 4 }}" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label" for="ubereats_client_secret">UberEats Client Secret</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="ubereats_client_secret_label" name="ubereats_client_secret_label" placeholder="UberEats Client Secret Setting Label" value="{{ $settings['ubereats_client_secret']['label'] ?? 'UberEats Client Secret' }}" />
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="ubereats_client_secret" name="ubereats_client_secret" placeholder="UberEats Client Secret" value="{{ $settings['ubereats_client_secret']['value'] ?? "" }}" />
                                </div>
                            </div>

                        </div>
                        @can('settings.update')
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection