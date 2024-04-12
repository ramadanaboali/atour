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
                        <div class="row">
                          
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>
@stop

