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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->nullable()->constrained('recruitments')->onDelete('cascade');
            $table->bigInteger('budget')->nullable();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('approver_result', ['Đồng ý', 'Từ chối'])->nullable();
            $table->text('approver_comment')->nullable();
            $table->enum('status', ['Chưa duyệt', 'Đã duyệt']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
