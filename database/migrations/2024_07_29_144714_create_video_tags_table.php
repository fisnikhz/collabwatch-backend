<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('video_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id');
            $table->json('tags');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('video_tags');
    }
};
