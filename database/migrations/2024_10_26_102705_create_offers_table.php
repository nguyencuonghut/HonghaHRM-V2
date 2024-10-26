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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('current_salary');
            $table->bigInteger('desired_salary');
            $table->bigInteger('insurance_salary');
            $table->bigInteger('position_salary');
            $table->bigInteger('capacity_salary');
            $table->bigInteger('position_allowance');
            $table->text('note')->nullable();
            $table->enum('feedback', ['Đồng ý', 'Từ chối'])->nullable();
            $table->enum('result', ['Ký HĐLĐ', 'Ký HĐTV', 'Ký HĐHV', 'Không đạt'])->nullable();
            $table->foreignId('recruitment_candidate_id')->constrained('recruitment_candidates')->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
