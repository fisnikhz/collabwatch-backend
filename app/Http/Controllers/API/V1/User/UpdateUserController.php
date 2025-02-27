<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\V1\User\UpdateUserRequest;
use App\Http\Resources\API\V1\UserResource;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;

class UpdateUserController extends APIController
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(UpdateUserRequest $request): JsonResponse
    {
        $user = $this->userService->updateUser(auth()->user(),$request->validated());

        return $this->respondWithSuccess(UserResource::make($user));
    }

}
