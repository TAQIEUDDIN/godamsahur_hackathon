<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Review::with('user')->latest();

        // Filter by place type if specified
        if ($request->has('type')) {
            $query->where('place_type', $request->type);
        }

        // Search by location if specified
        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $reviews = $query->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('reviews.create');
    }

    public function store(Request $request)
    {
        \Log::info('Review submission:', $request->all());
        
        $validated = $request->validate([
            'place_name' => 'required',
            'place_type' => 'required|in:mosque,restaurant,hotel',
            'location' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|min:10',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Generate a random place_id if not provided
        $validated['place_id'] = uniqid('place_');
        
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('review-photos', 'public');
                $photos[] = $path;
            }
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'photos' => $photos,
            'place_id' => $validated['place_id'],
            'place_name' => $validated['place_name'],
            'place_type' => $validated['place_type'],
            'location' => $validated['location'],
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
        ]);

        return redirect()->route('reviews.index')->with('success', 'Review posted successfully!');
    }
}
