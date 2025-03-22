@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 110px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Write a Review</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('reviews.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Place Type</label>
                            <select name="place_type" class="form-select @error('place_type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="mosque">Mosque</option>
                                <option value="restaurant">Restaurant</option>
                                <option value="hotel">Hotel</option>
                            </select>
                            @error('place_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Place Name</label>
                            <input type="text" name="place_name" class="form-control @error('place_name') is-invalid @enderror" required>
                            @error('place_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" required>
                            @error('location')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                    <label for="star{{ $i }}"><i class="bi bi-star-fill"></i></label>
                                @endfor
                            </div>
                            @error('rating')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Review</label>
                            <textarea name="review_text" class="form-control @error('review_text') is-invalid @enderror" rows="4" required></textarea>
                            @error('review_text')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Photos (optional)</label>
                            <input type="file" name="photos[]" class="form-control @error('photos.*') is-invalid @enderror" multiple accept="image/*">
                            @error('photos.*')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div class="form-text">You can upload multiple photos. Maximum 2MB each.</div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Post Review</button>
                            <a href="{{ route('reviews.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #ddd;
    padding: 0 0.1em;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffd700;
}
</style>
@endsection 