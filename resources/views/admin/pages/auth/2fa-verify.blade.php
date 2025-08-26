@extends('admin.layouts.auth')

@section('title', __('security.2fa_title'))

@section('content')
<div class="auth-wrapper auth-v2">
    <div class="auth-inner row m-0">
        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
                <img class="img-fluid" src="{{ asset('app-assets/images/pages/login-v2.svg') }}" alt="Login V2" />
            </div>
        </div>
        
        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                <h2 class="card-title font-weight-bold mb-1">{{ __('security.2fa_title') }}</h2>
                <p class="card-text mb-2">{{ __('security.2fa_subtitle') }}</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form class="auth-login-form mt-2" action="{{ route('admin.2fa.verify') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label" for="code">{{ __('security.2fa_verification_code') }}</label>
                        <input class="form-control @error('code') is-invalid @enderror" 
                               id="code" 
                               type="text" 
                               name="code" 
                               placeholder="{{ __('security.2fa_code_placeholder') }}"
                               maxlength="6"
                               pattern="[0-9]{6}"
                               required
                               autofocus />
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary btn-block" type="submit">{{ __('security.2fa_verify_code') }}</button>
                </form>

                <div class="text-center mt-2">
                    <form action="{{ route('admin.2fa.resend') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-link p-0">
                            {{ __('security.2fa_didnt_receive') }} {{ __('security.2fa_resend') }}
                        </button>
                    </form>
                </div>

                <div class="text-center mt-2">
                    <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary btn-sm">
                        {{ __('security.2fa_back_to_login') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    
    // Auto-submit when 6 digits are entered
    codeInput.addEventListener('input', function() {
        if (this.value.length === 6) {
            this.form.submit();
        }
    });
    
    // Only allow numbers
    codeInput.addEventListener('keypress', function(e) {
        if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
