<?php

namespace App\Jobs;

use Aws\Rekognition\RekognitionClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $videoPath;
    protected $videoId;
    protected $jobId;

    public function __construct($video)
    {
        $this->videoPath = $video['original_url'];
        $this->videoId = $video['id'];
    }

    public function handle()
    {
        Log::info("Processing video: " . $this->videoPath);

        try {
            // Download the video from the URL
            $videoData = Http::get($this->videoPath)->body();
            $videoName = basename($this->videoPath);
            $s3Path = 'videos/' . $videoName;

            // Upload video to S3
            Storage::disk('s3')->put($s3Path, $videoData);
            Log::info('Video uploaded successfully to S3: ' . $s3Path);

            // Initialize Rekognition client
            $rekognition = new RekognitionClient([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);

            // Start label detection
            $result = $rekognition->startLabelDetection([
                'Video' => [
                    'S3Object' => [
                        'Bucket' => env('AWS_BUCKET'),
                        'Name' => $s3Path,
                    ],
                ],
                'MinConfidence' => 75,
            ]);

            $this->jobId = $result['JobId'];
            Log::info('Started label detection with JobId: ' . $this->jobId);

            // Optionally, you can dispatch another job to check the status of the detection job
            CheckDetectionStatus::dispatch($this->jobId, $this->videoId)->delay(now()->addMinutes(5));
        } catch (\Exception $e) {
            Log::error('Error processing video: ' . $e->getMessage());
        }
    }
}

class CheckDetectionStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobId;
    protected $videoId;

    public function __construct($jobId, $videoId)
    {
        Log::info($videoId);
        $this->jobId = $jobId;
        $this->videoId = $videoId;
    }

    public function handle()
    {
        Log::info("Checking detection status for JobId: " . $this->jobId);

        try {
            // Initialize Rekognition client
            $rekognition = new RekognitionClient([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);

            // Get label detection results
            $result = $rekognition->getLabelDetection([
                'JobId' => $this->jobId,
            ]);

            Log::info('Raw detection result: ' . json_encode($result));

            if ($result['JobStatus'] === 'SUCCEEDED') {
                // Extract tags with confidence >= 90
                $tags = collect($result['Labels'])
                    ->filter(function ($label) {
                        return $label['Label']['Confidence'] >= 90;
                    })
                    ->pluck('Label.Name')
                    ->toArray();

                // Save tags in the database
                $this->storeTagsInDatabase($tags);

                Log::info('Detection completed successfully. Tags with confidence >= 90 have been stored.');
            } elseif ($result['JobStatus'] === 'FAILED') {
                Log::error('Label detection failed.');
            } else {
                Log::info('Detection job is still in progress.');
                // Requeue the job to check again later
                self::dispatch($this->jobId, $this->videoId)->delay(now()->addMinutes(5));
            }
        } catch (\Exception $e) {
            Log::error('Error checking detection status: ' . $e->getMessage());
        }
    }

    protected function storeTagsInDatabase(array $tags)
    {
        DB::table('video_tags')->updateOrInsert(
            ['video_id' => $this->videoId],
            ['tags' => json_encode($tags), 'updated_at' => now()]
        );
    }
}
