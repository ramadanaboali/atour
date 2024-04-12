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
                        <a href="#" class="brand-logo">
                            <img src="{{ $assetsPath }}/images/logo.png" height="40">
                        </a>

                        <form class="auth-login-form mt-2" action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="mb-1 @error('email') is-invalid @enderror">
                                <label for="login-email" class="form-label">{{ __('admin.email') }}</label>
                                <input type="email" class="form-control" id="login-email" name="email" placeholder="" aria-describedby="login-email" tabindex="1" autofocus value="{{ old('email') }}" />
                                @error('email')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <button class="btn btn-primary w-100" tabindex="4">{{ __('admin.send_password_reset_link') }}</button>
                        </form>
                    </div>
                </div>
                <!-- /Login v1 -->
            </div>
        </div>

    </div>

@endsection
