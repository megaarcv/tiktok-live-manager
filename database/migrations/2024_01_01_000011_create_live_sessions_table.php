<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tiktok_account_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->enum('status', ['live', 'ended', 'scheduled'])->default('ended');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->bigInteger('peak_viewers')->default(0);
            $table->bigInteger('total_viewers')->default(0);
            $table->bigInteger('total_likes')->default(0);
            $table->bigInteger('total_comments')->default(0);
            $table->bigInteger('total_shares')->default(0);
            $table->bigInteger('diamonds_earned')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_sessions');
    }
};
