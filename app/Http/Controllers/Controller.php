<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function longest_duration_movies(){
        $movies = Movie::with('rating')
            ->orderByDesc('runtimeMinutes')
            ->select('tconst', 'primaryTitle', 'runtimeMinutes', 'genres')
            ->limit(10)
            ->get();


        foreach ($movies as $movie) {
            $data[] = [
                'tconst' => $movie->tconst,
                'primaryTitle' => $movie->primaryTitle,
                'runtimeMinutes' => $movie->runtimeMinutes,
                'genres' => $movie->genres
            ];
        }
        $jsonData = json_encode($data);
        return response($jsonData)->header('Content-Type', 'application/json');

    }

    public function new_movies(Request $request){

        $data = $request->validate([
            'tconst' => 'required',
            'titleType' => 'required',
            'primaryTitle' => 'required',
            'runtimeMinutes' => 'required|numeric',
            'genres' => 'required',
            'average_rating' => 'required|numeric',
            'num_votes' => 'required|numeric',
        ]);

        $movie = new Movie;
        $movie->tconst = $data['tconst'];
        $movie->title_type = $data['titleType'];
        $movie->primaryTitle = $data['primaryTitle'];
        $movie->runtimeMinutes = $data['runtimeMinutes'];
        $movie->genres = $data['genres'];
        $movie->save();

        $rating = new Rating();
        $rating->tconst = $data['tconst'];
        $rating->average_rating = $data['average_rating'];
        $rating->num_votes = $data['num_votes'];
        $movie->save();

        return response('success', 200);
    }


    public function top_rated_movies(){
        $movies = Movie::with('rating')
            ->whereHas('rating', function($query) {
    $query->where('average_rating', '>', 6.0);
        })
            ->select('tconst', 'primaryTitle','genres')
            ->get();

      $movies = $movies->map(function($movie) {
      return [
        'tconst' => $movie->tconst,
        'primaryTitle' => $movie->primaryTitle,
        'genre' => $movie->genres,
        'averageRating' => $movie->rating->average_rating,
      ];
    });

// Sort the array by averageRating
$movies = $movies->sortByDesc('averageRating')->values()->all();

// Return the results as JSON
return response()->json($movies);

}

    public function display_votenum(){
        $results = DB::select("
    SELECT
        m.genres AS genre,
        m.primaryTitle AS primaryTitle,
        SUM(r.num_votes) AS numtotal
    FROM movies m
    JOIN ratings r ON m.tconst = r.tconst
    GROUP BY m.genres, m.primaryTitle
    WITH ROLLUP
   ");

        return view('votes_number', ['genreTotal' => $results]);

    }

    public function update_runtime_minutes(){
        DB::statement("
    UPDATE movies
    SET runtimeMinutes = CASE
        WHEN genres LIKE '%Documentary%' THEN runtimeMinutes + 15
        WHEN genres LIKE '%Animation%' THEN runtimeMinutes + 30
        ELSE runtimeMinutes + 45
    END
   ");

        return response('success', 200);
    }

}
