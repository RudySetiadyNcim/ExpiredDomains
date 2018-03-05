<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movie;

class SyncController extends Controller
{
    public function list(Request $request) {
        $movies = Movie::select('movies.imdb_id', 'movies.title')->get();
        return $movies;
    }
}
