<?php

namespace App\Http\Controllers\API\V1\Friend;

use App\Http\Controllers\API\APIController;
use App\Http\Resources\API\V1\FriendResource;
use App\Http\Services\FriendService;

class GetFriendRequestsController extends APIController
{
    public $friendService;

    public function __construct(FriendService $friendService){
        $this->friendService = $friendService;
    }
    public function __invoke()
    {
        $friendRequests = $this->friendService->getFriendRequests();

        return $this->respondWithSuccess(FriendResource::collection($friendRequests));
    }
}
