@extends('admin.layouts.master')

@section('title')
<title>{{ config('app.name') }} | {{ __('admin.dashboard') }}</title>
@endsection

@push('styles')
<style>
.modern-dashboard {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
}

.dashboard-header {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-card-modern {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 45px rgba(31, 38, 135, 0.5);
}

.stat-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-label {
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.875rem;
}

.chart-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
    position: relative;
    overflow: hidden;
}

.chart-container canvas {
    max-height: 400px;
    width: 100% !important;
    height: auto !important;
}

.chart-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 1.5rem;
    text-align: center;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 0.75rem;
    background: rgba(255, 255, 255, 0.7);
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateX(5px);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.1rem;
}

.revenue-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
}

.revenue-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.revenue-label {
    opacity: 0.9;
    font-size: 1rem;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.animate-delay-1 { animation-delay: 0.1s; }
.animate-delay-2 { animation-delay: 0.2s; }
.animate-delay-3 { animation-delay: 0.3s; }
.animate-delay-4 { animation-delay: 0.4s; }
</style>
@endpush

@section('content')
<div class="modern-dashboard">
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="dashboard-header animate-fade-in-up">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="text-white mb-2">{{ __('admin.dashboard') }}</h1>
                    <p class="text-white-50 mb-0">{{ __('admin.welcome_back') }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="text-white-50">
                        <i data-feather="calendar" class="me-2"></i>
                        {{ now()->format('F j, Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Cards -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="revenue-card animate-fade-in-up animate-delay-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="revenue-label">{{ __('admin.total_revenue') }}</div>
                            <div class="revenue-value">${{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="opacity-50">
                            <i data-feather="dollar-sign" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="revenue-card animate-fade-in-up animate-delay-2" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="revenue-label">{{ __('admin.monthly_revenue') }}</div>
                            <div class="revenue-value">${{ number_format($monthlyRevenueAmount, 2) }}</div>
                        </div>
                        <div class="opacity-50">
                            <i data-feather="trending-up" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card-modern animate-fade-in-up animate-delay-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value">{{ $customers }}</div>
                            <div class="stat-label">{{ __('admin.customers') }}</div>
                        </div>
                        <div class="text-primary opacity-75">
                            <i data-feather="users" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card-modern animate-fade-in-up animate-delay-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value">{{ $suppliers }}</div>
                            <div class="stat-label">{{ __('admin.suppliers') }}</div>
                        </div>
                        <div class="text-warning opacity-75">
                            <i data-feather="briefcase" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card-modern animate-fade-in-up animate-delay-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value">{{ $current_orders }}</div>
                            <div class="stat-label">{{ __('admin.current_orders') }}</div>
                        </div>
                        <div class="text-success opacity-75">
                            <i data-feather="shopping-bag" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card-modern animate-fade-in-up animate-delay-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-value">{{ $trips + $gifts + $effectiveness }}</div>
                            <div class="stat-label">{{ __('admin.total_services') }}</div>
                        </div>
                        <div class="text-info opacity-75">
                            <i data-feather="package" style="width: 48px; height: 48px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <!-- Revenue Trend Chart -->
            <div class="col-lg-8">
                <div class="chart-container animate-fade-in-up">
                    <h5 class="chart-title">{{ __('admin.revenue_trend') }}</h5>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            
            <!-- Service Distribution -->
            <div class="col-lg-4">
                <div class="chart-container animate-fade-in-up">
                    <h5 class="chart-title">{{ __('admin.service_distribution') }}</h5>
                    <canvas id="serviceDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Secondary Charts -->
        <div class="row">
            <!-- User Growth -->
            <div class="col-lg-6">
                <div class="chart-container animate-fade-in-up">
                    <h5 class="chart-title">{{ __('admin.user_growth') }}</h5>
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>
            
            <!-- Order Status -->
            <div class="col-lg-6">
                <div class="chart-container animate-fade-in-up">
                    <h5 class="chart-title">{{ __('admin.order_status') }}</h5>
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="row">
            <!-- Recent Activity -->
            <div class="col-lg-8">
                <div class="chart-container animate-fade-in-up">
                    <h5 class="chart-title">{{ __('admin.recent_activity') }}</h5>
                    <div class="activity-list">
                        @forelse($recentActivity as $activity)
                            <div class="activity-item">
                                <div class="activity-icon bg-{{ $activity['color'] }} text-white">
                                    <i data-feather="{{ $activity['icon'] }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">{{ $activity['message'] }}</div>
                                    <small class="text-muted">{{ $activity['time'] }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i data-feather="inbox" class="mb-2"></i>
                                <p>{{ __('admin.no_recent_activity') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Top Suppliers -->
            <div class="col-lg-4">
                <div class="chart-container animate-fade-in-up">
                    <h5 class="chart-title">{{ __('admin.top_suppliers') }}</h5>
                    <div class="supplier-list">
                        @forelse($topSuppliers as $index => $supplier)
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background: rgba(102, 126, 234, 0.1);">
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-primary rounded-circle me-3" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $supplier['name'] }}</div>
                                        <small class="text-muted">{{ $supplier['total_services'] }} {{ __('admin.services') }}</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i data-feather="users" class="mb-2"></i>
                                <p>{{ __('admin.no_suppliers') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js default configuration
    Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
    Chart.defaults.color = '#6c757d';
    Chart.defaults.borderColor = 'rgba(0,0,0,0.1)';
    Chart.defaults.backgroundColor = 'rgba(102, 126, 234, 0.1)';

    // Modern color palette
    const colors = {
        primary: '#667eea',
        secondary: '#764ba2',
        success: '#11998e',
        info: '#38ef7d',
        warning: '#f093fb',
        danger: '#f093fb'
    };

    // Revenue Trend Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($monthlyRevenue['labels']),
            datasets: [{
                label: '{{ __('admin.revenue') }}',
                data: @json($monthlyRevenue['data']),
                borderColor: colors.primary,
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Service Distribution Chart
    const serviceCtx = document.getElementById('serviceDistributionChart').getContext('2d');
    new Chart(serviceCtx, {
        type: 'doughnut',
        data: {
            labels: ['{{ __('admin.trips') }}', '{{ __('admin.gifts') }}', '{{ __('admin.effectivenes') }}'],
            datasets: [{
                data: [@json($serviceDistribution['trips']), @json($serviceDistribution['gifts']), @json($serviceDistribution['effectiveness'])],
                backgroundColor: [colors.primary, colors.success, colors.warning],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'bar',
        data: {
            labels: @json($userGrowth['labels']),
            datasets: [
                {
                    label: '{{ __('admin.customers') }}',
                    data: @json($userGrowth['customers']),
                    backgroundColor: colors.primary,
                    borderRadius: 8,
                    borderSkipped: false
                },
                {
                    label: '{{ __('admin.suppliers') }}',
                    data: @json($userGrowth['suppliers']),
                    backgroundColor: colors.success,
                    borderRadius: 8,
                    borderSkipped: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Order Status Chart
    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const tripData = @json(array_values($tripChart));
    new Chart(orderStatusCtx, {
        type: 'polarArea',
        data: {
            labels: ['{{ __('orders.current') }}', '{{ __('orders.completed') }}', '{{ __('orders.canceled') }}'],
            datasets: [{
                data: tripData,
                backgroundColor: [
                    'rgba(102, 126, 234, 0.8)',
                    'rgba(17, 153, 142, 0.8)',
                    'rgba(240, 147, 251, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                }
            }
        }
    });

    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endpush
