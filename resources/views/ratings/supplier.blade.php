@extends('layouts.app')

@section('title', __('ratings.supplier_ratings'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('ratings.ratings_for') }} {{ $supplier->name }}</h4>
                        <div class="rating-summary">
                            <span class="badge badge-primary badge-lg">
                                {{ number_format($stats['average_rating'], 1) }} ★
                            </span>
                            <small class="text-muted">({{ $stats['total_ratings'] }} {{ __('ratings.reviews') }})</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Rating Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="rating-stats">
                                <h6>{{ __('ratings.rating_distribution') }}</h6>
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="rating-bar mb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="rating-label">{{ $i }} ★</span>
                                            <div class="progress flex-grow-1 mx-2">
                                                @php
                                                    $percentage = $stats['total_ratings'] > 0 ? ($stats['rating_distribution'][$i] / $stats['total_ratings']) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar bg-warning" 
                                                     role="progressbar" 
                                                     style="width: {{ $percentage }}%"
                                                     aria-valuenow="{{ $percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="rating-count">{{ $stats['rating_distribution'][$i] }}</span>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-ratings">
                                <h6>{{ __('ratings.ratings_by_service') }}</h6>
                                <div class="service-rating-item mb-2">
                                    <span class="service-type">{{ __('ratings.tours') }}:</span>
                                    <span class="rating-value">{{ number_format($ratingsByType['tour'], 1) }} ★</span>
                                </div>
                                <div class="service-rating-item mb-2">
                                    <span class="service-type">{{ __('ratings.events') }}:</span>
                                    <span class="rating-value">{{ number_format($ratingsByType['event'], 1) }} ★</span>
                                </div>
                                <div class="service-rating-item mb-2">
                                    <span class="service-type">{{ __('ratings.gifts') }}:</span>
                                    <span class="rating-value">{{ number_format($ratingsByType['gift'], 1) }} ★</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Options -->
                    <div class="filter-section mb-4">
                        <form method="GET" action="{{ route('ratings.supplier', $supplier->id) }}" class="row g-3">
                            <div class="col-md-4">
                                <select name="service_type" class="form-select">
                                    <option value="">{{ __('ratings.all_services') }}</option>
                                    <option value="tour" {{ $serviceType == 'tour' ? 'selected' : '' }}>{{ __('ratings.tours') }}</option>
                                    <option value="event" {{ $serviceType == 'event' ? 'selected' : '' }}>{{ __('ratings.events') }}</option>
                                    <option value="gift" {{ $serviceType == 'gift' ? 'selected' : '' }}>{{ __('ratings.gifts') }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">{{ __('ratings.filter') }}</button>
                            </div>
                        </form>
                    </div>

                    <!-- Ratings List -->
                    <div class="ratings-list">
                        @forelse($ratings as $rating)
                            <div class="rating-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="rating-header">
                                        <div class="rating-stars mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="star {{ $i <= $rating->rating ? 'active' : '' }}">★</span>
                                            @endfor
                                            <span class="rating-text ms-2">{{ $rating->rating_text }}</span>
                                        </div>
                                        <div class="rating-meta">
                                            <small class="text-muted">
                                                {{ __('ratings.by') }} 
                                                <strong>{{ $rating->customer_name ?: $rating->customer->name ?? __('ratings.anonymous') }}</strong>
                                                • {{ $rating->rated_at->format('M d, Y') }}
                                                • {{ __(ucfirst($rating->service_type)) }}
                                                @if(!$rating->is_verified)
                                                    <span class="badge badge-warning ms-2">{{ __('ratings.unverified') }}</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <div class="rating-actions">
                                        <small class="text-muted">{{ __('ratings.transaction') }}: {{ $rating->transaction_id }}</small>
                                    </div>
                                </div>
                                @if($rating->comment)
                                    <div class="rating-comment mt-2">
                                        <p class="mb-0">{{ $rating->comment }}</p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-muted">{{ __('ratings.no_ratings_found') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($ratings->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $ratings->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-summary .badge-lg {
    font-size: 1.2rem;
    padding: 0.5rem 1rem;
}

.rating-bar {
    display: flex;
    align-items: center;
}

.rating-label {
    width: 40px;
    font-size: 0.9rem;
}

.rating-count {
    width: 30px;
    text-align: right;
    font-size: 0.9rem;
}

.progress {
    height: 20px;
}

.service-rating-item {
    display: flex;
    justify-content: between;
}

.service-type {
    flex: 1;
    font-weight: 500;
}

.rating-value {
    color: #ffc107;
    font-weight: bold;
}

.rating-stars .star {
    color: #ddd;
    font-size: 1.2rem;
}

.rating-stars .star.active {
    color: #ffc107;
}

.rating-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

.rating-comment {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    border-left: 3px solid #007bff;
}
</style>
@endsection
