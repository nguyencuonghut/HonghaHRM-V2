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
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_candidate_id')->constrained('recruitment_candidates')->onDelete('cascade');
            $table->string('work_location')->nullable();
            $table->bigInteger('salary');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->enum('reviewer_result', ['Đạt', 'Loại']);
            $table->text('reviewer_comment')->nullable();
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('approver_result', ['Đạt', 'Loại'])->nullable();
            $table->text('approver_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filters');
    }
};
