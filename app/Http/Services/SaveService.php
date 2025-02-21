<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Video;

class SaveService
{
    public function toggleSave(User $user, Video $video): bool
    {
        $changes = $user->savedVideos()->toggle($video->id);

        return !empty($changes['attached']);
    }

}
