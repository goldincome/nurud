<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class AirportService
{
    /**
     * Cache key for the pre-formatted airports dropdown data.
     */
    private const CACHE_KEY = 'airports_json_dropdown';

    /**
     * Cache duration in seconds (24 hours).
     * The JSON file rarely changes, so a long TTL is safe.
     */
    private const CACHE_TTL = 86400;

    /**
     * Get all airports pre-formatted for the frontend dropdown.
     * Each entry has: { value: "LOS", label: "Lagos (LOS) - Murtala Mohammed..." }
     *
     * Data is loaded from the static JSON file and cached in memory.
     */
    public function getAll(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return $this->loadAndFormat();
        });
    }

    /**
     * Load airports from the JSON file and format them for dropdown use.
     */
    private function loadAndFormat(): Collection
    {
        $path = database_path('data/airports.json');

        $raw = json_decode(file_get_contents($path), true);

        return collect($raw)
            ->map(function (array $airport) {
                $city = $airport['state_name'] ?? '';
                $code = $airport['code'] ?? '';
                $name = $airport['name'] ?? '';

                return [
                    'value' => $code,
                    'label' => "{$name}, {$city} ({$code})",
                    'code'  => strtolower($code),
                    'city'  => strtolower($city),
                    'name'  => strtolower($name),
                ];
            })
            ->values();
    }

    /**
     * Bust the cache (e.g. after updating the JSON file).
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
