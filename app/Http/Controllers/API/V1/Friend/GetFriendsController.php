<?php

namespace App\Http\Controllers\API\V1\Friend;

use App\Http\Controllers\API\APIController;
use App\Http\Services\FriendService;

class GetFriendsController extends APIController
{
    protected $friendService;

    public function __construct(FriendService $friendService)
    {
        $this->friendService = $friendService;
    }

    public function __invoke()
    {
        return $this->friendService->getFriends();
    }
}
