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
        Schema::create('kpi_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');
            $table->integer('year');
            $table->float('jan')->nullable();
            $table->float('feb')->nullable();
            $table->float('mar')->nullable();
            $table->float('apr')->nullable();
            $table->float('may')->nullable();
            $table->float('jun')->nullable();
            $table->float('jul')->nullable();
            $table->float('aug')->nullable();
            $table->float('sep')->nullable();
            $table->float('oct')->nullable();
            $table->float('nov')->nullable();
            $table->float('dec')->nullable();
            $table->float('year_avarage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_reports');
    }
};
