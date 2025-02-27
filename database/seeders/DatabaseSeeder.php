<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Video;
use App\Jobs\ProcessVideo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $users = User::factory(20)->create();
        $videos = $this->getAllVideoUrlsFromS3();

        if ($users->isEmpty() || empty($videos)) {
            echo "No users or videos found. Seeder skipped.\n";
            return;
        }

        foreach ($videos as $videoUrl) {
            $user = $users->random();
            $this->assignVideoToUser($user, $videoUrl);
        }
    }

    private function assignVideoToUser($user, $videoUrl)
    {
        echo "Assigning video to User ID {$user->id}...\n";

        $video = Video::create([
            'user_id' => $user->id,
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ]);

        $media = $video->addMediaFromUrl($videoUrl)
            ->toMediaCollection('videos', 's3');

        if ($media) {
            ProcessVideo::dispatch($media, $video->id);
        }

        echo "Video assigned & ProcessVideo job dispatched for Video ID: {$video->id}\n";
    }

    private function getAllVideoUrlsFromS3()
    {
        $files = Storage::disk('s3')->allFiles('videos');

        if (empty($files)) {
            return [];
        }

        return array_map(fn($file) => Storage::disk('s3')->url($file), $files);
    }
}
