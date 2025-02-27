<?php

namespace App\Http\Services;

use App\Enums\UserActionsEnum;
use App\Models\User;
use App\Models\UserAction;
use App\Models\Video;
use function Sodium\increment;

class LikeService
{
    public function toggleLike(User $user, Video $video): bool
    {
        $changes = $user->likedVideos()->toggle($video->id);

        $liked = !empty($changes['attached']);

        if ($liked) {
            UserAction::updateOrCreate(
                ['video_id' => $video->id, 'user_id' => $user->id],
                ['score' => \DB::raw('score + ' . UserActionsEnum::LIKE_SCORE->value)]
            );
        } else {
            UserAction::where('video_id', $video->id)
                ->where('user_id', $user->id)
                ->update(['score' => \DB::raw('GREATEST(score - ' . UserActionsEnum::LIKE_SCORE->value . ', 0)')]);
        }

        return $liked;
    }


}
