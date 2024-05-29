@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('admin.dashboard') }}</title>
@endsection
@section('content')
    <section id="dashboard-ecommerce">
        <div class="row match-height">

            <!-- Statistics Card -->
            <div class="col-xl-12 col-md-12 col-12">
                <div class="card card-statistics">
                    <div class="card-header">
                    <h4 class="card-title" style="color: #345c76">{{ __('admin.statistics') }}</h4>

                    </div>
                    <div class="card-body statistics-body">
                            <!-- Stats Vertical Card -->
                    <div class="row">
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="avatar bg-light-info p-50 mb-1">
                                        <div class="avatar-content">
                                            <i data-feather="users" class="font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bolder">{{ $customers }}</h2>
                                    <p class="card-text">{{ __('admin.customers') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="avatar bg-light-warning p-50 mb-1">
                                        <div class="avatar-content">
                                            <i data-feather="users" class="font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bolder">{{ $suppliers }}</h2>
                                    <p class="card-text">{{ __('admin.suppliers') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="avatar bg-light-danger p-50 mb-1">
                                        <div class="avatar-content">
                                            <i data-feather="shopping-bag" class="font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bolder">{{ $current_orders }}</h2>
                                    <p class="card-text">{{ __('admin.current_orders') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="avatar bg-light-primary p-50 mb-1">
                                        <div class="avatar-content">
                                            <i data-feather="heart" class="font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bolder">{{ $old_orders }}</h2>
                                    <p class="card-text">{{ __('admin.old_orders') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="avatar bg-light-success p-50 mb-1">
                                        <div class="avatar-content">
                                            <i data-feather="award" class="font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bolder">{{ $canceled_orders }}</h2>
                                    <p class="card-text">{{ __('admin.canceled_orders') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="avatar bg-light-danger p-50 mb-1">
                                        <div class="avatar-content">
                                            <i data-feather="truck" class="font-medium-5"></i>
                                        </div>
                                    </div>
                                    <h2 class="fw-bolder">{{ $trips }}</h2>
                                    <p class="card-text">{{ __('admin.trips') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Stats Vertical Card -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                            </div>
                            <div class="card-body">
                                <canvas class="line-chart-ex chartjs" data-height="450"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                    </div>
                </div>
            </div>

        </div>

    </section>
@stop
@push('scripts')
<script src="{{ asset('assets/admin') }}/vendors/js/charts/apexcharts.min.js"></script>
<script src="{{ asset('assets/admin') }}/vendors/js/charts/chart.min.js"></script>
@include('admin.pages.dashboard.chart')
@endpush


