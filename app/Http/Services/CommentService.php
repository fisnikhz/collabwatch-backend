<?php
namespace App\Http\Services;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;

class CommentService
{
    public function addComment(User $user, Video $video, string $body): Comment
    {
        return $video->comments()->create([
            'user_id' => $user->id,
            'body' => $body,
        ]);
    }

    public function updateComment(Comment $comment, string $body): Comment
    {
        $comment->update(['body' => $body]);
        return $comment;
    }

    public function deleteComment(Comment $comment): void
    {
        $comment->delete();
    }
}
