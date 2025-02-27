<?php

namespace App\Http\Services;

use App\Enums\UserActionsEnum;
use App\Models\User;
use App\Models\UserAction;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class SaveService
{
    public function toggleSave(User $user, Video $video): bool
    {
        $changes = $user->savedVideos()->toggle($video->id);

        $saved = !empty($changes['attached']);

        if ($saved) {
            UserAction::updateOrCreate(
                ['video_id' => $video->id, 'user_id' => $user->id],
                ['score' => DB::raw('score + ' . UserActionsEnum::SAVE_SCORE->value)]
            );
        } else {
            UserAction::where('video_id', $video->id)
                ->where('user_id', $user->id)
                ->update(['score' => DB::raw('GREATEST(score - '  . UserActionsEnum::SAVE_SCORE->value . ', 0)')]);
        }

        return $saved;
    }

}
