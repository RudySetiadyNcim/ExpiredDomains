<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->string('imdb_id')->unique();
            $table->string('title');
            $table->integer('year');
            $table->string('rated');
            $table->string('released');
            $table->date('released_date');
            $table->string('runtime');
            $table->string('genre');
            $table->string('director');
            $table->text('writer');
            $table->string('actors');
            $table->text('plot');
            $table->string('language');
            $table->string('country');
            $table->string('awards');
            $table->string('poster');
            $table->string('metascore');
            $table->integer('metascore_integer')->nullable();
            $table->string('imdb_rating');
            $table->float('imdb_rating_integer')->nullable();
            $table->string('imdb_votes');
            $table->integer('imdb_votes_integer')->nullable();
            $table->string('type');
            $table->string('dvd');
            $table->date('dvd_date')->nullable();
            $table->string('box_office');
            $table->string('production');
            $table->string('website');
            $table->string('response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
