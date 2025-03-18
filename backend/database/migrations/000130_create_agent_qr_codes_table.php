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
        Schema::create('agent_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->string('qr_code_path')->nullable()->comment('Đường dẫn đến file hình ảnh mã QR');
            $table->string('qr_token')->unique()->comment('Token để nhận dạng mã QR, thường là timestamp được mã hóa');
            $table->timestamp('generated_at')->comment('Thời gian mã QR được tạo');
            $table->string('url')->nullable()->comment('URL đầy đủ được mã hóa trong mã QR');
            $table->text('metadata')->nullable()->comment('Thông tin khác (nếu có) được lưu dưới dạng JSON');
            $table->boolean('is_active')->default(true)->comment('Trạng thái của mã QR, true = còn sử dụng được');
            $table->timestamps();

            // Index để tìm kiếm nhanh
            $table->index('qr_token');
            $table->index(['agent_id', 'generated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_qr_codes');
    }
};
