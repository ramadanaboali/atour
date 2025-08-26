@extends('admin.layouts.master')

@section('title', __('ratings.admin_ratings'))

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('ratings.rating_management') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('admin.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('ratings.admin_ratings') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('ratings.all_ratings') }}</h3>
                        </div>
                        <div class="card-body">
                            <!-- Filters -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <form method="GET" action="{{ route('admin.ratings.index') }}" class="row g-3">
                                        <div class="col-md-2">
                                            <select name="supplier_id" class="form-control">
                                                <option value="">{{ __('ratings.filter_by_supplier') }}</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="service_type" class="form-control">
                                                <option value="">{{ __('ratings.filter_by_service') }}</option>
                                                <option value="tour" {{ request('service_type') == 'tour' ? 'selected' : '' }}>{{ __('ratings.tours') }}</option>
                                                <option value="event" {{ request('service_type') == 'event' ? 'selected' : '' }}>{{ __('ratings.events') }}</option>
                                                <option value="gift" {{ request('service_type') == 'gift' ? 'selected' : '' }}>{{ __('ratings.gifts') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="rating" class="form-control">
                                                <option value="">{{ __('ratings.filter_by_rating') }}</option>
                                                @for($i = 5; $i >= 1; $i--)
                                                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} ★</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="is_verified" class="form-control">
                                                <option value="">{{ __('ratings.filter_by_verification') }}</option>
                                                <option value="1" {{ request('is_verified') == '1' ? 'selected' : '' }}>{{ __('ratings.verified') }}</option>
                                                <option value="0" {{ request('is_verified') == '0' ? 'selected' : '' }}>{{ __('ratings.unverified') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="{{ __('ratings.date_from') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="{{ __('ratings.date_to') }}">
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <button type="submit" class="btn btn-primary">{{ __('ratings.apply_filters') }}</button>
                                            <a href="{{ route('admin.ratings.index') }}" class="btn btn-secondary">{{ __('ratings.clear_filters') }}</a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Ratings Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ratings.customer_info') }}</th>
                                            <th>{{ __('ratings.supplier') }}</th>
                                            <th>{{ __('ratings.service_type') }}</th>
                                            <th>{{ __('ratings.rating') }}</th>
                                            <th>{{ __('ratings.comment') }}</th>
                                            <th>{{ __('ratings.verification_status') }}</th>
                                            <th>{{ __('ratings.rating_date') }}</th>
                                            <th>{{ __('admin.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($ratings as $rating)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong>{{ $rating->customer_name ?: $rating->customer->name ?? __('ratings.anonymous') }}</strong>
                                                    </div>
                                                    <small class="text-muted">{{ $rating->customer_email ?: $rating->customer->email ?? 'N/A' }}</small>
                                                    <br>
                                                    <small class="text-muted">{{ __('ratings.transaction') }}: {{ $rating->transaction_id }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $rating->supplier->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $rating->supplier->email }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">{{ __(ucfirst($rating->service_type)) }}</span>
                                                </td>
                                                <td>
                                                    <div class="rating-stars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <span class="star {{ $i <= $rating->rating ? 'active' : '' }}">★</span>
                                                        @endfor
                                                    </div>
                                                    <small class="text-muted">{{ $rating->rating_text }}</small>
                                                </td>
                                                <td>
                                                    @if($rating->comment)
                                                        <div class="comment-preview" title="{{ $rating->comment }}">
                                                            {{ Str::limit($rating->comment, 50) }}
                                                        </div>
                                                    @else
                                                        <span class="text-muted">{{ __('ratings.no_comment') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($rating->is_verified)
                                                        <span class="badge badge-success">{{ __('ratings.verified') }}</span>
                                                    @else
                                                        <span class="badge badge-warning">{{ __('ratings.unverified') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $rating->rated_at->format('M d, Y H:i') }}
                                                    <br>
                                                    <small class="text-muted">{{ $rating->ip_address }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <form method="POST" action="{{ route('admin.ratings.toggle-verification', $rating->id) }}" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm {{ $rating->is_verified ? 'btn-warning' : 'btn-success' }}" 
                                                                    title="{{ $rating->is_verified ? __('ratings.mark_unverified') : __('ratings.mark_verified') }}">
                                                                <i class="fas {{ $rating->is_verified ? 'fa-times' : 'fa-check' }}"></i>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.ratings.destroy', $rating->id) }}" 
                                                              style="display: inline;" 
                                                              onsubmit="return confirm('{{ __('admin.are_you_sure') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('ratings.delete_rating') }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">{{ __('ratings.no_ratings_found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
    </section>
</div>

<style>
.rating-stars .star {
    color: #ddd;
    font-size: 1.1rem;
}

.rating-stars .star.active {
    color: #ffc107;
}

.comment-preview {
    max-width: 200px;
    cursor: help;
}

.btn-group .btn {
    margin-right: 2px;
}
</style>
@endsection
