@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | {{ __('trips.plural') }}</title>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h1 class="bold mb-0 mt-1 text-dark">
                        <i data-feather="box" class="font-medium-2"></i>
                        <span>Notifications</span>
                    </h1>
                </div>
            </div>
        </div>
        {{--  --}}
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-datatable">
                <div class="container">
                    <ul class="list-group">
                        @forelse ($notifications as $notification)
                            <li class="list-group-item">
                                <strong>{{ $notification->data['title'] }}</strong><br>
                                {{ $notification->data['message'] }}<br>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item">No notifications</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop


