<?php

namespace App\Http\Controllers\API\V1\Video;

use App\Http\Controllers\API\APIController;
use App\Http\Services\CommentService;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class DeleteCommentVideoController extends APIController
{
    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function __invoke(Comment $comment): JsonResponse
    {
        $this->commentService->deleteComment($comment);
        return $this->respondWithSuccess();
    }
}
