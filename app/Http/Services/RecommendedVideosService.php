<?php

namespace App\Http\Services;

use App\Models\UserAction;
use App\Models\VideoTag;
use Illuminate\Support\Facades\Http;

class RecommendedVideosService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.recommendation_engine.base_url');
    }

    public function getPersonalizedFeed($userId)
    {
        $response = Http::get("{$this->baseUrl}/recommendations/" . "{$userId}");
        if ($response->successful()) {
            $responseJson = $response->json();

            if (array_values($responseJson) === $responseJson) {
                return array_keys($responseJson);
            }

            return $responseJson;
        }

        throw new \Exception('Error fetching data from Spring Boot service');
    }

    public function getPopularVideos()
    {
        $query = UserAction::query()
            ->orderByDesc('score')
            ->with('video.likes', 'video.tags');

        if (request()->has('filters')) {
            $filters = request()->query('filters');

            if (!empty($filters)) {
                $query->whereHas('video.tags', function ($q) use ($filters) {
                    $q->whereJsonContains('tags', $filters);
                });
            }
        }

        // Paginate results
        $response = $query->paginate(16);

        // Transform response to return only videos
        $response->getCollection()->transform(fn($item) => $item->video);

        return $response;
    }

}
