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
                $stateName = $airport['state_name'] ?? '';
                $code = $airport['code'] ?? '';
                $name = $airport['name'] ?? '';

                return [
                    'value'      => $code,
                    'label'      => "{$name} ({$code}) - {$stateName}",
                    'code'       => strtolower($code),
                    'state'      => strtolower($stateName),
                    'name'       => strtolower($name),
                    'raw_name'   => $name,
                    'raw_code'   => $code,
                    'raw_state'  => $stateName,
                ];
            })
            ->values();
    }

    /**
     * Get the raw airports dataset keyed by IATA code.
     *
     * Returns the full JSON rows (id, name, code, country_code, state_code,
     * state_name, local) indexed by uppercase IATA code. Used by the SEO
     * flight-route pages so airport city/name/country data always comes from
     * airports.json — never the `airports` DB table. Cached for 24 hours.
     */
    public function lookup(): Collection
    {
        return Cache::remember('airports_lookup_code_map', self::CACHE_TTL, function () {
            $path = database_path('data/airports.json');
            $raw = json_decode(file_get_contents($path), true);

            return collect($raw)->keyBy(fn (array $airport) => strtoupper((string) ($airport['code'] ?? '')));
        });
    }

    /**
     * Bust the cache (e.g. after updating the JSON file).
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget('airports_lookup_code_map');
    }
}
