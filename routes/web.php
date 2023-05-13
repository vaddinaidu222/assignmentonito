<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/api/v1/longest-duration-movies', [controller::class, 'longest_duration_movies']);
Route::post('/api/v1/new-movie', [controller::class, 'new_movies']);
Route::get('/api/v1/top-rated-movies', [controller::class, 'top_rated_movies']);
Route::get('/v1/genre-movies-with-subtotals', [controller::class, 'display_votenum']);
Route::post('/api/v1/update-runtime-minutes', [controller::class, 'update_runtime_minutes']);
