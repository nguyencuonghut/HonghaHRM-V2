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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('division_id')->nullable()->constrained('divisions');
            $table->bigInteger('insurance_salary');
            $table->bigInteger('position_salary');
            $table->bigInteger('max_capacity_salary');
            $table->bigInteger('position_allowance')->nullable();
            $table->string('recruitment_standard_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
