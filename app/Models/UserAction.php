<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    use HasFactory;

    protected $guarded = [];

    const SCORE_LIKE = 5;
    const SCORE_SAVE = 3;
    const SCORE_COMMENT = 2;


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function video()
    {
        return $this->belongsTo(Video::class);
    }


    public static function likeVideo($videoId, $userId)
    {
        return self::updateOrCreate(
            ['video_id' => $videoId, 'user_id' => $userId],
            ['score' => self::SCORE_LIKE]
        );
    }


    public static function saveVideo($videoId, $userId)
    {
        return self::updateOrCreate(
            ['video_id' => $videoId, 'user_id' => $userId],
            ['score' => self::SCORE_SAVE]
        );
    }

    public static function commentOnVideo($videoId, $userId, $comment)
    {
        return self::updateOrCreate(
            ['video_id' => $videoId, 'user_id' => $userId],
            ['score' => self::SCORE_COMMENT, 'comment' => $comment]
        );
    }

}
