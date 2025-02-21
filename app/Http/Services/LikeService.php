<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Video;

class LikeService
{
    public function toggleLike(User $user, Video $video): bool
    {
        $changes = $user->likedVideos()->toggle($video->id);

        return !empty($changes['attached']);
    }

}
