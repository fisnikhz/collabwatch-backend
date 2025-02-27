<?php

namespace App\Http\Services;

use App\Models\VideoTag;

class VideoTagService
{
    public function getTopTags()
    {
        $tags = VideoTag::pluck('tags')
            ->map(function ($tag) {
                return json_decode($tag, true);
            })
            ->filter()
            ->flatten();

        $topTags = collect($tags)
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(8);

        return $topTags;
    }
}
