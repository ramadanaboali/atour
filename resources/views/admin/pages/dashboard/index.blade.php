@extends('admin.layouts.master')

@section('title')
<title>{{ config('app.name') }} | {{ __('admin.dashboard') }}</title>
@endsection

@section('content')
<section id="dashboard-ecommerce">
    <div class="row match-height">
        <div class="col-xl-12">
            <div class="card card-statistics">
                <div class="card-header">
                    <h4 class="card-title text-primary">{{ __('admin.statistics') }}</h4>
                </div>
                <div class="card-body statistics-body">
                    <div class="row">

                        {{-- Clients & Suppliers --}}
                        <x-dashboard.stat-card :route="route('admin.clients.index')" icon="users" color="info" :value="$customers" :label="__('admin.customers')" />

                        <x-dashboard.stat-card :route="route('admin.suppliers.index')" icon="users" color="warning" :value="$suppliers" :label="__('admin.suppliers')" />

                        {{-- Orders --}}
                        <x-dashboard.stat-card :route="route('admin.current_orders.index')" icon="shopping-bag" color="danger" :value="$current_orders" :label="__('admin.current_orders')" />

                        <x-dashboard.stat-card :route="route('admin.orders.index')" icon="heart" color="primary" :value="$old_orders" :label="__('admin.old_orders')" />

                        <x-dashboard.stat-card :route="route('admin.canceled_orders.index')" icon="x" color="danger" :value="$canceled_orders" :label="__('admin.canceled_orders')" />

                        {{-- Trips, Effectiveness, Gifts --}}
                        <x-dashboard.stat-card :route="route('admin.trips.index')" icon="award" color="success" :value="$trips" :label="__('admin.trips')" />

                        <x-dashboard.stat-card :route="route('admin.effectivenes.index')" icon="trending-up" color="success" :value="$effectiveness" :label="__('admin.effectivenes')" />

                        <x-dashboard.stat-card :route="route('admin.gifts.index')" icon="gift" color="success" :value="$gifts" :label="__('admin.gifts')" />

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders Statistics Chart --}}
    <div class="row match-height">
        <div class="col-xl-12">
            <div class="card card-statistics">
                <div class="card-header">
                    <h4 class="card-title text-primary">{{ __('admin.orders_statistics') }}</h4>
                </div>
                <div class="card-body statistics-body">


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Trips Chart -->
                        <h3>{{ __('admin.trips') }}</h3>
                        
                        <canvas id="tripsChart"></canvas>
                        
                        <hr>
                        <!-- Gifts Chart -->
                        <h3>{{ __('admin.gifts') }}</h3>
                        <canvas id="giftsChart"></canvas>
                        
                        <hr>
                        <!-- Effectiveness Chart -->
                        <h3>{{ __('admin.effectivenes') }}</h3>
                        <canvas id="effectivenessChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
    function renderChart(ctxId, label, data) {
        return new Chart(document.getElementById(ctxId), {
            type: 'bar'
            , data: {
                labels: ["{{ __('orders.current') }}"
                    , "{{ __('orders.completed') }}", 
                    "{{ __('orders.canceled') }}"
                ]
                , datasets: [{
                    label: label
                    , data: data
                    , backgroundColor: [
                        'rgba(54, 162, 235, 0.6)'
                        , 'rgba(75, 192, 192, 0.6)'
                        , 'rgba(255, 99, 132, 0.6)'
                    , ]
                }]
            }
            , options: {
                responsive: true
                , plugins: {
                    legend: {
                        display: false
                    }
                }
                , scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    renderChart('tripsChart', "{{ __('admin.trips') }}", @json($tripChart));
    renderChart('giftsChart', "{{ __('admin.gifts') }}", @json($giftChart));
    renderChart('effectivenessChart', "{{ __('admin.effectivenes') }}", @json($effectivenessChart));

</script>

@endpush
