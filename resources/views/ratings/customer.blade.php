@extends('layouts.app')

@section('title', __('ratings.my_ratings'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ __('ratings.my_ratings') }}</h4>
                </div>
                <div class="card-body">
                    @forelse($ratings as $rating)
                        <div class="rating-item border-bottom pb-3 mb-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="rating-header">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rating-stars me-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="star {{ $i <= $rating->rating ? 'active' : '' }}">★</span>
                                                @endfor
                                            </div>
                                            <span class="rating-text">{{ $rating->rating_text }}</span>
                                        </div>
                                        <h6 class="service-name">{{ $rating->service_name }}</h6>
                                        <div class="rating-meta">
                                            <small class="text-muted">
                                                {{ __('ratings.supplier') }}: <strong>{{ $rating->supplier->name }}</strong>
                                                • {{ __(ucfirst($rating->service_type)) }}
                                                • {{ $rating->rated_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                    @if($rating->comment)
                                        <div class="rating-comment mt-2">
                                            <p class="mb-0">{{ $rating->comment }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="rating-status">
                                        @if($rating->is_verified)
                                            <span class="badge badge-success">{{ __('ratings.verified') }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ __('ratings.pending_verification') }}</span>
                                        @endif
                                    </div>
                                    <div class="transaction-id mt-2">
                                        <small class="text-muted">{{ __('ratings.transaction') }}: {{ $rating->transaction_id }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('ratings.no_ratings_yet') }}</h5>
                                <p class="text-muted">{{ __('ratings.no_ratings_description') }}</p>
                            </div>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    @if($ratings->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $ratings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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

.empty-state {
    padding: 2rem;
}

.service-name {
    color: #495057;
    margin-bottom: 0.5rem;
}
</style>
@endsection
