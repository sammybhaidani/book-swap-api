<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    // GET /api/books - Return all books, optionally filtered
    public function index(Request $request): JsonResponse
    {
        $query = Book::with(['genre', 'reviews']);

        if ($request->has('genre_id')) {
            $query->where('genre_id', $request->genre_id);
        }

        if ($request->has('available')) {
            $query->where('available', $request->boolean('available'));
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $books = $query->get();

        return response()->json([
            'status' => 200,
            'message' => 'Books retrieved successfully',
            'data' => $books
        ]);
    }

    // GET /api/books/{id} - Return a specific book
    public function show(int $id): JsonResponse
    {
        $book = Book::with(['genre', 'reviews'])->find($id);

        if (!$book) {
            return response()->json([
                'status' => 404,
                'message' => 'Book not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Book retrieved successfully',
            'data' => $book
        ]);
    }

    // POST /api/books - Add a new book
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre_id' => 'required|exists:genres,id',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url'
        ]);

        $book = Book::create($validated);
        $book->load(['genre', 'reviews']);

        return response()->json([
            'status' => 201,
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }

    // PUT /api/books/{id}/claim - Claim a book
    public function claim(Request $request, int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => 404,
                'message' => 'Book not found',
                'data' => null
            ], 404);
        }

        if (!$book->available) {
            return response()->json([
                'status' => 400,
                'message' => 'Book is not available',
                'data' => null
            ], 400);
        }

        $validated = $request->validate([
            'claimed_by' => 'required|string|max:255'
        ]);

        $book->update([
            'available' => false,
            'claimed_by' => $validated['claimed_by']
        ]);

        $book->load(['genre', 'reviews']);

        return response()->json([
            'status' => 200,
            'message' => 'Book claimed successfully',
            'data' => $book
        ]);
    }

    // PUT /api/books/{id}/return - Return a book
    public function returnBook(int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => 404,
                'message' => 'Book not found',
                'data' => null
            ], 404);
        }

        $book->update([
            'available' => true,
            'claimed_by' => null
        ]);

        $book->load(['genre', 'reviews']);

        return response()->json([
            'status' => 200,
            'message' => 'Book returned successfully',
            'data' => $book
        ]);
    }

    // DELETE /api/books/{id} - Delete a book
    public function destroy(int $id): JsonResponse
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => 404,
                'message' => 'Book not found',
                'data' => null
            ], 404);
        }

        $book->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Book deleted successfully',
            'data' => null
        ]);
    }
}
