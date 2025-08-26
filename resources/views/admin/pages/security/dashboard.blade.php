@extends('admin.layouts.master')

@section('title')
<title>{{ config('app.name') }} | {{ __('security.security_dashboard') }}</title>
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{ __('security.security_dashboard') }}</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('admin.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('security.security_dashboard') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- Security Statistics Cards -->
    <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="font-weight-bolder mb-0">{{ number_format($stats['total_login_attempts']) }}</h3>
                            <p class="card-text">{{ __('security.total_login_attempts') }}</p>
                        </div>
                        <div class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="log-in" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="font-weight-bolder mb-0 text-success">{{ number_format($stats['successful_login_attempts']) }}</h3>
                            <p class="card-text">{{ __('security.successful_logins') }}</p>
                        </div>
                        <div class="avatar bg-light-success p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="check-circle" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="font-weight-bolder mb-0 text-danger">{{ number_format($stats['failed_login_attempts']) }}</h3>
                            <p class="card-text">{{ __('security.failed_logins') }}</p>
                        </div>
                        <div class="avatar bg-light-danger p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="x-circle" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="font-weight-bolder mb-0">{{ number_format($stats['total_activities']) }}</h3>
                            <p class="card-text">{{ __('security.user_activities') }}</p>
                        </div>
                        <div class="avatar bg-light-info p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="activity" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="font-weight-bolder mb-0">{{ number_format($stats['users_with_2fa']) }}</h3>
                            <p class="card-text">{{ __('security.users_with_2fa') }}</p>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="shield" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="font-weight-bolder mb-0 text-warning">{{ number_format($stats['locked_accounts']) }}</h3>
                            <p class="card-text">{{ __('security.locked_accounts') }}</p>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="lock" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('security.login_attempts_chart') }}</h4>
                </div>
                <div class="card-body">
                    <canvas id="loginAttemptsChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">{{ __('security.recent_failed_attempts') }}</h4>
                    <a href="{{ route('admin.security.login-attempts') }}" class="btn btn-sm btn-outline-primary">{{ __('security.view_all') }}</a>
                </div>
                <div class="card-body">
                    @if($recentFailedAttempts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('security.email') }}</th>
                                        <th>{{ __('security.ip_address') }}</th>
                                        <th>{{ __('admin.time') }}</th>
                                        <th>{{ __('security.failure_reason') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentFailedAttempts as $attempt)
                                    <tr>
                                        <td>{{ $attempt->email }}</td>
                                        <td>{{ $attempt->ip_address }}</td>
                                        <td>{{ $attempt->attempted_at->diffForHumans() }}</td>
                                        <td><span class="badge badge-danger">{{ $attempt->failure_reason }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">{{ __('security.no_recent_failed_attempts') }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">{{ __('security.recent_activities') }}</h4>
                    <a href="{{ route('admin.security.audit-trail') }}" class="btn btn-sm btn-outline-primary">{{ __('security.view_all') }}</a>
                </div>
                <div class="card-body">
                    @if($recentActivities->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>{{ __('security.user') }}</th>
                                        <th>{{ __('security.action') }}</th>
                                        <th>{{ __('security.description') }}</th>
                                        <th>{{ __('admin.time') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivities as $activity)
                                    <tr>
                                        <td>{{ $activity->user->name ?? 'N/A' }}</td>
                                        <td><span class="badge badge-info">{{ ucfirst($activity->action_type) }}</span></td>
                                        <td>{{ Str::limit($activity->description, 30) }}</td>
                                        <td>{{ $activity->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">{{ __('security.no_recent_activities') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Login Attempts Chart
    const ctx = document.getElementById('loginAttemptsChart').getContext('2d');
    const chartData = @json($loginAttemptsByDay);
    
    const labels = chartData.map(item => item.date);
    const totalData = chartData.map(item => item.total);
    const successfulData = chartData.map(item => item.successful);
    const failedData = chartData.map(item => item.total - item.successful);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Attempts',
                    data: totalData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Successful',
                    data: successfulData,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Failed',
                    data: failedData,
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection
