<?php

namespace App\Http\Services;

use App\Http\Resources\API\V1\UserResource;
use App\Models\User;
use App\Traits\APIResponse;

class UserService
{
    use APIResponse;
    public function findUserWithMedia(User $user)
    {
        return $user->load(['media','likedVideos','videos','savedVideos']);
    }

    public function getUserVideos(User $user)
    {
        $videos = $user->videos;

        return $videos;
    }

    public function updateUser(User $user, $data)
    {
        $user->update($data);
        return $user->refresh();
    }
}
