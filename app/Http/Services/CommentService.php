<?php

namespace App\Http\Services;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use App\Models\UserAction;
use Illuminate\Support\Facades\DB;
use App\Enums\UserActionsEnum;

class CommentService
{
    public function addComment(User $user, Video $video, string $body): Comment
    {
        $comment = $video->comments()->create([
            'user_id' => $user->id,
            'body' => $body,
        ]);

        UserAction::updateOrCreate(
            ['video_id' => $video->id, 'user_id' => $user->id],
            ['score' => DB::raw('score + ' . UserActionsEnum::COMMENT_SCORE->value)]
        );

        return $comment;
    }

    public function updateComment(Comment $comment, string $body): Comment
    {
        $comment->update(['body' => $body]);
        return $comment;
    }

    public function deleteComment(Comment $comment): void
    {
        $videoId = $comment->video_id;
        $userId = $comment->user_id;

        $comment->delete();

        UserAction::where('video_id', $videoId)
            ->where('user_id', $userId)
            ->update(['score' => DB::raw('GREATEST(score - ' . UserActionsEnum::COMMENT_SCORE->value . ', 0)')]);
    }
}
