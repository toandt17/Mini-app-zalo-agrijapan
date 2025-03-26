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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('reward_id')->nullable();
            $table->integer('points_reward')->default(0);
            $table->integer('spin_tickets')->default(0);

            $table->string('action_required')->nullable()->comment('Hành động cần thực hiện: watch_youtube, share_facebook, follow_tiktok, etc');
            $table->json('action_data')->nullable()->comment('Dữ liệu chi tiết như URL, số lượng, thời gian yêu cầu, v.v.');
            $table->integer('difficulty_level')->default(0);
            $table->integer('reward_spin_tickets')->default(1);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('reward_id')->references('id')->on('rewards')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('missions');
    }
};
