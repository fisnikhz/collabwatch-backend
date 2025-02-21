<?php

namespace App\Http\Controllers\API\V1\Video;

use App\Http\Controllers\API\APIController;
use App\Http\Services\LikeService;
use App\Models\Video;
use Illuminate\Http\JsonResponse;

class LikeVideoController extends APIController
{
    private LikeService $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function __invoke(Video $video): JsonResponse
    {
        $liked = $this->likeService->toggleLike(request()->user(), $video);

        return $this->respondWithSuccess($liked);
    }
}
