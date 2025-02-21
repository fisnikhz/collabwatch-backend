<?php

namespace App\Http\Controllers\API\V1\Friend;

use App\Http\Controllers\API\APIController;
use App\Http\Services\FriendService;
use App\Models\User;

class BlockFriendController extends APIController
{
    protected $friendService;

    public function __construct(FriendService $friendService)
    {
        $this->friendService = $friendService;
    }

    public function __invoke(User $user)
    {
        return $this->friendService->blockUser($user);
    }
}
