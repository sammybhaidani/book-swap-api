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
            'data' => $genres,
            'message' => 'Genres retrieved'
        ]);
    }
}
