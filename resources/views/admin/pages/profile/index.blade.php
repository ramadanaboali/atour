@extends('admin.layouts.master')
@section('title')
    <title>{{ config('app.name') }} | البروفايل</title>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ auth()->user()->name }}</h3>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                             <a class="bold text-dark" href="{{ route('admin.profile.index') }}">{{ __('profile.profile') }}</a>
                        </li>
                        <li class="list-group-item">
                            <a class="bold text-dark" href="{{ route('admin.profile.change_password') }}">{{ __('profile.change_password') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        <div class="form-group row @error('name') is-invalid @enderror">
                            <label for="name" class="col-sm-2 col-form-label">{{ __('profile.name') }}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" type="text" value="{{ auth()->user()->name }}">
                                @error('name')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">{{ __('profile.email') }}</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email" id="email" value="{{ auth()->user()->email }}">
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <label for="phone" class="col-sm-2 col-form-label">{{ __('profile.phone') }}</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="phone" id="phone" value="{{ auth()->user()->phone }}">
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <div class="offset-sm-2 col-sm-10">
                                <button type="submit" class="btn btn-sm btn btn-primary me-1 waves-effect">
                                    <i class="fas fa-save"></i>
                                    <span class="active-sorting">{{ __('profile.update') }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
@stop
