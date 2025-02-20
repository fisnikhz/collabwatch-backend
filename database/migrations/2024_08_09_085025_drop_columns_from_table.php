<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('user_actions', function (Blueprint $table) {
            $table->dropColumn(['type','content']);
        });
    }

    public function down(): void
    {
        Schema::table('user_actions', function (Blueprint $table) {
            $table->string('type');
            $table->string('content');
        });
    }
};
