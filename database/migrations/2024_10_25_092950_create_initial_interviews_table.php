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
        Schema::create('initial_interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_candidate_id')->constrained('recruitment_candidates')->onDelete('cascade');
            $table->text('health_comment')->nullable();
            $table->integer('health_score');
            $table->text('attitude_comment')->nullable();
            $table->integer('attitude_score');
            $table->text('stability_comment')->nullable();
            $table->integer('stability_score');
            $table->foreignId('interviewer_id')->constrained('users')->onDelete('cascade');
            $table->integer('total_score');
            $table->enum('result', ['Đạt', 'Không đạt']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initial_interviews');
    }
};
