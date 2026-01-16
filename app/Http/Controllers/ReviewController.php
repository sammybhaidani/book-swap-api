<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function store(Request $request, int $bookId): JsonResponse
    {
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'review_text' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $validated['book_id'] = $bookId;

        $review = Review::create($validated);

        return response()->json([
            'status' => 201,
            'message' => 'Review created successfully',
            'data' => $review
        ], 201);
    }
}
