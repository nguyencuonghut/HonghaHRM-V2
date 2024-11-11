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
        Schema::create('regimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('regime_type_id')->constrained('regime_types')->onDelete('cascade');
            $table->date('off_start_date')->nullable(); //Ngày bắt đầu nghỉ
            $table->date('off_end_date')->nullable(); //Ngày kết thúc nghỉ
            $table->text('payment_period')->nullable();
            $table->bigInteger('payment_amount')->nullable();
            $table->enum('status', ['Mở', 'Đóng']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regimes');
    }
};
