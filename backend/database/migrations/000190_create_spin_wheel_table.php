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
        Schema::create('spin_wheel', function (Blueprint $table) {
            $table->id();
            $table->string('prize_name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('probability', 5, 2);
            $table->integer('remaining_quantity');
            $table->boolean('has_reward')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spin_wheel');
    }
};
