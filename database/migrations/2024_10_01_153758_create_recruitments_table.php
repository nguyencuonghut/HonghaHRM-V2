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
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained('positions');
            $table->integer('quantity');
            $table->longText('reason');
            $table->longText('requirement')->nullable();
            $table->bigInteger('salary')->nullable();
            $table->date('work_time');
            $table->longText('note')->nullable();
            $table->enum('status', ['Mở', 'Đã kiểm tra', 'Đã duyệt', 'Đóng', 'Hủy']);
            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('reviewer_id')->nullable()->contrainded('users');
            $table->enum('reviewer_result', ['Đồng ý', 'Từ chối'])->nullable();
            $table->dateTime('reviewed_time')->nullable();
            $table->longText('reviewer_comment')->nullable();
            $table->foreignId('approver_id')->nullable()->contrained('users');
            $table->enum('approver_result', ['Đồng ý', 'Từ chối'])->nullable();
            $table->longText('approver_comment')->nullable();
            $table->dateTime('approved_time')->nullable();
            $table->dateTime('completed_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments');
    }
};
