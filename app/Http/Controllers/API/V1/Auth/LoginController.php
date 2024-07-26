<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\V1\Auth\LoginRequest;
use App\Http\Resources\API\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LoginController extends APIController
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->email)->first();


        if (!auth()->attempt($request->only('email', 'password'))) {
            return $this->respondWithError([], __('auth.failed'));
        }

        $response = [
            'token' => $user->createToken('Personal Token')->plainTextToken,
            'user' => UserResource::make($user->load('media')),
        ];

        return $this->respondWithSuccess($response, __('app.login.success'));
    }
}
