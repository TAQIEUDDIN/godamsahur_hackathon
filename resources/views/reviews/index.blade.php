@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 110px;">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Reviews</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('reviews.create') }}" class="btn btn-primary">Write a Review</a>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('reviews.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="mosque" {{ request('type') == 'mosque' ? 'selected' : '' }}>Mosques</option>
                        <option value="restaurant" {{ request('type') == 'restaurant' ? 'selected' : '' }}>Restaurants</option>
                        <option value="hotel" {{ request('type') == 'hotel' ? 'selected' : '' }}>Hotels</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="location" class="form-control" placeholder="Search by location..." value="{{ request('location') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews List -->
    @foreach($reviews as $review)
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title">{{ $review->place_name }}</h5>
                <span class="badge bg-primary">{{ ucfirst($review->place_type) }}</span>
            </div>
            <h6 class="card-subtitle mb-2 text-muted">{{ $review->location }}</h6>
            
            <div class="mb-2">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                        <i class="bi bi-star-fill text-warning"></i>
                    @else
                        <i class="bi bi-star text-warning"></i>
                    @endif
                @endfor
            </div>
            
            <p class="card-text">{{ $review->review_text }}</p>
            
            @if($review->photos)
            <div class="review-photos mb-3">
                @foreach($review->photos as $photo)
                    <img src="{{ Storage::url($photo) }}" alt="Review photo" class="img-thumbnail me-2" style="width: 100px; height: 100px; object-fit: cover;">
                @endforeach
            </div>
            @endif
            
            <div class="text-muted">
                <small>By {{ $review->user->name }} - {{ $review->created_at->diffForHumans() }}</small>
            </div>
        </div>
    </div>
    @endforeach

    {{ $reviews->links() }}
</div>
@endsection 