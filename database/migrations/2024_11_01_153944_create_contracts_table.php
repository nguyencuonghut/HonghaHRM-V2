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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('position_id')->constrained('positions');
            $table->foreignId('contract_type_id')->constrained('contract_types');
            $table->string('file_path')->nullable();
            $table->enum('status', ['On', 'Off']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('request_terminate_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
