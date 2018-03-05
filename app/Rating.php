<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ratings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'imdb_id',
        'source',
        'value'
    ];

    public function movie()
    {
        return $this->belongsTo('App\Movie', 'imdb_id');
    }
}
