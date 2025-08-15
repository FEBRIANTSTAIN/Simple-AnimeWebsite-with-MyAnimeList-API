<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnimeController extends Controller
{
    public function index(Request $request)
    {
        // Query params
        $q         = trim((string) $request->query('q')) ?: null;
        $genreId   = $request->integer('genre'); // Jikan expects numeric id
        $status    = $request->query('status');   // airing | complete | upcoming
        $minScore  = $request->integer('min_score');
        $orderBy   = $request->query('order_by', 'score'); // popularity | score | rank | favorites
        $sort      = $request->query('sort', 'desc');      // asc | desc
        $page      = max(1, (int) $request->query('page', 1));
        $category  = $request->query('category');          // top | season

        // 1) Genres (untuk sidebar filter)
        $genres = [];
        try {
            $genresRes = Http::timeout(10)->get('https://api.jikan.moe/v4/genres/anime');
            $genres = $genresRes->json('data') ?? [];
        } catch (\Throwable $e) {}

        // 2) Slider data: pakai seasonal now atau top anime
        $slider = [];
        try {
            if ($category === 'season') {
                $seasonRes = Http::timeout(10)->get('https://api.jikan.moe/v4/seasons/now', [ 'page' => 1]);
                $slider = array_slice($seasonRes->json('data') ?? [], 0, 8);
            } else {
                $topRes = Http::timeout(10)->get('https://api.jikan.moe/v4/top/anime', [ 'page' => 1 ]);
                $slider = array_slice($topRes->json('data') ?? [], 0, 8);
            }
        } catch (\Throwable $e) {}

        // 3) Grid list (search + filter)
        $grid = [];
        $pagination = [ 'has_next_page' => false, 'current_page' => $page ];

        try {
            $endpoint = 'https://api.jikan.moe/v4/anime';

            // Kalau kategori = top dan tidak ada query/filter khusus, pakai endpoint top
            if ($category === 'top' && !$q && !$genreId && !$status && !$minScore) {
                $endpoint = 'https://api.jikan.moe/v4/top/anime';
            }

            $params = [
                'page'     => $page,
                'limit'    => 24,
            ];

            if ($endpoint === 'https://api.jikan.moe/v4/anime') {
                if ($q)         $params['q'] = $q;
                if ($genreId)   $params['genres'] = $genreId; // numeric id
                if ($status)    $params['status'] = $status;  // airing | complete | upcoming
                if ($minScore)  $params['min_score'] = max(1, min(9, $minScore));
                if ($orderBy)   $params['order_by'] = $orderBy; // score, popularity, rank
                if ($sort)      $params['sort'] = $sort;
            }

            $res = Http::timeout(15)->get($endpoint, $params);
            $grid = $res->json('data') ?? [];
            $pagination = $res->json('pagination') ?? $pagination;
        } catch (\Throwable $e) {}

        return view('anime.index', [
            'q'          => $q,
            'genres'     => $genres,
            'selectedGenre' => $genreId,
            'status'     => $status,
            'minScore'   => $minScore,
            'orderBy'    => $orderBy,
            'sort'       => $sort,
            'category'   => $category,
            'slider'     => $slider,
            'grid'       => $grid,
            'pagination' => $pagination,
        ]);
    }
}
