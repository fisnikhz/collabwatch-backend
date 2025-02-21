<?php

namespace App\Http\Controllers\API\V1\Video;

use App\Http\Controllers\API\APIController;
use App\Http\Services\SaveService;
use App\Models\Video;
use Illuminate\Http\JsonResponse;

class SaveVideoController extends APIController
{
    private SaveService $saveService;

    public function __construct(SaveService $saveService)
    {
        $this->saveService = $saveService;
    }

    public function __invoke(Video $video): JsonResponse
    {
        $saved = $this->saveService->toggleSave(request()->user(), $video);

        return $this->respondWithSuccess($saved);
    }
}
