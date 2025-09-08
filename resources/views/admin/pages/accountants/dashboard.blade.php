@extends('admin.layouts.master')

@section('title', __('admin.accountants_dashboard'))

@section('content')
<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{ __('admin.accountants_dashboard') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.accountants.dashboard') }}">{{ __('admin.dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('admin.accountants') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <!-- Financial Overview Cards -->
        <div class="row match-height">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-start pb-0">
                        <div>
                            <h2 class="font-weight-bolder">${{ number_format($totalRevenue, 2) }}</h2>
                            <p class="card-text">{{ __('admin.total_revenue') }}</p>
                        </div>
                        <div class="avatar bg-light-success p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="trending-up" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="font-small-2 text-muted">{{ __('admin.this_month') }}: ${{ number_format($monthlyRevenue, 2) }}</span>
                        </div>
                        @if($revenueGrowth > 0)
                            <div class="badge badge-light-success">+{{ number_format($revenueGrowth, 1) }}%</div>
                        @elseif($revenueGrowth < 0)
                            <div class="badge badge-light-danger">{{ number_format($revenueGrowth, 1) }}%</div>
                        @else
                            <div class="badge badge-light-secondary">0%</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-start pb-0">
                        <div>
                            <h2 class="font-weight-bolder">${{ number_format($totalCommissions, 2) }}</h2>
                            <p class="card-text">{{ __('admin.total_commissions') }}</p>
                        </div>
                        <div class="avatar bg-light-info p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="percent" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="font-small-2 text-muted">{{ __('admin.this_month') }}: ${{ number_format($monthlyCommissions, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-start pb-0">
                        <div>
                            <h2 class="font-weight-bolder">${{ number_format($pendingPayouts, 2) }}</h2>
                            <p class="card-text">{{ __('admin.pending_payouts') }}</p>
                        </div>
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="clock" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="font-small-2 text-muted">{{ __('admin.awaiting_payment') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-start pb-0">
                        <div>
                            <h2 class="font-weight-bolder">{{ number_format($totalTransactions) }}</h2>
                            <p class="card-text">{{ __('admin.total_transactions') }}</p>
                        </div>
                        <div class="avatar bg-light-primary p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="credit-card" class="font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="font-small-2 text-muted">{{ __('admin.this_month') }}: {{ number_format($monthlyTransactions) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row match-height">
            <!-- Revenue Chart -->
            <div class="col-lg-8 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-sm-center align-items-start flex-sm-row flex-column">
                        <div class="header-left">
                            <h4 class="card-title">{{ __('admin.revenue_analytics') }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="400"></canvas>
                    </div>
                </div>
            </div>

            <!-- User Statistics -->
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('admin.user_statistics') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-light-primary me-2">
                                    <div class="avatar-content">
                                        <i data-feather="users" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <span>{{ __('admin.active_clients') }}</span>
                            </div>
                            <div class="font-weight-bolder">{{ number_format($activeClients) }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-light-success me-2">
                                    <div class="avatar-content">
                                        <i data-feather="briefcase" class="avatar-icon"></i>
                                    </div>
                                </div>
                                <span>{{ __('admin.active_suppliers') }}</span>
                            </div>
                            <div class="font-weight-bolder">{{ number_format($activeSuppliers) }}</div>
                        </div>

                        <!-- Payment Methods Distribution -->
                        <hr>
                        <h6 class="mb-1">{{ __('admin.payment_methods') }}</h6>
                        @foreach($paymentMethods as $method)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-capitalize">{{ $method->payment_method ?? __('admin.unknown') }}</span>
                            <span class="font-weight-bolder">{{ $method->count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Suppliers and Recent Orders -->
        <div class="row match-height">
            <!-- Top Performing Suppliers -->
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('admin.top_suppliers') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('admin.supplier') }}</th>
                                        <th>{{ __('admin.orders') }}</th>
                                        <th>{{ __('admin.earnings') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topSuppliers as $supplier)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-1">
                                                    <img src="{{ $supplier->image_url ?? asset('assets/admin/images/avatars/1.png') }}" alt="Avatar" height="32" width="32">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $supplier->name }}</h6>
                                                    <small class="text-muted">{{ $supplier->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $supplier->completed_orders ?? 0 }}</td>
                                        <td class="font-weight-bolder text-success">${{ number_format($supplier->total_earnings ?? 0, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('admin.recent_orders') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('admin.order_id') }}</th>
                                        <th>{{ __('admin.client') }}</th>
                                        <th>{{ __('admin.amount') }}</th>
                                        <th>{{ __('admin.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <span class="font-weight-bolder">#{{ $order->id }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $order->client->name ?? __('admin.unknown') }}</h6>
                                                <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </td>
                                        <td class="font-weight-bolder">${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-light-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($monthlyRevenueChart);
    
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => item.month),
            datasets: [{
                label: '{{ __("admin.revenue") }}',
                data: revenueData.map(item => item.revenue),
                borderColor: '#7367f0',
                backgroundColor: 'rgba(115, 103, 240, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            elements: {
                point: {
                    radius: 4,
                    hoverRadius: 6
                }
            }
        }
    });
});
</script>
@endsection
