<?php

namespace App\Http\Controllers;

use App\Services\AirportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function __construct(
        private readonly AirportService $airportService
    ) {}

    /**
     * Return the full pre-formatted airports list for the frontend autocomplete.
     * Data is loaded from the cached JSON file — no DB calls.
     */
    public function search(Request $request): JsonResponse
    {
        $airports = $this->airportService->getAll();

        return response()->json($airports)
            ->setEtag(md5($airports->toJson()))
            ->header('Cache-Control', 'public, max-age=86400'); // Browser caches for 24h
    }
}
