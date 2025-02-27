<?php

namespace App\Http\Controllers\API\V1\Feed;

use App\Http\Controllers\API\APIController;
use App\Http\Resources\API\V1\VideoResource;
use App\Http\Services\FeedService;
use App\Http\Services\RecommendedVideosService;
use Illuminate\Http\JsonResponse;


class ExploreFeedController extends APIController
{
    private RecommendedVideosService $feedService;

    public function __construct(RecommendedVideosService $feedService)
    {
        $this->feedService = $feedService;
    }

    public function __invoke(): JsonResponse
    {
        $exploreFeed = $this->feedService->getPopularVideos();

        return $this->respondWithSuccess(VideoResource::collection($exploreFeed));
    }
}
