<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->integer('duration')->nullable()->after('size');
            $table->string('thumbnail_url')->nullable()->after('size');

        });
    }

    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('duration');
            $table->dropColumn('thumbnail_url');
        });
    }
};
