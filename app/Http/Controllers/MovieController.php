<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{


    public function index()
    {
        $baseUrl = env('MOVIE_DB_BASE_URL');
        $imageBaseUrl = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $MAX_BANNER = 3;
        $MAX_MOVIE_ITEM = 10;
        $MAX_TV_SHOWS_ITEM = 10;

        // HIT API BANNER
        $bannerResponse = Http::get("{$baseUrl}/trending/movie/week", [
            'api_key' => $apiKey
        ]);

        // Prepare variable
        $bannerArray = [];

        // Check API Response
        if ($bannerResponse->successful()) {
            // Check data is null or not 
            $resultArray = $bannerResponse->object()->results;

            // Check if $resultArray is set
            if (isset($resultArray)) {
                // looping response data
                foreach ($resultArray as $item) {
                    // save response data to new variable
                    array_push($bannerArray, $item);

                    // Max 3 items
                    if (count($bannerArray) == $MAX_BANNER) {
                        break;
                    }
                }
            }
        }

        // HIT API TOP 10 MOVIES
        $topMoviesResponse = Http::get("{$baseUrl}/movie/top_rated", [
            'api_key' => $apiKey
        ]);

        // Prepare variable 
        $topMoviesArray = [];

        // Check API Response
        if ($topMoviesResponse->successful()) {
            // Check is null or not
            $resultArray = $topMoviesResponse->object()->results;

            // looping response data
            if (isset($resultArray)) {
                foreach ($resultArray as $item) {
                    // save response data to new variable
                    array_push($topMoviesArray, $item);

                    // Max 10 items
                    if (count($topMoviesArray) == $MAX_MOVIE_ITEM) {
                        break;
                    }
                }
            }
        }

        // HIT API TOP 10 TV Show
        $topTVShowResponse = Http::get("{$baseUrl}/tv/top_rated", [
            'api_key' => $apiKey
        ]);

        // Prepare variable 
        $topTVShowsArray = [];

        // Check API Response
        if ($topTVShowResponse->successful()) {
            // Check is null or not
            $resultArray = $topTVShowResponse->object()->results;

            // looping response data
            if (isset($resultArray)) {
                foreach ($resultArray as $item) {
                    // save response data to new variable
                    array_push($topTVShowsArray, $item);

                    // Max 10 items
                    if (count($topTVShowsArray) == $MAX_TV_SHOWS_ITEM) {
                        break;
                    }
                }
            }
        }

        return view('home', [
            'baseUrl' => $baseUrl,
            'imageBaseUrl' => $imageBaseUrl,
            'apiKey' => $apiKey,
            'banner' => $bannerArray,
            'topMovies' => $topMoviesArray,
            'topTVShows' => $topTVShowsArray,
        ]);
    }

    public function movies()
    {
        $baseUrl = env('MOVIE_DB_BASE_URL');
        $imageBaseUrl = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $sortBy = "popularity.dec";
        $page = 1;
        $minimalVoter = 100;

        $movieResponse = Http::get("{$baseUrl}/discover/movie", [
            'api_key' => $apiKey,
            'sort_by' => $sortBy,
            'vite_count.gte' => $minimalVoter,
            'page' => $page
        ]);

        $movieArray = [];

        if ($movieResponse->successful()) {
            // Check is null or not
            $resultArray = $movieResponse->object()->results;

            // looping response data
            if (isset($resultArray)) {
                foreach ($resultArray as $item) {
                    // save response data to new variable
                    array_push($movieArray, $item);
                }
            }
        }

        return view('movie', [
            'baseUrl' => $baseUrl,
            'imageBaseUrl' => $imageBaseUrl,
            'apiKey' => $apiKey,
            'movies' => $movieArray,
            'sortBy' => $sortBy,
            'page' => $page,
            'minimalVolter' => $minimalVoter,

        ]);
    }

    public function tvShows()
    {
        $baseUrl = env('MOVIE_DB_BASE_URL');
        $imageBaseUrl = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');
        $sortBy = "popularity.dec";
        $page = 1;
        $minimalVoter = 100;

        $tvResponse = Http::get("{$baseUrl}/discover/tv", [
            'api_key' => $apiKey,
            'sort_by' => $sortBy,
            'vite_count.gte' => $minimalVoter,
            'page' => $page
        ]);

        $tvArray = [];

        if ($tvResponse->successful()) {
            // Check is null or not
            $resultArray = $tvResponse->object()->results;

            // looping response data
            if (isset($resultArray)) {
                foreach ($resultArray as $item) {
                    // save response data to new variable
                    array_push($tvArray, $item);
                }
            }
        }

        return view('tv', [
            'baseUrl' => $baseUrl,
            'imageBaseUrl' => $imageBaseUrl,
            'apiKey' => $apiKey,
            'tvShows' => $tvArray,
            'sortBy' => $sortBy,
            'page' => $page,
            'minimalVolter' => $minimalVoter,

        ]);
    }

    public function search()
    {
        $baseUrl = env('MOVIE_DB_BASE_URL');
        $imageBaseUrl = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');

        return view('search', [
            'baseUrl' => $baseUrl,
            'imageBaseUrl' => $imageBaseUrl,
            'apiKey' => $apiKey,
        ]);
    }

    public function movieDetails($id)
    {
        $baseUrl = env('MOVIE_DB_BASE_URL');
        $imageBaseUrl = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');

        $response = Http::get("{$baseUrl}/movie/{$id}?api_key={$apiKey}&append_to_response=videos");

        $movieData = null;

        if ($response->successful()) {
            $movieData = $response->object();
        }

        return view('movie_details', [
            'baseUrl' => $baseUrl,
            'imageBaseUrl' => $imageBaseUrl,
            'apiKey' => $apiKey,
            'movieData' => $movieData
        ]);
    }

    public function tvDetails($id)
    {
        $baseUrl = env('MOVIE_DB_BASE_URL');
        $imageBaseUrl = env('MOVIE_DB_IMAGE_BASE_URL');
        $apiKey = env('MOVIE_DB_API_KEY');

        $response = Http::get("{$baseUrl}/tv/{$id}?api_key={$apiKey}&append_to_response=videos");

        $tvData = null;

        if ($response->successful()) {
            $tvData = $response->object();
        }

        return view('tv_details', [
            'baseUrl' => $baseUrl,
            'imageBaseUrl' => $imageBaseUrl,
            'apiKey' => $apiKey,
            'tvData' => $tvData
        ]);
    }
}
