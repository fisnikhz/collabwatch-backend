<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class RecommendedVideosService
{

    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.recommendation_engine.base_url');
    }

    public function __invoke($userId)
    {
        $response = Http::get("{$this->baseUrl}/recommendations/"."{$userId}");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Error fetching data from Spring Boot service');
    }
}
