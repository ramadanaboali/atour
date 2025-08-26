@extends('admin.layouts.master')

@section('title')
<title>{{ config('app.name') }} | {{ __('security.2fa_setup_title') }}</title>
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{ __('security.2fa_setup_title') }}</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('admin.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.security.dashboard') }}">{{ __('security.security') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.security.2fa.settings') }}">{{ __('security.2fa_title') }} {{ __('security.settings') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('security.setup') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="card-title">{{ __('security.2fa_verify_email') }}</h4>
                    <p class="card-text">{{ __('security.2fa_setup_subtitle') }}</p>
                </div>
                
                <div class="card-body">
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

                    <form action="{{ route('admin.security.2fa.verify-setup') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label" for="code">{{ __('security.2fa_verification_code') }}</label>
                            <input class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
                                   id="code" 
                                   type="text" 
                                   name="code" 
                                   placeholder="{{ __('security.2fa_code_placeholder') }}"
                                   maxlength="6"
                                   pattern="[0-9]{6}"
                                   style="letter-spacing: 10px; font-size: 24px;"
                                   required
                                   autofocus />
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-primary btn-block btn-lg" type="submit">
                            <i data-feather="shield"></i>
                            {{ __('security.2fa_enable_button') }}
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-muted">{{ __('security.2fa_didnt_receive') }}</p>
                        <a href="{{ route('admin.security.2fa.enable') }}" class="btn btn-outline-secondary btn-sm">
                            {{ __('security.2fa_resend') }}
                        </a>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('admin.security.2fa.settings') }}" class="btn btn-link">
                            {{ __('security.2fa_setup_cancel') }}
                        </a>
                    </div>
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
