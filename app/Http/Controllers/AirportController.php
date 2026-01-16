<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AirportController extends Controller
{
    public function search(Request $request): JsonResponse
    {

         // 1. Fetch from Cache (or DB if cache misses)
        // We select CONCAT raw to pre-format the label for the frontend to save JS processing time
        $airports = Cache::rememberForever('airports_dropdown', function () {
            return DB::table('airports')
                ->selectRaw("code as value, CONCAT(city, ' (', code, ') - ', name) as label, search_vector")
                ->orderBy('city')
                ->get();
        });

        // 2. Return with HTTP Caching Headers
        return response()->json($airports)
            ->setEtag(md5($airports->toJson())); // Browser won't re-download if ETag matches
    
        /*
        // 1. Faster Validation
        $validated = $request->validate([
            'q' => 'required|string|min:2|max:50'
        ]);
        
        $query = $validated['q'];

        // 2. Optimized Query
        $airports = Airport::query()
            ->select('id', 'code')
            // Pre-generate the "Label" in SQL to avoid PHP looping/mapping overhead
            ->selectRaw("CONCAT(city, ' (', code, ') - ', name) as label")
            ->where(function ($q) use ($query) {
                // Primary: Try Full Text Search (Fastest)
                $q->whereRaw("MATCH(search_vector) AGAINST(? IN BOOLEAN MODE)", [$query . '*'])
                // Fallback: If query is 3 chars (e.g. 'JFK'), LIKE is safer due to MySQL limits
                  ->orWhere('code', 'LIKE', "$query%")
                  ->orWhere('city', 'LIKE', "$query%");
            })
            // Order by relevance manually if mixing LIKE and FULLTEXT
            ->orderByRaw("CASE WHEN code = ? THEN 1 WHEN city LIKE ? THEN 2 ELSE 3 END", [$query, "$query%"])
            ->limit(20) // Limit strictly to 20 for UI performance
            ->get();

        return response()->json($airports);
        */
    }
}
