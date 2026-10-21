@extends('layouts.master') @section('content')
    <style></style>
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">

                <div class="col-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="main-heading">
                            <h4>Device Status</h4>
                            <button type="button" class="btn btn-primary" onclick="window.location.reload()">Refresh</button>
                        </div>

                        @include('layouts.flash-msg')

                        <div class="main-content p-3">
                            <div class="row">

                                <div class="col-12 mb-5 text-center">
                                    @if($settings['guacamole_device_ip'] ?? null)   
                                        <a class="btn btn-primary" target="_blank" href="{{ route('guac', ['restaurantId' => $restaurantId]) }}">Connect POS</a>
                                    @endif
                                </div>

                                <div class="col-12">
                                    <div class="d-flex flex-column gap-4">
                                        @foreach ($devices as $type => $deviceList)
                                            <div class="d-flex gap-2 align-items-center justify-content-center flex-wrap">
                                                @foreach ($deviceList as $device)
                                                    <div class="card text-center {{ ($device['connection_status'] ?? 0) == 1 ? 'bg-success' : 'bg-danger' }} text-white"
                                                        style="width: 120px; min-height: 120px;">
                                                        <div class="card-header p-1">{{ ucfirst($device['type']) }}</div>

                                                        <div class="card-body p-1 mx-2" style="font-size: 14px;">
                                                            @if ($device['type'] == "printer" || $device['type'] == "kds")
                                                                <i style="font-size: 50px;" class="bx bx-printer "></i>
                                                            @elseif($device['type'] == "pos")
                                                                <i style="font-size: 50px;" class="bx bx-desktop "></i>
                                                            @elseif($device['type'] == "terminal")
                                                                <i style="font-size: 50px;" class="bx bx-credit-card "></i>
                                                            @endif
                                                            <br>
                                                            {{ $device['name'] ?? '' }}
                                                            <br>
                                                            {{ $device['ip_address'] ?? '-' }}
                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
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