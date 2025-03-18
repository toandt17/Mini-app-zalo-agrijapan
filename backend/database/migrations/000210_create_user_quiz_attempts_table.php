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
        Schema::create('user_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('question_id');
            $table->char('selected_option', 1);
            $table->boolean('is_correct');
            $table->integer('points_earned')->default(0);
            $table->unsignedBigInteger('reward_id')->nullable();
            $table->integer('spin_tickets')->default(0);
            $table->timestamp('attempt_time')->useCurrent();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('quiz_questions')->onDelete('cascade');
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
        Schema::dropIfExists('user_quiz_attempts');
    }
};
