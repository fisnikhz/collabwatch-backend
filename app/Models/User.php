<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\FriendStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, HasApiTokens, InteractsWithMedia;

    protected $guarded = [];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function likedVideos()
    {
        return $this->belongsToMany(Video::class, 'likes')->withTimestamps();
    }

    public function savedVideos()
    {
        return $this->belongsToMany(Video::class, 'saves')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function sentFriendRequests()
    {
        return $this->hasMany(Friend::class, 'sender_id')->where('status', FriendStatusEnum::PENDING->value);
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(Friend::class, 'receiver_id')->where('status', FriendStatusEnum::PENDING->value);
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'sender_id', 'receiver_id')
            ->wherePivot('status', FriendStatusEnum::ACCEPTED->value)
            ->withTimestamps();
    }


}
