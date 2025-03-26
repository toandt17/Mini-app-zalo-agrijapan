<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Bảng lưu thông tin xem video
        Schema::create('video_watches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('video_id'); // ID của video (YouTube ID hoặc ID nội bộ)
            $table->integer('watch_duration')->default(0); // Thời gian xem (giây)
            $table->timestamp('watched_at');

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Bảng lưu thông tin đọc bài viết
        Schema::create('article_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('article_id');
            $table->integer('read_time')->default(0); // Thời gian đọc (giây)
            $table->timestamp('read_at');

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Bảng lưu thông tin bình luận bài viết
        Schema::create('article_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('article_id');
            $table->text('comment_text');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_comments');
        Schema::dropIfExists('article_reads');
        Schema::dropIfExists('video_watches');
    }
};
