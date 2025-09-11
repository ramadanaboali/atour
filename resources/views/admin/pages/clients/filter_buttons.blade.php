<div class="btn-group me-1" role="group" aria-label="Date Filters">
    <button type="button" class="btn btn-sm btn-outline-primary btn-date-filter" data-period="today">
        {{ __('clients.filters.today') }}
    </button>
    <button type="button" class="btn btn-sm btn-outline-primary btn-date-filter" data-period="week">
        {{ __('clients.filters.this_week') }}
    </button>
    <button type="button" class="btn btn-sm btn-outline-primary btn-date-filter" data-period="month">
        {{ __('clients.filters.this_month') }}
    </button>
</div>

<div class="btn-group me-1" role="group" aria-label="Account Status">
    <button type="button" class="btn btn-sm btn-outline-success btn-status-filter" data-status="activated">
        <i data-feather='check-circle'></i> {{ __('clients.filters.activated') }}
    </button>
    <button type="button" class="btn btn-sm btn-outline-warning btn-status-filter" data-status="non_activated">
        <i data-feather='x-circle'></i> {{ __('clients.filters.non_activated') }}
    </button>
</div>

<div class="btn-group me-1" role="group" aria-label="Account Activity">
    <button type="button" class="btn btn-sm btn-outline-info btn-activity-filter" data-activity="with_orders">
        <i data-feather='shopping-cart'></i> {{ __('clients.filters.with_orders') }}
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary btn-activity-filter" data-activity="without_orders">
        <i data-feather='shopping-bag'></i> {{ __('clients.filters.without_orders') }}
    </button>
</div>

<div class="btn-group me-1" role="group" aria-label="Gender">
    <button type="button" class="btn btn-sm btn-outline-primary btn-gender-filter" data-gender="male">
        <i data-feather='user'></i> {{ __('clients.filters.male') }}
    </button>
    <button type="button" class="btn btn-sm btn-outline-pink btn-gender-filter" data-gender="female">
        <i data-feather='user'></i> {{ __('clients.filters.female') }}
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary btn-gender-filter" data-gender="unspecified">
        <i data-feather='user-x'></i> {{ __('clients.filters.unspecified') }}
    </button>
</div>

<div class="btn-group me-1" role="group" aria-label="Growth Rate">
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-info dropdown-toggle" type="button" id="growthRateDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i data-feather='trending-up'></i> {{ __('clients.filters.growth_rate') }}
        </button>
        <ul class="dropdown-menu" aria-labelledby="growthRateDropdown">
            <li><a class="dropdown-item growth-rate-filter" href="#" data-period="7">{{ __('clients.filters.weekly_growth') }}</a></li>
            <li><a class="dropdown-item growth-rate-filter" href="#" data-period="30">{{ __('clients.filters.monthly_growth') }}</a></li>
            <li><a class="dropdown-item growth-rate-filter" href="#" data-period="90">{{ __('clients.filters.quarterly_growth') }}</a></li>
            <li><a class="dropdown-item growth-rate-filter" href="#" data-period="365">{{ __('clients.filters.yearly_growth') }}</a></li>
        </ul>
    </div>
</div>

<button type="button" class="btn btn-sm btn-outline-secondary me-1" id="resetFilters">
    <i data-feather='refresh-cw'></i> {{ __('clients.filters.reset') }}
</button>
