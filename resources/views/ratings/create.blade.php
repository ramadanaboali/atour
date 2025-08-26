@extends('layouts.app')

@section('title', __('ratings.submit_rating'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ __('ratings.rate_your_experience') }}</h4>
                </div>
                <div class="card-body">
                    <!-- Service Information -->
                    <div class="service-info mb-4 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>{{ __('ratings.service_details') }}</h6>
                                <p class="mb-1"><strong>{{ __('ratings.service_type') }}:</strong> {{ __(ucfirst($serviceType)) }}</p>
                                <p class="mb-1"><strong>{{ __('ratings.service_name') }}:</strong> {{ $service->name ?? $service->title ?? 'N/A' }}</p>
                                <p class="mb-0"><strong>{{ __('ratings.transaction_id') }}:</strong> {{ $transactionId }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>{{ __('ratings.supplier_info') }}</h6>
                                <p class="mb-1"><strong>{{ __('ratings.supplier_name') }}:</strong> {{ $supplier->name }}</p>
                                <p class="mb-0"><strong>{{ __('ratings.supplier_email') }}:</strong> {{ $supplier->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Form -->
                    <form method="POST" action="{{ route('ratings.store') }}" id="ratingForm">
                        @csrf
                        
                        <!-- Hidden Fields -->
                        <input type="hidden" name="transaction_id" value="{{ $transactionId }}">
                        <input type="hidden" name="service_type" value="{{ $serviceType }}">
                        <input type="hidden" name="service_id" value="{{ $serviceId }}">
                        <input type="hidden" name="supplier_id" value="{{ $supplierId }}">

                        <!-- Star Rating -->
                        <div class="form-group mb-4">
                            <label class="form-label">{{ __('ratings.your_rating') }} <span class="text-danger">*</span></label>
                            <div class="star-rating" id="starRating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star" data-rating="{{ $i }}">★</span>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingValue" value="{{ old('rating') }}">
                            <div class="rating-text mt-2" id="ratingText"></div>
                            @error('rating')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Comment -->
                        <div class="form-group mb-4">
                            <label for="comment" class="form-label">{{ __('ratings.your_comment') }}</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" 
                                      name="comment" 
                                      rows="4" 
                                      placeholder="{{ __('ratings.comment_placeholder') }}">{{ old('comment') }}</textarea>
                            <small class="form-text text-muted">{{ __('ratings.comment_optional') }}</small>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @guest
                        <!-- Guest User Information -->
                        <div class="guest-info mb-4">
                            <h6>{{ __('ratings.your_information') }}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_name" class="form-label">{{ __('ratings.your_name') }}</label>
                                        <input type="text" 
                                               class="form-control @error('customer_name') is-invalid @enderror" 
                                               id="customer_name" 
                                               name="customer_name" 
                                               value="{{ old('customer_name') }}"
                                               placeholder="{{ __('ratings.name_placeholder') }}">
                                        @error('customer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_email" class="form-label">{{ __('ratings.your_email') }}</label>
                                        <input type="email" 
                                               class="form-control @error('customer_email') is-invalid @enderror" 
                                               id="customer_email" 
                                               name="customer_email" 
                                               value="{{ old('customer_email') }}"
                                               placeholder="{{ __('ratings.email_placeholder') }}">
                                        @error('customer_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endguest

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('ratings.cancel') }}</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                {{ __('ratings.submit_rating') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.star-rating {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    user-select: none;
}

.star-rating .star {
    transition: color 0.2s;
    margin-right: 5px;
}

.star-rating .star:hover,
.star-rating .star.active {
    color: #ffc107;
}

.star-rating .star.hover {
    color: #ffc107;
}

.rating-text {
    font-weight: bold;
    color: #495057;
}

.service-info {
    border-left: 4px solid #007bff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingValue = document.getElementById('ratingValue');
    const ratingText = document.getElementById('ratingText');
    const submitBtn = document.getElementById('submitBtn');
    
    const ratingTexts = {
        1: '{{ __("ratings.very_poor") }}',
        2: '{{ __("ratings.poor") }}',
        3: '{{ __("ratings.average") }}',
        4: '{{ __("ratings.good") }}',
        5: '{{ __("ratings.excellent") }}'
    };

    // Set initial rating if there's an old value
    const oldRating = ratingValue.value;
    if (oldRating) {
        updateStars(parseInt(oldRating));
        updateRatingText(parseInt(oldRating));
        submitBtn.disabled = false;
    }

    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.dataset.rating);
            highlightStars(rating);
        });

        star.addEventListener('mouseout', function() {
            const currentRating = parseInt(ratingValue.value) || 0;
            updateStars(currentRating);
        });

        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            ratingValue.value = rating;
            updateStars(rating);
            updateRatingText(rating);
            submitBtn.disabled = false;
        });
    });

    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('hover');
            } else {
                star.classList.remove('hover');
            }
        });
    }

    function updateStars(rating) {
        stars.forEach((star, index) => {
            star.classList.remove('hover');
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    function updateRatingText(rating) {
        ratingText.textContent = ratingTexts[rating] || '';
    }
});
</script>
@endsection
