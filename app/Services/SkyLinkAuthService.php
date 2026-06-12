<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SkyLinkAuthService
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('247travels.base_url', 'https://247travels.com/api'), '/');
        $this->username = config('247travels.username');
        $this->password = config('247travels.password');
    }

    public function getAccessToken(): string
    {
        $token = Cache::get(config('247travels.token_cache_key'));
        $expiresAt = Cache::get(config('247travels.token_expiry_cache_key'));

        if ($token && $expiresAt && now()->lt($expiresAt->copy()->subMinutes(2))) {
            return $token;
        }

        return $this->login();
    }

    protected function login(): string
    {
        try {
            $response = Http::withOptions(['connect_timeout' => 120])
                ->timeout(360)
                ->retry(3, 2000)
                ->post("{$this->baseUrl}/api/login", [
                    'email' => $this->username,
                    'password' => $this->password,
                ]);

            if (!$response->successful()) {
                Log::error('SkyLink login failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('SkyLink authentication failed: ' . $response->body());
            }

            $body = $response->body();
            $body = preg_replace('/^\xEF\xBB\xBF/', '', $body);
            $decoded = json_decode($body, true);
            if (!$decoded || !isset($decoded['data'])) {
                Log::error('SkyLink login: invalid JSON response', ['body' => $body]);
                throw new \Exception('SkyLink login: invalid response format');
            }
            $data = $decoded['data'];
            $accessToken = $data['access_token'];
            $refreshToken = $data['refresh_token'];
            $expiresIn = $data['expires_in'] ?? 900;

            $expiresAt = now()->addSeconds($expiresIn);

            Cache::put(config('247travels.token_cache_key'), $accessToken, $expiresAt);
            Cache::put(config('247travels.refresh_token_cache_key'), $refreshToken, now()->addDays(14));
            Cache::put(config('247travels.token_expiry_cache_key'), $expiresAt, $expiresAt);

            return $accessToken;
        } catch (\Exception $e) {
            Log::error('SkyLink login exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function clearTokens(): void
    {
        Cache::forget(config('247travels.token_cache_key'));
        Cache::forget(config('247travels.refresh_token_cache_key'));
        Cache::forget(config('247travels.token_expiry_cache_key'));
    }
}
