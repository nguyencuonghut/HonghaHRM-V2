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
        Schema::create('probations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('recruitment_id')->constrained('recruitments')->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('result_of_work', ['Hoàn thành', 'Không hoàn thành'])->nullable();
            $table->enum('result_of_attitude', ['Tốt', 'Khá', 'Trung bình', 'Kém'])->nullable();
            $table->enum('result_manager_status', ['Đạt', 'Không đạt'])->nullable();
            $table->enum('result_reviewer_status', ['Đồng ý', 'Từ chối'])->nullable();
            $table->time('result_review_time')->nullable();
            $table->foreignId('result_reviewer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('approver_result', ['Đồng ý', 'Từ chối'])->nullable();
            $table->string('approver_comment')->nullable();
            $table->time('approver_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('probations');
    }
};
