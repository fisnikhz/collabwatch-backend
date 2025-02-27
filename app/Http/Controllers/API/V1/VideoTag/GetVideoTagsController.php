<?php

namespace App\Http\Controllers\API\V1\VideoTag;

use App\Http\Controllers\API\APIController;
use App\Http\Services\VideoTagService;
use Illuminate\Http\JsonResponse;

class GetVideoTagsController extends ApiController
{
    private VideoTagService $videTagService;

    public function __construct(VideoTagService $videTagService)
    {
        $this->videTagService = $videTagService;
    }

    public function __invoke(): JsonResponse
    {
        $videoTags = $this->videTagService->getTopTags();
        return $this->respondWithSuccess($videoTags);
    }
}
