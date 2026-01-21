<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // GET /api/books - Return all books with optional filters
    public function index(Request $request): JsonResponse
    {
        $query = Book::with('genre');

        // Filter by genre (accepts both 'genre' and 'genre_id' parameters)
        if ($request->has('genre') || $request->has('genre_id')) {
            $genreId = $request->input('genre') ?? $request->input('genre_id');
            $query->where('genre_id', $genreId);
        }

        // Filter by claimed status (frontend sends 'claimed', we check 'available')
        if ($request->has('claimed')) {
            $isClaimed = $request->boolean('claimed');
            // If claimed=true, we want books where available=false
            // If claimed=false, we want books where available=true
            $query->where('available', !$isClaimed);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $books = $query->get();

        return response()->json([
            'data' => $books,
            'message' => 'Books successfully retrieved'
        ]);
    }

    // GET /api/books/{id} - Return a specific book
    public function show(int $id): JsonResponse
    {
        $book = Book::with(['genre', 'reviews'])->find($id);

        if (!$book) {
            return response()->json([
                'message' => "Book with id {$id} not found"
            ], 404);
        }

        return response()->json([
            'data' => $book,
            'message' => 'Book successfully found'
        ]);
    }

    // POST /api/books - Add a new book
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'blurb' => 'nullable|string|max:1000',
            'image' => 'nullable|url|max:500',
            'year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'page_count' => 'nullable|integer|min:1|max:10000',
        ]);

        $book = Book::create($validated);
        $book->load(['genre', 'reviews']);

        return response()->json([
            'message' => 'Book created'
        ], 201);
    }

    // PUT /api/books/{id}/claim - Claim a book
    public function claim(Request $request, int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => "Book {$id} was not found"
            ], 404);
        }

        if (!$book->available) {
            return response()->json([
                'message' => "Book {$id} is already claimed"
            ], 400);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $book->update([
            'available' => false,
            'claimed_by_name' => $validated['name'],
            'claimed_by_email' => $validated['email'],
        ]);

        return response()->json([
            'message' => "Book {$id} was claimed"
        ]);
    }

    // PUT /api/books/return/{id} - Return a book
    public function returnBook(Request $request, int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => "Book {$id} was not found"
            ], 404);
        }

        if ($book->available) {
            return response()->json([
                'message' => "Book {$id} is not currently claimed"
            ], 400);
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        if ($book->claimed_by_email !== $validated['email']) {
            return response()->json([
                'message' => "Book {$id} was not returned. {$validated['email']} did not claim this book."
            ], 400);
        }

        try {
            $book->update([
                'available' => true,
                'claimed_by_name' => null,
                'claimed_by_email' => null,
            ]);

            return response()->json([
                'message' => "Book {$id} was returned"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Book {$id} was not able to be returned"
            ], 500);
        }
    }

    // DELETE /api/books/{id} - Delete a book
    public function destroy(int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => "Book {$id} was not found"
            ], 404);
        }

        $book->delete();

        return response()->json([
            'message' => "Book {$id} was deleted"
        ]);
    }
}
