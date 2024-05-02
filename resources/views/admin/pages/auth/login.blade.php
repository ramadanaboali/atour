@extends('admin.layouts.auth')
@php $assetsPath = asset('assets/admin'); @endphp
@section('content')
    <div class="content-body">
        <div class="auth-wrapper auth-v1 px-2">
            <div class="auth-inner py-2">
                <!-- Login v1 -->
                <div class="card mb-0">
                    <div class="card-body" >
            @include('flash::message')

                        <h2 class="col-md-12 text-center">Atour</h2>
                        <form class="auth-login-form mt-2" action="{{ route('admin.postLogin') }}" method="POST">
                            @csrf
                            <div class="mb-1 @error('email') is-invalid @enderror">

                                <label for="login-email" class="form-label">{{ __('admin.email') }}</label>
                                <input type="email" class="form-control" id="login-email" name="email" placeholder="" aria-describedby="login-email" tabindex="1" autofocus value="{{ old('email') }}" />
                                @error('email')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-1 @error('password') is-invalid @enderror">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="login-password">{{ __('admin.password') }}</label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">
                                            <small>{{ __('admin.forgot_password') }}</small>
                                        </a>
                                    @endif
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
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember" tabindex="3" />
                                    <label class="form-check-label" for="remember"> {{ __('admin.remember_me') }} </label>
                                </div>
                            </div>
                            <button class="btn btn-success w-100" tabindex="4">{{ __('admin.login') }}</button>
                        </form>
                    </div>
                </div>
                <!-- /Login v1 -->
            </div>
        </div>

    </div>
@endsection
