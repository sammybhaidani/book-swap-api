<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'review' => 'required|string',
            'rating' => 'required|integer|min:0|max:5',
            'book_id' => 'required|integer|exists:books,id'
        ]);

        $review = Review::create([
            'book_id' => $validated['book_id'],
            'reviewer_name' => $validated['name'],
            'review_text' => $validated['review'],
            'rating' => $validated['rating']
        ]);

        return response()->json([
            'message' => 'Review created'
        ], 201);
    }
}
