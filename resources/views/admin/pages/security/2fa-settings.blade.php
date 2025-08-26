@extends('admin.layouts.master')

@section('title')
<title>{{ config('app.name') }} | {{ __('security.2fa_title') }} - {{ __('security.settings') }}</title>
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{ __('security.2fa_title') }}</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('admin.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.security.dashboard') }}">{{ __('security.security') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('security.2fa_title') }} {{ __('security.settings') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('security.2fa_title') }} {{ __('security.settings') }}</h4>
                </div>
                
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <h5>{{ __('security.2fa_current_status') }}</h5>
                            <p class="mb-3">
                                {{ __('security.2fa_title') }} {{ __('admin.is_currently') }} 
                                @if($user->two_factor_enabled)
                                    <span class="alert alert-success">{{ __('security.2fa_enabled') }}</span>
                                @else
                                    <span class="alert alert-danger">{{ __('security.2fa_disabled') }}</span>
                                @endif
                            </p>

                            @if($user->two_factor_enabled)
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i data-feather="shield" class="mr-1"></i>
                                        {{ __('security.2fa_enhanced_security') }}
                                    </h6>
                                    <p class="mb-0">
                                        {{ __('security.2fa_enhanced_description') }}
                                    </p>
                                </div>

                                <form action="{{ route('admin.security.2fa.disable') }}" method="POST" class="mt-3">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('{{ __('security.2fa_disable_confirm') }}')">
                                        <i data-feather="shield-off"></i>
                                        {{ __('security.2fa_disable_button') }}
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading">
                                        <i data-feather="alert-triangle" class="mr-1"></i>
                                        {{ __('security.2fa_enhance_security') }}
                                    </h6>
                                    <p class="mb-0">
                                        {{ __('security.2fa_enhance_description') }}
                                    </p>
                                </div>

                                <form action="{{ route('admin.security.2fa.enable') }}" method="POST" class="mt-3">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i data-feather="shield"></i>
                                        {{ __('security.2fa_enable_button') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i data-feather="shield" class="font-large-2 text-primary mb-2"></i>
                                    <h5>{{ __('security.2fa_how_it_works') }}</h5>
                                    <ol class="text-left small">
                                        <li>{{ __('security.2fa_step_1') }}</li>
                                        <li>{{ __('security.2fa_step_2') }}</li>
                                        <li>{{ __('security.2fa_step_3') }}</li>
                                    </ol>
                                    <p class="small text-muted mb-0">
                                        {{ __('security.2fa_code_expires') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($user->two_factor_enabled)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5>{{ __('security.security_info') }}</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>{{ __('security.last_verification') }}:</strong></td>
                                            <td>
                                                @if($user->two_factor_verified_at)
                                                    {{ $user->two_factor_verified_at->format('Y-m-d H:i:s') }}
                                                    <small class="text-muted">({{ $user->two_factor_verified_at->diffForHumans() }})</small>
                                                @else
                                                    {{ __('security.never') }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('security.failed_login_attempts') }}:</strong></td>
                                            <td>
                                                {{ $user->failed_login_attempts }}
                                                @if($user->failed_login_attempts > 0)
                                                    <span class="badge badge-warning">{{ $user->failed_login_attempts }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('security.account_status') }}:</strong></td>
                                            <td>
                                                @if($user->isAccountLocked())
                                                    <span class="badge badge-danger">{{ __('security.locked_until') }} {{ $user->locked_until->format('Y-m-d H:i:s') }}</span>
                                                @else
                                                    <span class="badge badge-success">{{ __('security.active') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
