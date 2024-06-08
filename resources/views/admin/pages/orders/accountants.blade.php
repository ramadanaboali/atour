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
                    <h4 class="card-title" style="color: #345c76">{{ __('admin.total_amount_reservasion') }}</h4>

                    </div>
                    <div class="card-body statistics-body">
                            <!-- Stats Vertical Card -->
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-6 text-center">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <div class="avatar bg-light-info p-50 mb-1">
                                            <div class="avatar-content">
                                                <i data-feather="dollar-sign" class="font-medium-5"></i>
                                            </div>
                                        </div>
                                        <h2 class="fw-bolder">{{ $total }}</h2>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <!--/ Stats Vertical Card -->
                    </div>
                </div>
            </div>
        </div>


    </section>
@stop


