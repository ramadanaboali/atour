@extends('admin.layouts.auth')
@php $assetsPath = asset('assets/admin'); @endphp
@section('content')
    <div class="content-body">
        <div class="auth-wrapper auth-v1 px-2">
            <div class="auth-inner py-2">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <!-- Login v1 -->
                <div class="card mb-0">
                    <div class="card-body">

            @include('flash::message')

                        <form class="auth-login-form mt-2" action="{{ route('password.save') }}" method="POST">
                            @csrf

                            <div class="mb-1 @error('code') is-invalid @enderror">
                                <label for="login-code" class="form-label">{{ __('admin.code') }}</label>
                                <input type="number" class="form-control" id="login-code" name="code" placeholder="" aria-describedby="login-code" tabindex="1" autofocus value="{{ old('code') }}" />
                                @error('code')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-1 @error('password') is-invalid @enderror">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="login-password">{{ __('admin.password') }}</label>
                                </div>
                                <div class="input-group input-group-merge form-password-toggle">
                                    <input type="password" class="form-control form-control-merge" id="login-password" name="password" tabindex="2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="login-password" />
                                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                </div>
                            </div>
                            <div>
                                @error('password')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-1">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password_confirmation">{{ __('admin.confirm_password') }}</label>
                                </div>
                                <div class="input-group input-group-merge form-password-toggle">
                                    <input type="password" class="form-control form-control-merge" id="password_confirmation" name="password_confirmation" tabindex="2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="login-password" />
                                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100" tabindex="3">{{ __('admin.reset_password') }}</button>
                        </form>
                    </div>
                </div>
                <!-- /Login v1 -->
            </div>
        </div>

    </div>

@endsection

