<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->post('/movies/{imdbID}', 'MovieController@create')->name('movie.create');
Route::middleware('api')->delete('/movies/{imdbID}', 'MovieController@destroy')->name('movie.delete');
Route::middleware('api')->get('/movies/{imdbID}', 'MovieController@show')->name('movie.show');
Route::middleware('api')->get('/movies', 'MovieController@index')->name('movie.index');
Route::middleware('api')->get('/movies/list', 'MovieController@list')->name('movie.list');
Route::middleware('api')->get('/new-movies', 'MovieController@new')->name('movie.new');
Route::middleware('api')->get('/top-movies', 'MovieController@top')->name('movie.top');
Route::middleware('api')->get('/sync/list', 'SyncController@list')->name('sync.list');