@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                                <h2 class="text-white mb-0">Valuasi Order</h2>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales"
                                    data-update='{
                                        "data":{
                                            "labels": ["Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                                            "datasets":[
                                                {
                                                    "data":[5000, 0, 0, 0, 0, 0]
                                                }
                                            ]
                                        }
                                    }'
                                    data-prefix="Rp " data-suffix="k">
                                        <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                                            <span class="d-none d-md-block">Venue</span>
                                            <span class="d-md-none">V</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" data-toggle="chart" data-target="#chart-sales"
                                    data-update='{
                                        "data":{
                                            "labels": ["Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                                            "datasets":[
                                                {
                                                    "data":[500, 0, 0, 0, 0, 0]
                                                }
                                            ]
                                        }
                                    }'
                                    data-prefix="Rp " data-suffix="k">
                                        <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                                            <span class="d-none d-md-block">Produk</span>
                                            <span class="d-md-none">P</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="chart-sales" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-light ls-1 mb-1">Performa</h6>
                                <h2 class="text-white mb-0">Total order</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart" id="chart-container">
                        {{-- data-update='{
                            "labels": ['Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                            "datasets": [{
                                label: 'Sales',
                                data: [25, 20, 30, 22, 17, 29]
                            }]
                        }'> --}}
                            <canvas id="chart-orders" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush