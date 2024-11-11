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
        Schema::create('probation_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('probation_id')->constrained('probations')->onDelete('cascade');
            $table->text('work_title');
            $table->text('work_requirement');
            $table->date('work_deadline');
            $table->string('instructor')->nullable();
            $table->text('work_result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('probation_plans');
    }
};
