<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
/**
 * JoinDate: ngày vào thử việc của nhân sự.
 * Dùng để tính toán việc 1 yêu cầu tuyển dụng có đúng hạn hay không.
 * Còn thâm niên, sử dụng ngày ký của hợp đồng lao động ký mới gần nhất theo table SeniorityReport
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('join_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('recruitment_candidate_id')->nullable()->constrained('recruitment_candidates')->onDelete('cascade');
            $table->date('join_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('join_dates');
    }
};
