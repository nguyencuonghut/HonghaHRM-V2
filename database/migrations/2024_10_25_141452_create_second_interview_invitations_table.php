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
        Schema::create('second_interview_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_candidate_id')->constrained('recruitment_candidates')->onDelete('cascade');
            $table->enum('status', ['Đã gửi', 'Chưa gửi']);
            $table->dateTime('interview_time');
            $table->text('interview_location');
            $table->text('contact');
            $table->enum('feedback', ['Đồng ý', 'Từ chối', 'Hẹn lại'])->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('second_interview_invitations');
    }
};
