<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    public function index(): JsonResponse
    {
        $genres = Genre::all();

        return response()->json([
            'status' => 200,
            'message' => 'Genres retrieved successfully',
            'data' => $genres
        ]);
    }
}
