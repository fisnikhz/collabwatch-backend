<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\APIController;
use App\Models\User;
use Illuminate\Http\Request;

class FindUserByIdController extends APIController
{
    public function __invoke(User $user)
    {
        return $this->respondWithSuccess($user->load('media'));
    }
}
