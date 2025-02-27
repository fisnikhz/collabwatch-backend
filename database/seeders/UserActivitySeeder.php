<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Video;
use App\Models\Friend;
use App\Enums\FriendStatusEnum;
use App\Http\Services\LikeService;
use App\Http\Services\SaveService;
use App\Http\Services\CommentService;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class UserActivitySeeder extends Seeder
{
    public $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    public function run()
    {
        DB::transaction(function () {
            $this->seedFriends();
            $this->seedUserActions();
        });
    }

    private function seedFriends()
    {
        $users = User::all();

        foreach ($users as $user) {
            $friends = $users->random(rand(3, 10));

            foreach ($friends as $friend) {
                if ($user->id !== $friend->id && !Friend::where([
                        ['sender_id', $user->id],
                        ['receiver_id', $friend->id]
                    ])->exists()) {
                    Friend::create([
                        'sender_id' => $user->id,
                        'receiver_id' => $friend->id,
                        'status' => FriendStatusEnum::ACCEPTED->value,
                    ]);
                }
            }
        }
    }

    private function seedUserActions()
    {
        $users = User::all();
        $videos = Video::all();

        $likeService = new LikeService();
        $saveService = new SaveService();
        $commentService = new CommentService();

        foreach ($users as $user) {
            $randomVideos = $videos->random(rand(5, 50));

            foreach ($randomVideos as $video) {
                if (rand(0, 1)) {
                    $likeService->toggleLike($user, $video);
                }

                if (rand(0, 100) < 30) {
                    $commentService->addComment($user, $video, $this->faker->text);
                }

                if (rand(0, 100) < 20) {
                    $saveService->toggleSave($user, $video);
                }
            }
        }
    }
}
