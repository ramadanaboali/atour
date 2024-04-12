@extends('admin.layouts.auth')
@php $assetsPath = asset('assets/admin'); @endphp
@section('content')
    <div class="content-body">
        <div class="auth-wrapper auth-v1 px-2">
            <div class="auth-inner py-2">
                {{ __('Please confirm your password before continuing.') }}
                <!-- Login v1 -->
                <div class="card mb-0">
                    <div class="card-body">
                        <a href="#" class="brand-logo">
                            <img src="{{ $assetsPath }}/images/logo.png" height="40">
                        </a>

                        <form class="auth-login-form mt-2" action="{{ route('password.confirm') }}" method="POST">
                            @csrf
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
                            <button class="btn btn-primary w-100" tabindex="3">{{ __('admin.reset_password') }}</button>
                        </form>
                    </div>
                </div>
                <!-- /Login v1 -->
            </div>
        </div>

    </div>

@endsection

