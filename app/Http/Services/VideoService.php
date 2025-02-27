<?php

namespace App\Http\Services;

use App\Models\Video;
use App\Traits\APIResponse;

class VideoService
{
    use APIResponse;
    public function getVideoDetails(Video $video): Video
    {
        return $video->load(['media', 'likes', 'comments', 'saves', 'user']);
    }

}
