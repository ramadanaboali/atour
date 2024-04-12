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
                    <form class="form-horizontal" method="POST" action="{{ route('admin.profile.update_password') }}">
                        @csrf
                        <div class="form-group row @error('password') is-invalid @enderror">
                            <label for="password" class="col-sm-2 col-form-label">{{ __('profile.password') }}</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password" type="text" value="">
                                @error('password')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="form-group row @error('password_confirmation') is-invalid @enderror">
                            <label for="password_confirmation" class="col-sm-2 col-form-label">{{ __('profile.password_confirmation') }}</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password_confirmation" type="text" value="">
                                @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
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
