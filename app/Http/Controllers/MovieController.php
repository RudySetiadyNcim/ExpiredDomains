<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movie;
use App\Rating;
use DB;
use Carbon\Carbon;
use GuzzleHttp\Client;

class MovieController extends Controller
{
    protected $model;

    public function __construct(Movie $model) 
    {
        $this->model = $model;
    }

    /**
     * Create a new movie instance.
     *
     * @param Request
     */
    public function create($imdbID)
    {
        $imdbAPI = env('IMDB_API', '');
        $uri = 'http://www.omdbapi.com/?i='.$imdbID.'&apikey='.$imdbAPI;

        $api = new Client();
        $res = $api->request('POST', $uri);

        $body = (string)$res->getBody();
        $newMovie = json_decode($body);

        $resource = $this->model->find($imdbID);

        DB::beginTransaction();
        try {

            try {
                $released_date = Carbon::parse($newMovie->Released)->toDateString();
            }
            catch(\Exception $e) {
                $released_date = null;
            }

            try {
                $dvd_date = Carbon::parse($newMovie->DVD)->toDateString();
            }
            catch(\Exception $e) {
                $dvd_date = null;
            }

            if($resource != null) {
                $resource->title = $newMovie->Title;
                $resource->year = $newMovie->Year;
                $resource->rated = $newMovie->Rated;
                $resource->released = $newMovie->Released;
                $resource->released_date = $released_date;
                $resource->runtime = $newMovie->Runtime;
                $resource->genre = $newMovie->Genre;
                $resource->director = $newMovie->Director;
                $resource->writer = $newMovie->Writer;
                $resource->actors = $newMovie->Actors;
                $resource->plot = $newMovie->Plot;
                $resource->language = $newMovie->Language;
                $resource->country = $newMovie->Country;
                $resource->awards = $newMovie->Awards;
                $resource->poster = $newMovie->Poster;
                $resource->metascore = $newMovie->Metascore;
                $resource->metascore_integer = is_numeric($newMovie->Metascore) ? (int)$newMovie->Metascore : null;
                $resource->imdb_rating = $newMovie->imdbRating;
                $resource->imdb_rating_integer = is_numeric($newMovie->imdbRating) ? (int)$newMovie->imdbRating : null;
                $resource->imdb_votes = $newMovie->imdbVotes;
                $resource->imdb_votes_integer = is_numeric(str_replace(',', '', $newMovie->imdbVotes)) ? (int)str_replace(',', '', $newMovie->imdbVotes) : null;
                $resource->imdb_id = $newMovie->imdbID;
                $resource->type = $newMovie->Type;
                $resource->dvd = $newMovie->DVD;
                $resource->dvd_date = $dvd_date;
                $resource->box_office = $newMovie->BoxOffice;
                $resource->production = $newMovie->Production;
                $resource->website = $newMovie->Website;
                $resource->response = $newMovie->Response;
                $resource->save();
                $this->UpdateChildren($newMovie);
            }
            else {

                $released_date = Carbon::parse($newMovie->Released)->toDateString();

                $resource = Movie::create([
                    'title' => $newMovie->Title,
                    'year' => $newMovie->Year,
                    'rated' => $newMovie->Rated,
                    'released' => $newMovie->Released,
                    'released_date' => $released_date,
                    'runtime' => $newMovie->Runtime,
                    'genre' => $newMovie->Genre,
                    'director' => $newMovie->Director,
                    'writer' => $newMovie->Writer,
                    'actors' => $newMovie->Actors,
                    'plot' => $newMovie->Plot,
                    'language' => $newMovie->Language,
                    'country' => $newMovie->Country,
                    'awards' => $newMovie->Awards,
                    'poster' => $newMovie->Poster,
                    'metascore' => $newMovie->Metascore,
                    'metascore_integer' => is_numeric($newMovie->Metascore) ? (int)$newMovie->Metascore : null,
                    'imdb_rating' => $newMovie->imdbRating,
                    'imdb_rating_integer' => is_numeric($newMovie->imdbRating) ? (int)$newMovie->imdbRating : null,
                    'imdb_votes' => str_replace(',', '', $newMovie->imdbVotes),
                    'imdb_votes_integer' => is_numeric(str_replace(',', '', $newMovie->imdbVotes)) ? (int)str_replace(',', '', $newMovie->imdbVotes) : null,
                    'imdb_id' => $newMovie->imdbID,
                    'type' => $newMovie->Type,
                    'dvd' => $newMovie->DVD,
                    'dvd_date' => $dvd_date,
                    'box_office' => $newMovie->BoxOffice,
                    'production' => $newMovie->Production,
                    'website' => $newMovie->Website,
                    'response' => $newMovie->Response
                ]);
                $this->UpdateChildren($newMovie);
            }
            DB::commit();
            $success = true;
        } catch(\Exception $e) {
            $success = false;
            DB::rollback();
            dd($e);
        }

        if($success) {
            return response()->json([
                'status' => 'success',
                'resource' => $resource
            ]);
        }
        else {
            return response()->json(['status' => 'failed']);
        }
    }

    public function index() {
        return Movie::orderBy('released_date', 'asc')
            ->get();
    }

    public function show($imdbID) {
        $movie = Movie::find($imdbID);
        $movie->ratings = Rating::where('imdb_id', $imdbID)->get();
        return $movie;
    }

    public function new(Request $request) {
        $newMovies = Movie::select('movies.imdb_id', 'movies.title', 'movies.poster')->orderBy('released_date', 'desc')->take(6)->get();
        return $newMovies;
    }    

    public function top(Request $request) {
        $newMovies = Movie::select('movies.imdb_id', 'movies.title', 'movies.poster')->orderBy('imdb_rating', 'desc')->take(6)->get();
        return $newMovies;
    }

    public function destroy($imdbID) {
        DB::beginTransaction();
        try {
            Rating::where('imdb_id', $imdbID)->delete();
            Movie::where('imdb_id', $imdbID)->delete();
            DB::commit();
            $success = true;
        } catch(\Exception $e) {
            $success = false;
            DB::rollback();
        }

        if($success) {
            return response()->json(['status' => 'success']);
        }
        else {
            return response()->json(['status' => 'failed']);
        }
    }

    private function UpdateChildren($newMovie) {
        // Ratings
        Rating::where('imdb_id', $newMovie->imdbID)->delete();
        $ratings = $newMovie->Ratings;
        foreach($ratings as $rating) {
            Rating::create([
                'imdb_id' => $newMovie->imdbID,
                'source' => $rating->Source,
                'value' => $rating->Value
            ]);        
        }
    }
}
