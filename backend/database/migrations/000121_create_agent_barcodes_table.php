<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agent_barcodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->string('barcode_value')->unique(); // Giá trị barcode
            $table->string('barcode_image')->nullable(); // Đường dẫn đến hình ảnh barcode
            $table->string('agent_code', 50); // Mã quy ước đại lý
            $table->string('order_code', 50)->nullable(); // Mã đơn hàng (nếu có)
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('generated_at')->nullable();
            $table->text('metadata')->nullable(); // Lưu trữ metadata dạng JSON
            $table->timestamps();

            // Foreign key
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_barcodes');
    }
};
