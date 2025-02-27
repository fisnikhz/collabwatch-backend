<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\APIController;
use App\Http\Resources\API\V1\VideoResource;
use App\Http\Services\UserService;
use App\Models\User;

class GetUserVideosController extends APIController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(User $user)
    {
        $videos = $this->userService->getUserVideos($user);
        return $this->respondWithSuccess(VideoResource::collection($videos));
    }

}
