<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    public $incrementing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'movies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'imdb_id', 
        'title', 
        'year', 
        'rated', 
        'released', 
        'released_date', 
        'runtime', 
        'genre', 
        'director', 
        'writer', 
        'actors', 
        'plot', 
        'language',
        'country',
        'awards',
        'poster',
        'metascore',
        'metascore_integer',
        'imdb_rating',
        'imdb_rating_integer',
        'imdb_votes',
        'imdb_votes_integer',
        'type',
        'dvd',
        'dvd_date',
        'box_office',
        'production',
        'website',
        'response'
    ];

    public $primaryKey = "imdb_id";

}
