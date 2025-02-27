<?php

namespace App\Http\Controllers\API\V1\Video;

use App\Http\Controllers\API\APIController;
use App\Http\Resources\API\V1\VideoResource;
use App\Http\Services\VideoService;
use App\Models\Video;
use Illuminate\Http\JsonResponse;

class GetVideoController extends APIController
{
    private VideoService $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function __invoke(Video $video): JsonResponse
    {
        $videoDetails = $this->videoService->getVideoDetails($video);

        return $this->respondWithSuccess(VideoResource::make($videoDetails));
    }
}
