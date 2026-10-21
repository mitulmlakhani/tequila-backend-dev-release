@extends('layouts.master')
@section('title', 'Restaurant Settings')
@section('content')
        <div class="wrapper home-section" id="full-width">
            <div class="container-fluid p-4">
                <div class="row">
                    <div class="col-12">
                        <div class="main-heading">
                            <h4>Restaurant Settings</h4>
                        </div>
                    </div>
                    @include('layouts.flash-msg')

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>There were some problems with your input:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="col-12">
                        <div class="main-content p-3">
                            <form action="{{ route('settings.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Accordion for settings -->
                                <div class="accordion" id="settingsAccordion">
                                    <!-- Items Display Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="itemsHeading">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#itemsCollapse" aria-expanded="true" aria-controls="itemsCollapse">
                                                Items Display Settings
                                            </button>
                                        </h2>
                                        <div id="itemsCollapse" class="accordion-collapse collapse show" aria-labelledby="itemsHeading" data-bs-parent="#settingsAccordion">
                                            <div class="accordion-body">
                                                @include('partials.settings-module', [
        'module' => 'item',
        'settings' => $settings['items_display'] ?? []
    ])
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Categories Display Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="categoriesHeading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#categoriesCollapse" aria-expanded="false" aria-controls="categoriesCollapse">
                                                Categories Display Settings
                                            </button>
                                        </h2>
                                        <div id="categoriesCollapse" class="accordion-collapse collapse" aria-labelledby="categoriesHeading" data-bs-parent="#settingsAccordion">
                                            <div class="accordion-body">
                                                @include('partials.settings-module', [
        'module' => 'cat',
        'settings' => $settings['categories_display'] ?? []
    ])
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modifiers Display Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="modifiersHeading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#modifiersCollapse" aria-expanded="false" aria-controls="modifiersCollapse">
                                                Modifiers Display Settings
                                            </button>
                                        </h2>
                                        <div id="modifiersCollapse" class="accordion-collapse collapse" aria-labelledby="modifiersHeading" data-bs-parent="#settingsAccordion">
                                            <div class="accordion-body">
                                                @include('partials.settings-module', [
        'module' => 'mod',
        'settings' => $settings['modifiers_display'] ?? []
    ])
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modifier Groups Display Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="modifierGroupsHeading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#modifierGroupsCollapse" aria-expanded="false" aria-controls="modifierGroupsCollapse">
                                                Modifier Groups Display Settings
                                            </button>
                                        </h2>
                                        <div id="modifierGroupsCollapse" class="accordion-collapse collapse" aria-labelledby="modifierGroupsHeading" data-bs-parent="#settingsAccordion">
                                            <div class="accordion-body">
                                                @include('partials.settings-module', [
        'module' => 'mod_group',
        'settings' => $settings['modifier_groups_display'] ?? []
    ])
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CC Payment Settings Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="ccPaymentHeading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ccPaymentCollapse" aria-expanded="false" aria-controls="ccPaymentCollapse">
                                                Credit Card Payment Settings
                                            </button>
                                        </h2>
                                        <div id="ccPaymentCollapse" class="accordion-collapse collapse" aria-labelledby="ccPaymentHeading" data-bs-parent="#settingsAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="protocol1" class="form-label">Protocol 1</label>
                                                            <input type="text" name="protocol1" id="protocol1" class="form-control" value="{{ $settings['cc_payment']['protocol1'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="protocol2" class="form-label">Protocol 2</label>
                                                            <input type="text" name="protocol2" id="protocol2" class="form-control" value="{{ $settings['cc_payment']['protocol2'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <!-- Credit Card Fee Label -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="cc_label" class="form-label">Credit Card Fee Label</label>
                                                            <input type="text" name="cc_label" id="cc_label" class="form-control" value="{{ $settings['cc_payment']['cc_label'] ?? 'Credit Card Fee' }}">
                                                            <small class="text-muted">Example: *Credit Card Fee, Surcharge, Transaction Fee*</small>
                                                        </div>
                                                    </div>
                                                    <!-- Global Credit Card Fee (Disabled) -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Super Admin Defined CC Fee</label>
                                                            <input type="text" class="form-control" value="{{ $globalFee ?? 0 }}%" disabled>
                                                            <small class="text-muted">This fee is set by the Super Admin and cannot be changed.</small>
                                                        </div>
                                                    </div>
                                                    <!-- Cashback pectange -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Cashback (Percentage %)</label>
                                                            <input type="text" name="cashback" id="cashback" class="form-control" value="{{ $settings['cc_payment']['cashback'] ?? 0 }}">
                                                            <small class="text-muted">Percantage of cashback in case of cash payment.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Service Charge Settings Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="serviceChargeHeading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#serviceChargeCollapse" aria-expanded="false" aria-controls="serviceChargeCollapse">
                                                Service Charge Settings
                                            </button>
                                        </h2>
                                        <div id="serviceChargeCollapse" class="accordion-collapse collapse" aria-labelledby="serviceChargeHeading" data-bs-parent="#settingsAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="service_charge_percentage" class="form-label">Service Charge (%)</label>
                                                            <input type="number" name="service_charge_percentage" id="service_charge_percentage" class="form-control" value="{{ $settings['service_charge']['service_charge_percentage'] ?? 5 }}" min="0" max="100" step="0.01">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="service_charge_amount" class="form-label">Service Charge ($)</label>
                                                            <input type="number" name="service_charge_amount" id="service_charge_amount" class="form-control" value="{{ $settings['service_charge']['service_charge_amount'] ?? 5 }}" min="0" max="100" step="0.01">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Printer Server Settings Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="printerServerHeading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#printerServerCollapse" aria-expanded="false" aria-controls="printerServerCollapse">
                                                Printer Server Settings
                                            </button>
                                        </h2>
                                        <div id="printerServerCollapse" class="accordion-collapse collapse" aria-labelledby="printerServerHeading" data-bs-parent="#settingsAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="printer_server_name" class="form-label">Server Name</label>
                                                            <input type="text" name="printer_server_name" id="printer_server_name" class="form-control" value="{{ $settings['printer_server']['printer_server_name'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="printer_server_ip" class="form-label">IP Address</label>
                                                            <input type="text" name="printer_server_ip" id="printer_server_ip" class="form-control" value="{{ $settings['printer_server']['printer_server_ip'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="printer_server_port" class="form-label">Server Port</label>
                                                            <input type="number" name="printer_server_port" id="printer_server_port" class="form-control" value="{{ $settings['printer_server']['printer_server_port'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- POS Settings Section -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="posHeading">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#posCollapse" aria-expanded="false" aria-controls="posCollapse">
                                                POS Settings
                                            </button>
                                        </h2>
                                        <div id="posCollapse" class="accordion-collapse collapse" aria-labelledby="posHeading" data-bs-parent="#settingsAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="pos_session_timeout" class="form-label">POS Session Timeout</label>
                                                            <input type="number" name="pos_session_timeout" id="pos_session_timeout" class="form-control" value="{{ $settings['pos_settings']['pos_session_timeout'] ?? 2 }}" min="0" max="100" step="0.01">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="send_deleted_items_to_kitchen" class="form-label">Send Deleted Items to Kitchen</label>
                                                            <input type="number" name="send_deleted_items_to_kitchen" id="send_deleted_items_to_kitchen" class="form-control" value="{{ $settings['pos_settings']['send_deleted_items_to_kitchen'] ?? 0 }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                @can('settings.update')
                                <button type="submit" class="btn btn-primary mt-4">Save Settings</button>
                                @endcan
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
